<?php /* Originating Release: February 2012 */
use RightNow\Connect\v1_2 as RNCPHP;
require_once( get_cfg_var("doc_root")."/include/ConnectPHP/Connect_init.phph" );	
initConnectAPI();

class contact_model extends Model
{
    private $cacheHandlePrefix = 'contact';
    function __construct()
    {
        parent::__construct();
        $this->load->model("standard/Customfield_model");
    }

   /**
     * Returns a Contact middle layer object from the database based on the contact id.
     *
     * @return (Object) An instance of the Contact middle layer object
     * @param $contactID int The id of the contact to retrieve
     * @param $formatter Formatter
     */
    function get($contactID, $formatter = null)
    {
        if(!$contactID)
            return null;

        $contact = RnowBase::checkFormattedRecordCache($this->cacheHandlePrefix, $contactID, $formatter);
        if($contact && $contact->c_id->value === $contactID) {
            return $contact;
        }

        $customFields = getCustomFieldList(TBL_CONTACTS, VIS_CF_ALL);
        $numberOfCustomFields = count($customFields);
        $customFieldColumns = getCustomFieldQueryString($customFields, 'c');

        $si = sql_prepare("select c.login, c.password_hash, c.email, c.first_name, c.last_name, c.alt_first_name, c.alt_last_name,
            c.email_alt1, c.email_alt2, c.street, c.city,c.ma_opt_in, c.ma_mail_type,
            c.postal_code, c.country_id, c.prov_id, c.ph_office, c.ph_mobile,
            c.ph_fax, c.ph_asst, c.ph_home, c.title, c.ma_state, c.sa_state, c.css_state, c.password_email_exp, c.survey_opt_in, o.login, o.name, c.disabled $customFieldColumns
            from contacts c
            LEFT JOIN orgs o ON c.org_id=o.org_id
            where c.c_id = $contactID");


        $i = 0;
        sql_bind_col($si, ++$i, BIND_NTS, 81);     //login
        sql_bind_col($si, ++$i, BIND_BIN, 61);     //password
        sql_bind_col($si, ++$i, BIND_NTS, 81);     //email
        sql_bind_col($si, ++$i, BIND_NTS, 81);     //first_name
        sql_bind_col($si, ++$i, BIND_NTS, 81);     //last_name
        sql_bind_col($si, ++$i, BIND_NTS, 81);     //alt_first_name
        sql_bind_col($si, ++$i, BIND_NTS, 81);     //alt_last_name
        sql_bind_col($si, ++$i, BIND_NTS, 81);     //email_alt1
        sql_bind_col($si, ++$i, BIND_NTS, 81);     //email_alt2
        sql_bind_col($si, ++$i, BIND_NTS, 241);    //street
        sql_bind_col($si, ++$i, BIND_NTS, 81);     //city
        sql_bind_col($si, ++$i, BIND_INT, 0);      //ma_opt_in
        sql_bind_col($si, ++$i, BIND_INT, 0);      //ma_mail_type
        sql_bind_col($si, ++$i, BIND_NTS, 11);     //postal_code
        sql_bind_col($si, ++$i, BIND_INT, 0);      //country_id
        sql_bind_col($si, ++$i, BIND_INT, 0);      //prov_id
        sql_bind_col($si, ++$i, BIND_NTS, 41);     //ph_office
        sql_bind_col($si, ++$i, BIND_NTS, 41);     //ph_mobile
        sql_bind_col($si, ++$i, BIND_NTS, 41);     //ph_fax
        sql_bind_col($si, ++$i, BIND_NTS, 41);     //ph_asst
        sql_bind_col($si, ++$i, BIND_NTS, 41);     //ph_home
        sql_bind_col($si, ++$i, BIND_NTS, 41);     //title
        sql_bind_col($si, ++$i, BIND_INT, 0);      //ma_state
        sql_bind_col($si, ++$i, BIND_INT, 0);      //sa_state
        sql_bind_col($si, ++$i, BIND_INT, 0);      //css_state
        sql_bind_col($si, ++$i, BIND_DTTM, 0);     //password_email_exp
        sql_bind_col($si, ++$i, BIND_INT, 0);     //survey_opt_in
        sql_bind_col($si, ++$i, BIND_NTS, 41);     //org_login
        sql_bind_col($si, ++$i, BIND_NTS, 41);     //org_name
        sql_bind_col($si, ++$i, BIND_INT, 0);     //disabled

        bind_cf($customFields, $numberOfCustomFields, $i, $si);

        $row = sql_fetch($si);
        sql_free($si);

        $contact = new Contact();
        $contact->c_id->value = $contactID;
        list(
            $contact->login->value,
            $contact->password->value,
            $contact->email->value,
            $contact->first_name->value,
            $contact->last_name->value,
            $contact->alt_first_name->value,
            $contact->alt_last_name->value,
            $contact->email_alt1->value,
            $contact->email_alt2->value,
            $contact->street->value,
            $contact->city->value,
            $contact->ma_opt_in->value,
            $contact->ma_mail_type->value,
            $contact->postal_code->value,
            $contact->country_id->value,
            $contact->prov_id->value,
            $contact->ph_office->value,
            $contact->ph_mobile->value,
            $contact->ph_fax->value,
            $contact->ph_asst->value,
            $contact->ph_home->value,
            $contact->title->value,
            $marketingState,
            $salesState,
            $serviceState,
            $contact->password_email_exp->value,
            $contact->survey_opt_in->value,
            $contact->organization_login->value,
            $contact->organization_name->value,
            $contact->disabled->value) = $row;

        $contact->disabled->value = ($contact->disabled->value === 1);

        $contact->state->value = array(
            'ma_state' => ($marketingState) ? getMessage(MARKETING_LBL) : null,
            'sa_state' => ($salesState) ? getMessage(SALES_LBL) : null,
            'css_state' => ($serviceState) ? getMessage(SERVICE_LBL) : null);
        if(getConfig(intl_nameorder, 'COMMON'))
        {
            $contact->full_name->value = "{$contact->last_name->value} {$contact->first_name->value}";
            $contact->short_name->value = $contact->last_name->value;
        }
        else
        {
            $contact->full_name->value = "{$contact->first_name->value} {$contact->last_name->value}";
            $contact->short_name->value = $contact->first_name->value;
        }

        //Populate channel fields
        $contact->channels = $this->getChannelFields($contactID);

        //Org fields cannot be updated on enduser pages
        $contact->organization_login->readonly = true;
        $contact->organization_password->readonly = true;

        $contact->ma_mail_type->menu_items = optlistGet(OPTL_MA_MAIL_TYPE);

        $contact->custom_fields = $this->Customfield_model->getCustomFieldArray($customFields, $i, $row);
        $langID = lang_id(LANG_DIR);
        $si = sql_prepare(sprintf('select c.country_id, lc.label, c.postal_mask, p.prov_id, lp.label, c.phone_mask
            from labels lc, countries c left outer join provinces p on (p.country_id = c.country_id)
            left outer join labels lp on ((lp.tbl = %d) and (lp.lang_id = %d) and (p.prov_id = lp.label_id))
            where (c.country_id = lc.label_id) and (lc.tbl = %d) and (lc.lang_id = %d) order by c.seq, p.seq, lp.label',
            TBL_PROVINCES, $langID, TBL_COUNTRIES, $langID));

        sql_bind_col($si, 1, BIND_INT, 0);
        sql_bind_col($si, 2, BIND_NTS, 41);
        sql_bind_col($si, 3, BIND_NTS, 21);
        sql_bind_col($si, 4, BIND_INT, 0);
        sql_bind_col($si, 5, BIND_NTS, 41);
        sql_bind_col($si, 6, BIND_NTS, 81);

        for($lastCountry = 0; $row = sql_fetch($si); )
        {
            if ($row[0] !== $lastCountry)
            {
                $contact->country_id->menu_items[$row[0]] = $row[1];
                if($row[0] === $contact->country_id->value)
                {
                    $contact->postal_code->mask = $row[2];
                    $contact->ph_office->mask = $row[5];
                    $contact->ph_mobile->mask = $row[5];
                    $contact->ph_fax->mask = $row[5];
                    $contact->ph_asst->mask = $row[5];
                    $contact->ph_home->mask = $row[5];
                }
                $lastCountry = $row[0];
            }
            if($contact->country_id->value === $row[0] && $row[3])
            {
                $contact->prov_id->menu_items[$row[3]] = $row[4];
            }
        }
        sql_free($si);

        //add japanese "sama" suffix; when lang != ja_JP this field is blank
        if (LANG_DIR === 'ja_JP')
        {
            $contact->jp_suffix->value = getMessage(NAME_SUFFIX_LBL);
            $contact->full_name->value .= " {$contact->jp_suffix->value}";
        }

        //if this contact is logged in, attach the profile object to the contact object
        $profile = $this->session->getProfile();
        if($profile)
        {
            if($profile->c_id->value == $contactID)
            {
                $contact->profile = unserialize(serialize($profile));
                //We need to get the labels for the prod/cat profile defaults in
                //case we are going to display them. Otherwise they will just display numbers
                if(count($profile->prod->value))
                {
                    $profileProducts = array();
                    foreach($profile->prod->value as $hmValue)
                        if($hmValue != "")
                            array_push($profileProducts, $hmValue);
                    if(count($profileProducts))
                        $contact->profile->prod->value = $this->getProfileHierLabels($profileProducts);
                    else
                        $contact->profile->prod->value = array();
                }
                if(count($profile->cat->value))
                {
                    $profileCategories = array();
                    foreach($profile->cat->value as $hmValue)
                        if($hmValue != "")
                            array_push($profileCategories, $hmValue);
                    if(count($profileCategories))
                        $contact->profile->cat->value = $this->getProfileHierLabels($profileCategories);
                    else
                        $contact->profile->cat->value = array();
                }
            }
        }
        $contact->profile->search_type->menu_items = optlistGet(OPTL_ANS_SRCH_TYPES);
        return RnowBase::setFormattedRecordCache($contact, $contactID, $this->cacheHandlePrefix, $formatter);
    }

    /**
     * Returns an empty contact structure. Used to be able to access contact
     * fields without having a contact ID.
     *
     * @param $formatter Formatter
     * @return object An instance of the contact middle layer object
     */
    function getBlank($formatter = null)
    {
        $contact = checkCache('contactblank');
        if($contact !== null)
            return $contact;

        $contact = new Contact();
        $contact->custom_fields = $this->Customfield_model->getBlankCustomFieldArray(getCustomFieldList(TBL_CONTACTS, VIS_CF_ALL));
        $contact->channels = $this->getChannelFields();

        $si = sql_prepare(sprintf('select c.country_id, lc.label, c.postal_mask, c.phone_mask from labels lc, countries c
            where (c.country_id = lc.label_id) and (lc.tbl = %d) and (lc.lang_id = %d) order by c.seq', TBL_COUNTRIES, lang_id(LANG_DIR)));

        sql_bind_col($si, 1, BIND_INT, 0);
        sql_bind_col($si, 2, BIND_NTS, 41);
        sql_bind_col($si, 3, BIND_NTS, 21);
        sql_bind_col($si, 4, BIND_NTS, 81);

        for($i=0; $row = sql_fetch($si); $i++)
            $contact->country_id->menu_items[$row[0]] = $row[1];
        sql_free($si);

        $mailTypes = optlistGet(OPTL_MA_MAIL_TYPE);
        $contact->ma_mail_type->menu_items = $mailTypes;
        $contact->format($formatter);

        setCache('contactblank', $contact);
        return $contact;
    }

    /**
     * Function to convert a middle layer contact object into pairdata
     * and submit the new contact to the API.
     *
     * @param $contact ContactObject  The new contact to create
     * @param $creatingPassword Boolean - true if the user is changing password
     *       assume that the field password_new wouldn't be on the form unless a password is being created
     * @return mixed A string if an error was encountered or a 1 if successful
     */
    function create($contact, $creatingPassword = false)
    {
        $contact->source->value = ($contact->source->value) ?: SRC2_EU_NEW_CONTACT;
        $preHookData = array('data' => $contact);
        $customHookError = RightNowHooks::callHook('pre_contact_create', $preHookData);
        if(is_string($customHookError))
            return $customHookError;

        $error = $contact->validate($creatingPassword);
        if(!$error)
        {
            $duplicateContact = $this->checkUniqueFields($contact);
            if($duplicateContact !== false)
            {
                if(site_config_int_get(CFG_OPT_DUPLICATE_EMAIL) && $contact->password->overwrite)
                {
                    //allow new contact to be created for an existing email only if coming through 'finish account creation' process
                    //and if the login is unique
                    if($duplicateContact['login'])
                        return $duplicateContact['login'];
                }
                else
                {
                    return implode('<br/>', array_values($duplicateContact));
                }
            }
            //Check if valid org has been entered
            if($contact->organization_login->value)
            {
                $validOrg = $this->checkOrg($contact, $contact->organization_login->value, $contact->organization_password->value);
                if($validOrg !== true)
                    return $validOrg;
            }

            //pairdata has no sense of password_new or anything, so we need to overwrite password here
            //that way when toPairData is called it gets the new password
            //That lets toPairData only worry about password and allows blank passwords
            //the account create page only has new and verify on it
            $contact->password->value = $contact->password_new->value;
            $contact->state->value['css_state'] = 1;
            AbuseDetection::check();
            $newContactID = contact_create($contact->toPairData(null));
            ActionCapture::record('contact', 'create');
            $contact->c_id->value = $newContactID;
            $postHookData = array('data' => $contact, 'returnValue' => $newContactID);
            RightNowHooks::callHook('post_contact_create', $postHookData);
            if($newContactID > 0)
            {
                return $newContactID;
            }
            else
            {
                return getMessage(SORRY_ERROR_SUBMISSION_LBL);
            }
        }
        else
        {
            return $error;
        }
    }

    /**
     * Function to convert a middle layer contact object into pairdata
     * and submit the updated contact to the API
     *
     * @param $contact ContactObject  The new contact data to update
     * @param $prevData Array  The previous state of the contact before being updated.
     * @param $changingPassword Boolean - true if the user is changing password
     *       we assume that the field password_new wouldn't be on the form unless they were changing their password
     * @return mixed A string if an error was encountered or a 1 if successful
     */
    function update($contact, $prevData, $changingPassword = false)
    {
        $contact->source->value = ($contact->source->value) ?: SRC2_EU_CONTACT_EDIT;
        $contact->state->value['css_state'] = 1;
        $preHookData = array('data'=>$contact);
        $customHookError = RightNowHooks::callHook('pre_contact_update', $preHookData);
        if(is_string($customHookError))
            return $customHookError;

        $error = $contact->validate($changingPassword);
        if(!$error)
        {
            //check to see if they are changing their password
            if($changingPassword)
            {
                //if they're changing pws check to see if the old password is the correct one that's in the db
                //not done in the case where the password is being reset
                if($contact->password->overwrite)
                    $valid = $this->checkValidAccount($contact);
                else
                    $valid = $this->checkOldPassword($contact);
                if($valid !== true)
                    return $valid;
                //pairdata has no sense of password_new or anything, so we need to overwrite password here
                //that way when toPairData was called the first time, it gets the old password
                //and this second time, it gets the new one.  That lets toPairData do it's job of removing unmodified data correctly
                //for places where the profile is not changing their passwords
                $contact->password->value = $contact->password_new->value;
            }

            //check unique login, emails
            $duplicateContact = $this->checkUniqueFields($contact);
            if($duplicateContact !== false)
            {
                if(site_config_int_get(CFG_OPT_DUPLICATE_EMAIL))
                {
                     //if contacts allowed to share email, only care if there's an existing
                     //contact with the specified login
                    if($duplicateContact['login'])
                        return $duplicateContact['login'];
                }
                else
                {
                    return implode('<br/>', array_values($duplicateContact));
                }
            }
            AbuseDetection::check();
            $ret = contact_update($contact->toPairData($prevData));
            ActionCapture::record('contact', 'update');
            if($changingPassword){
                ActionCapture::record('contact', 'changePassword');
            }

            $postHookData = array('data'=>$contact, 'returnValue'=>$ret);
            RightNowHooks::callHook('post_contact_update', $postHookData);

            if($ret)
            {
                RnowBase::setFormattedRecordCache($contact, $contact->c_id->value, $this->cacheHandlePrefix, null);

                if(is_null($contact->login->value))
                    return 1;

                //Update session profile information since it may have been updated
                $preHookData = array('data'=>array('source'=>'LOCAL'));
                RightNowHooks::callHook('pre_login', $preHookData);

                $oldProfile = $this->session->getProfile();
                if($changingPassword)
                {
                    $newProfile = array(
                        'login' => $contact->login->value,
                        'sessionid' => $this->session->getSessionData('sessionID'), 
                        'cookie_set' => (int) $this->session->getSessionData('cookiesEnabled'),
                        'login_method' => CP_LOGIN_METHOD_LOCAL,
                    );
                    if ($contact->value->password !== '')
                    {
                        $newProfile['password'] = $contact->password->value;
                    }
                    $newProfile = (object) contact_login($newProfile);
                }
                else
                {
                    $newProfile = (object) contact_login_reverify(array(
                        'login' => $contact->login->value, 
                        'encrypted_passwd' => $contact->password->value,
                        'sessionid' => $this->session->getSessionData('sessionID'),
                    ));
                }
                if($newProfile && $newProfile->login !== null && $newProfile->login !== '' && $newProfile->login !== false)
                    contact_login_update_cookie(array('login' => $newProfile->login, 'expire_time' => time() + $this->session->getProfileCookieLength()));
                if($oldProfile->openLoginUsed)
                    $newProfile->openLoginUsed = $oldProfile->openLoginUsed->value;
                $newProfile = $this->session->createMapping($newProfile);

                $postHookData = array('returnValue'=>$newProfile, 'data'=>array('source'=>'LOCAL'));
                RightNowHooks::callHook('post_login', $postHookData);

                if($newProfile != null)
                    $this->session->createProfileCookie($newProfile);

                return 1;
            }
            else
            {
                return getMessage(SORRY_ERROR_SUBMISSION_LBL);
            }
        }
        else
        {
            return $error;
        }
    }

    /**
     * Gets a list of all contact visible channel fields in the database. If a unique contact identifier is
     * specified, the channel values associated with that contact will also be populated.
     * @param $uniqueIdentifier mixed [optional] Contact ID or username from which to populate values
     * @return Array List of ChannelField instances found in the database
     */
    function getChannelFields($uniqueIdentifier = null)
    {
        $CI = get_instance();
        $channelFields = array();
        $langID = lang_id(LANG_DIR);
        /**if($uniqueIdentifier === null)
        {
            $query = sprintf("select ct.chan_type_id, l.label from channel_types ct
                              left outer join labels l on l.tbl = %d and l.lang_id = %d and l.label_id = ct.chan_type_id
                              where ct.contact_vis = 1 order by ct.seq", TBL_CHANNEL_TYPES, $langID);
        }
        else
        {
			
            $baseQuery = "select ct.chan_type_id, l.label, c.username, c.sm_user_id from channel_types ct
                              left outer join labels l on l.tbl = %d and l.lang_id = %d and l.label_id = ct.chan_type_id
                              left outer join sm_users c on c.c_id = ";
            if(is_int($uniqueIdentifier))
                $baseQuery .= $uniqueIdentifier;
            else
                $baseQuery .= sprintf("(select c_id from contacts where login= '%s')", strtr($uniqueIdentifier, $CI->rnow->getSqlEscapeCharacters()));


            $query = sprintf("$baseQuery and c.chan_type_id = ct.chan_type_id
                              where ct.contact_vis = 1 order by ct.seq", TBL_CHANNEL_TYPES, $langID);
		}
file_put_contents("/tmp/euf_new_log.log",print_r("query : ".$query." \n",true),FILE_APPEND );
	    $si = sql_prepare(($query));
		file_put_contents("/tmp/euf_log.log",print_r("sql_prepare \n",true),FILE_APPEND );
        sql_bind_col($si, 1, BIND_INT, 0);
        sql_bind_col($si, 2, BIND_NTS, 81);
        if($uniqueIdentifier !== null)
        {
            sql_bind_col($si, 3, BIND_NTS, 81);
            sql_bind_col($si, 4, BIND_NTS, 40);
        }
	
        while($row = sql_fetch($si))
        {
		    list($channelTypeID, $channelName, $userName, $sm_user_id) = $row;
            $channelFields[$channelTypeID] = new ChannelField($channelTypeID, $channelName, $userName, $sm_user_id);
			
        }*/
		//$channelFields = array();
		$i = 0;
		$result = RNCPHP\ROQL::query("Select * from ChannelType order by ID")->next();
		while($con =  $result->next())
		{
			if($con['ContactVisibility']==1)
			{
				$channelFields[$i]['chan_type_id'] = new ChannelField($con['ID'], $con['LookupName'], Null, Null);
				$i++;
			}
		}

		//sql_free($si);
        return $channelFields;
    }

    /**
     * Logs the user in given a username, password, session_id, widget_id, and a url
     *
     * @return array an array containing the status of the request and other data to be interpreted by the callee
     * @param $username String The username of the contact
     * @param $password String The password of the contact
     * @param $sessionID String The session Id of the contact
     * @param $widgetID mixed The widget Id of the widget that submitted the request
     * @param $url String The url where to redirect to after login has completed
     */
    function doLogin($username, $password, $sessionID, $widgetID, $url)
    {
        $result = array('w_id' => $widgetID, 'success' => 0);
        $CI = get_instance();
        if((!$CI->session->canSetSessionCookies() || !$CI->session->getSessionData('cookiesEnabled')) && !checkForTemporaryLoginCookie())
        {
            //Temporary cookie does not exist, return an error
            $result['message'] = getMessage(PLEASE_ENABLE_COOKIES_BROWSER_LOG_MSG);
            $result['showLink'] = false;
            return $result;
        }
        if(utf8_char_len($password) > 20) {
            $result['message'] = sprintf(getMessage(PASSWD_ENTERED_EXCEEDS_MAX_CHARS_MSG), 20);
            return $result;
        }

        //We need to check if they are on just ...com, ...com/, /app, or /app/ so what when we
        //redirect we go to the home page
        if (in_array($url, array('', '/', '/app', '/app/'), true)) {
            $url = getHomePage();
        }

        $result['addSession'] = in_array('session', explode('/', $url), true);
        $result['sessionParm'] = sessionParm();
        $result['url'] = $url;

        $profile = $this->getProfileSid($username, $password, $sessionID);
        if(is_string($profile))
        {
            $result['message'] = $profile;
        }
        else if($profile)
        {
            $result['message'] = getMessage(REDIRECTING_ELLIPSIS_MSG);
            $result['success'] = 1;
            $CI->session->createProfileCookie($profile);
        }
        else
        {
            if(getConfig(CP_MAX_LOGINS) > 0 || getConfig(CP_MAX_LOGINS_PER_CONTACT) > 0)
                $result['message'] = getMessage(USRNAME_PASSWD_ENTERED_INCOR_ACCT_MSG);
            else
                $result['message'] = getMessage(USERNAME_PASSWD_ENTERED_INCOR_ACCT_MSG);
        }
        return $result;
    }

     /**
     * Logs out the current session
     *
     * @return (Array) An array that has the new session id and the url to redirect to
     * @param $currentUrl String The URL that the user was on when they clicked the logout link
     * @param $redirectUrl String The URL that the user is going to after they successfully logout
     */
    function doLogout($currentUrl, $redirectUrl = null)
    {
        $CI = get_instance();
        //Delete out the number of searches since it will be reset
        $currentUrl = urlParmDelete($currentUrl, 'sno');
        if(sessionParm() !== '')
            $currentUrl = urlParmAdd($currentUrl, 'session', getSubstringAfter(sessionParm(), "session/"));
        //Contact isn't logged in, just spoof a success
        if(!$CI->session->isLoggedIn())
            return array('url'=>$currentUrl, 'session'=>sessionParm(), 'success'=>1);

        if(getConfig(COMMUNITY_ENABLED, 'RNW'))
        {
            $socialLogoutUrl = getConfig(COMMUNITY_BASE_URL, 'RNW') . '/scripts/signout';
            if($redirectUrl)
            {
                //Check if redirect is fully qualified and on the same domain
                $redirectComponents = parse_url($redirectUrl);
                if($redirectComponents['host'])
                {
                    if(sessionParm() !== '' && $redirectComponents['host'] === getConfig(OE_WEB_SERVER, 'COMMON'))
                        $redirectUrl = urlParmAdd($redirectUrl, 'session', getSubstringAfter(sessionParm(), "session/"));
                    $socialLogoutUrl .= '?redirectUrl=' . urlencode($redirectUrl);
                }
                else
                {
                    if(sessionParm() !== '')
                        $redirectUrl = urlParmAdd($redirectUrl, 'session', getSubstringAfter(sessionParm(), "session/"));
                    $socialLogoutUrl .= '?redirectUrl=' . urlencode(getShortEufBaseUrl('sameAsCurrentPage', $redirectUrl));
                }
            }
        }

        $sessionID = $CI->session->getSessionData('sessionID');
        $preHookData = array();
        RightNowHooks::callHook('pre_logout', $preHookData);
        $logoutResult = contact_logout(array(
            'cookie' => $CI->session->getProfileData('cookie'),
            'sessionid' => $sessionID,
        ));
        //We don't record PTA logouts via ACS, so we need to be sure the library is loaded before we record the logout action
        if(ActionCapture::isInitialized()){
            ActionCapture::record('contact', 'logout');
        }
        $postHookData = array('returnValue'=>$logoutResult);
        RightNowHooks::callHook('post_logout', $postHookData);
        $CI->session->performLogout();
        if(sessionParm() !== '')
            $currentUrl = urlParmAdd($currentUrl, 'session', substr(sessionParm(), 9));
        $result = array('url' => $currentUrl,
                        'session' => sessionParm(),
                        'success' => 1);
        if($socialLogoutUrl)
            $result['socialLogout'] = $socialLogoutUrl;
        return $result;
    }
     /**
     * Creates an instance of the Profile middle layer object given a username
     * password and session id.
     *
     * @return (Object) Instance of the Profile object, or null if login failed
     * @param $username String The username of the contact
     * @param $password String The password of the contact: plaintext, non-encrypted
     * @param $sessionID String The current session id
     */
    function getProfileSid($username, $password, $sessionID)
    {
        $preHookData = array('data' => array('source' => 'LOCAL'));
        $customHookError = RightNowHooks::callHook('pre_login', $preHookData);
        if(is_string($customHookError))
            return $customHookError;

        AbuseDetection::check();
        $pairData = array(
            'login' => $username,
            'sessionid' => $sessionID,
            'cookie_set' => 1,
            'login_method' => CP_LOGIN_METHOD_LOCAL,
        );
        if (is_string($password) && $password !== '') {
            $pairData['password'] = $password;
        }
        $profile = $this->session->createMapping(contact_login($pairData));
        ActionCapture::record('contact', 'login', 'local');
        $postHookData = array('returnValue' => $profile, 'data' => array('source' => 'LOCAL'));
        RightNowHooks::callHook('post_login', $postHookData);
        return $profile;
    }

    /**
     * Attempts to find a contact record in the database by an email address and
     * optionally their first and last name
     * @param $email String The email address to lookup
     * @param $firstName object [optional] The first name of the contact
     * @param $lastName object [optional] The last name of the contact
     * @return Mixed The contact ID if found or false if not found
     */
    function lookupContactByEmail($email, $firstName = null, $lastName = null)
    {
        $contactDetails = $this->lookupContact($email, $firstName, $lastName);
        if($contactDetails === false)
            return false;
        return $contactDetails['c_id'];
    }

    /**
     * Finds a contact ID and org ID of a contact given their email and optionally
     * their first and last names
     * @param $email string The email address to look up
     * @param $firstName string [optional] The first name of the contact
     * @param $lastName string [optional] The last name of the contact
     * @return Mixed False if the contact doesn't exist or an array containing the contactID and orgID found
     */
    function lookupContactAndOrgIdByEmail($email, $firstName=null, $lastName=null)
    {
        $contactDetails = $this->lookupContact($email, $firstName, $lastName);
		die($contactDetails);
        if($contactDetails === false)
            return false;
        return array($contactDetails['c_id'], $contactDetails['org_id']);
    }

    /**
     * Finds a contact ID and email address of a user given their federated account
     * user ID and provider name.
     * @param $providerName string The name of the third-party provider
     *          (Either twitter, facebook, or openid)
     * @param $openLoginAccountID string The user's id on the third-party service
     *          (the openid_identity / openid_claimed_id URL for OpenID)
     * @param $openLoginAccountUsername (optional) string The user's username on the third-party service
     * @return Mixed false if the contact doesn't exist or an array containing the contactID and email found
     */
    function lookupContactByOpenLoginAccount($providerName, $openLoginAccountID, $openLoginAccountUsername = '')
    {
        if(strtolower($providerName) === 'openid')
        {
            $openIDUrl = within_sql(trim($openLoginAccountID));
            $sql = "SELECT contact.c_id, contact.email FROM openid_accounts openid, contacts contact
                    WHERE openid.openid_url = '$openIDUrl' AND contact.c_id = openid.c_id";
        }
        else if($providerID = $this->getOpenLoginChannel($providerName))
        {
            $openLoginAccountID = within_sql($openLoginAccountID);
            if($openLoginAccountUsername)
            {
                $openLoginAccountUsername = within_sql($openLoginAccountUsername);
                $optionalCriteria = "OR channel.username = '$openLoginAccountUsername'";
            }
            $sql = "SELECT contact.c_id, contact.email FROM contact2channel_type channel, contacts contact
                WHERE channel.chan_type_id = $providerID AND contact.c_id = channel.c_id AND (channel.userid = $openLoginAccountID $optionalCriteria)";
        }

        if($sql)
        {
            $sql = sql_prepare($sql);
            $index = 0;
            sql_bind_col($sql, ++$index, BIND_INT, 0);
            sql_bind_col($sql, ++$index, BIND_NTS, 81);
            if($result = sql_fetch($sql))
            {
                sql_free($sql);
                return array(
                    'contactID' => $result[0],
                    'email' => $result[1]
                );
            }
            sql_free($sql);
        }
        return false;
    }

    /**
     * Returns an array containing all open login account info for the specified contact.
     * @param $contactID Int The contact's id
     * @param $providerName (optional) String The specific OpenLogin account to retrieve for the contact
     *              (Either twitter, facebook, or openid)
     * @return Mixed false if no open login accounts exist or an array containing the account data with
     *      'userName' and 'userID' keys associated with each of the contact's Open Login accounts
     */
    function getOpenLoginAccounts($contactID, $providerName = '')
    {
        if(!$contactID || !is_int($contactID) || $contactID < 0)
            return false;

        $contactID = within_sql($contactID);
        $openIDDesignator = 0;
        $openIDSelectStatement = "SELECT $openIDDesignator, openid_url, '$openIDDesignator' FROM openid_accounts WHERE c_id = $contactID";

        if($providerName)
        {
            if(strtolower($providerName) === 'openid')
            {
                $sql = $openIDSelectStatement;
            }
            else if($providerID = $this->getOpenLoginChannel($providerName))
            {
                $optionalCriteria = "AND chan_type_id = $providerID";
            }
        }
        $sql = $sql ?: "SELECT chan_type_id, username, userid FROM contact2channel_type WHERE c_id = $contactID $optionalCriteria UNION $openIDSelectStatement";

        $index = 0;
        $sql = sql_prepare($sql);
        sql_bind_col($sql, ++$index, BIND_INT, 0);
        sql_bind_col($sql, ++$index, BIND_NTS, 255);
        sql_bind_col($sql, ++$index, BIND_NTS, 40);

        $results = array();
        while($row = sql_fetch($sql))
        {
            if(intval($row[0]) === $openIDDesignator)
            {
                //OpenID
                $results['openid'][] = $results[1];
            }
            else
            {
                $results[$this->getReadableOpenLoginChannel($row[0])] = array('userName' => $row[1], 'userID' => $row[2]);
            }
        }
        sql_free($sql);

        return $results ?: false;
    }

    /**
    * Returns the channels.chan_type_id for the equivalent string name of the channel type
    *   for channels used as Open Login providers.
    * @param $providerName String The name of the channel type / open login provider
    * @return Int ID of the channel type or 0 if the specified provider doesn't exist
    */
    function getOpenLoginChannel($providerName)
    {
        switch(strtolower($providerName))
        {
            case 'facebook':
                return CHAN_FACEBOOK;
                break;
            case 'twitter':
                return CHAN_TWITTER;
                break;
        }
        return 0;
    }

    /**
     * Function to return all details about country given its ID
     *
     * @param $id int The database ID of the country
     * @return Array Details about the country including postal code mask, phone mask,
     *               and provinces grouped with that country.
     */
    static function getCountryDetails($id)
    {
        $langID = lang_id(LANG_DIR);
        $si = sql_prepare(sprintf('select c.postal_mask, p.prov_id, lp.label, c.phone_mask
            from labels lc, countries c left outer join provinces p on (p.country_id = c.country_id)
            left outer join labels lp on ((lp.tbl = %d) and (lp.lang_id = %d) and (p.prov_id = lp.label_id))
            where (c.country_id = lc.label_id) and (lc.tbl = %d) and (lc.lang_id = %d) and (c.country_id=%d) order by p.seq, lp.label',
            TBL_PROVINCES, $langID, TBL_COUNTRIES, $langID, $id));

        sql_bind_col($si, 1, BIND_NTS, 21);
        sql_bind_col($si, 2, BIND_INT, 0);
        sql_bind_col($si, 3, BIND_NTS, 41);
        sql_bind_col($si, 4, BIND_NTS, 81);

        $results = array('states'=>array());
        for($i=0; $row=sql_fetch($si); $i++)
        {
            if ($row[1])
            {
                $state = array('id'=>$row[1], 'val'=>$row[2]);
                $results['states'][$i] = $state;
            }
            $results['postal_mask'] = $row[0];
            $results['phone_mask'] = $row[3];
        }
        sql_free($si);
        return $results;
    }

     /**
     * Either creates or updates a answer notification for the currently logged
     * in user.
     *
     * @param $answerID int The answer ID the notification was for
     * @param $status int Current status of the notification
     * @param $contactID int
     * @return array of status codes
     */
    static function answerNotification($answerID, $status, $contactID = null)
    {
        if (!$contactID)
        {
            $CI = get_instance();
            $sessionData = $CI->session->getProfile();
            $contactID = $sessionData->c_id->value;
        }
        if(!$contactID)
            return array('status' => -1);

        $return = array();
        $si = sql_get_int(sprintf('SELECT count(*) FROM ans_notif WHERE (c_id = %d) AND (a_id = %d)', $contactID, $answerID));

        $renewingAnswerNotif = ($si) ? 1 : 0;
        sql_free($si);
        $pairdata['c_id'] = intval($contactID);
        $pairdata['source_upd'] = array('lvl_id1' => SRC1_EU, 'lvl_id2' => SRC2_EU_CONTACT_EDIT);
        $pairdata['ans_notif']['ans_notif_item0'] ['a_id'] = intval($answerID);
        $pairdata['prev'] = array('c_id' => intval($contactID));

        $status = intval($status);
        $operation = '';
        if (!$renewingAnswerNotif && $status !== -4)
        {
            $pairdata['ans_notif']['ans_notif_item0']['interface_id'] = intf_id();
            $pairdata['ans_notif']['ans_notif_item0']['action'] = ACTION_ADD;
            $operation = 'create';
        }
        else
        {
            if($status === 0)
            {
                $pairdata['ans_notif']['ans_notif_item0']['action'] = ACTION_UPD;
                $operation = 'renew';
            }
            elseif($status === -4)
            {
                $pairdata['ans_notif']['ans_notif_item0']['action'] = ACTION_DEL;
                $operation = 'delete';
            }
            else
            {
                $si = sql_prepare(sprintf('SELECT start_time FROM ans_notif WHERE (c_id = %d) AND (a_id = %d)', $contactID, $answerID));
                sql_bind_col($si, 1, BIND_DTTM, 0);
                $row = sql_fetch($si);

                $duration = getConfig(ANS_NOTIF_DURATION, 'RNW_UI');
                if($duration > 0)
                {
                    $sec = 3600 * 24;
                    $daysExpire =  round(($row[0] + ($duration * $sec) - time()) / $sec);
                    $return['status'] = 0;
                    $return['time_left'] = $daysExpire;
                }
                else
                {
                    $return['status'] = 3;
                }
                return $return;
            }
        }

        AbuseDetection::check();
        $ret = contact_update($pairdata);
        
        if($operation){
            ActionCapture::record('answerNotification', $operation, $answerID);
        }

        $ansNotifResult = $ret > 0 ? ($renewingAnswerNotif ? 2 : 1) : -1;

        switch ($ansNotifResult)
        {
            case 1:
                $return['status'] = 1;
                break;
            case 2:
                $duration = getConfig(ANS_NOTIF_DURATION, 'RNW_UI');
                if($duration > 0)
                {
                    $date = sprintf(getMessage(EXPIRES_PCT_S_LBL), date_str(DATEFMT_SHORT, strtotime("+$duration day")));
                    $days = ($duration === 1) ? sprintf(getMessage(PCT_D_DAY_LBL), $duration) : sprintf(getMessage(PCT_D_DAYS_LBL), $duration);
                    $return['time_left'] = sprintf('%s (%s)', $date, $days);
                }
                $return['status'] = 2;
                break;
            default:
                if($status == 0)
                    $return['status'] = -2;
                else
                    $return['status'] = -1;
        }
        return $return;
    }

    /**
      * Emails contact username if the contact isn't disabled and the email isn't invalid.
      *
      * @param $email string The email of the contact
      * @return an array containing a message that specifies the result of the operation
      */
    function emailContactLogin($email)
    {
        $CI = get_instance();
        $CI->session->setSessionData(array('previouslySeenEmail'=>$email));
        $result['message'] = '<b>' . getMessage(EMAIL_CONTAINING_ACCT_INFORMATION_MSG) . '</b>';
        $result['message'] .= '<p></p>' . getMessage(DNT_RECEIVE_EMAIL_UL_THAN_LI_ACCT_MSG);

        if(!is_string($email) || !strlen($email))
            return $result;

        $email = strtolower(within_sql($email));

        $si = sql_prepare("SELECT c_id FROM contacts WHERE (email = '$email') AND (disabled = 0)");
        sql_bind_col($si, 1, BIND_INT, 0);

        $row = sql_fetch($si);
        sql_free($si);

        if(!$row[0])
            return $result;

        $pairdata = array('c_id' => intval($row[0]), 'email' => $email);
        contact_login_recover($pairdata);
        ActionCapture::record('contact', 'loginRecovery');

        return $result;
    }

    /**
    * Checks if contact has a valid account and email address;
    * Sets the password_reset flag and updates the contact accordingly.
    * contact_update() will send the password reset email.
    *
    * @param $login string username
    * @return an array containing a message that specifies the result of the operation
    */
    function emailContactPassword($login)
    {
        $result['message'] = '<b>' . getMessage(EMAIL_RESET_PASSWORD_MSG) . '</b>';
        $result['message'] .= '<p></p>' . getMessage(DONT_RECEIVE_EMAIL_UL_THAN_LI_ACCT_MSG);

        if(!is_string($login) || !strlen($login))
            return $result;

        $login = within_sql($login);

        $si = sql_prepare("SELECT c_id, email, email_invalid FROM contacts
               WHERE (login = '$login') AND (disabled = 0)");
        sql_bind_col($si, 1, BIND_INT, 0);  define('c_id', 0);
        sql_bind_col($si, 2, BIND_NTS, 81); define('email', 1);
        sql_bind_col($si, 3, BIND_INT, 0);  define('invalid', 2);

        $row = sql_fetch($si);
        sql_free($si);

        if(!$row || !$row[email])
            return $result;

        $pairdata = array();
        $pairdata['prev'] = array('c_id' => intval($row[c_id]));

        if($row[invalid])
        {
            $pairdata['email'] = array('invalid' => 0);
            $pairdata['prev']['email'] = array('invalid' => 1);
        }

        $pairdata['c_id'] = intval($row[c_id]);
        $pairdata['source_upd'] = array('lvl_id1'  => SRC1_EU,
            'lvl_id2'  => SRC2_EU_CONTACT_EDIT);
        $pairdata['password_reset'] = 1;

        AbuseDetection::check();
        contact_update($pairdata);
        ActionCapture::record('contact', 'passwordRecovery');

        return $result;
    }

    /**
    * Checks if a contact already exists with the given login or email.
    *
    * @param $idType string The identifier used to check contact uniqueness; either 'login' or 'email'
    * @param $idValue string The actual username or email value entered
    * @param $accountSetup string If the user is creating a username via setup_password:
    *                                                   don't direct them back to acct_assist page in the error message if they chose a pre-existing username.
    * @return mixed error message or false if the idValue is unique.
    */
    function contactAlreadyExists($idType, $idValue, $accountSetup = false)
    {
        $token = $this->input->post('contactToken');
        if(isValidSecurityToken($token, 1) === false)
           return false;
        $idValue = within_sql($idValue);

        $result = false;

        if($idType === 'email')
        {
            $idValue = strtolower($idValue);
            if(sql_get_int("SELECT c_id FROM contacts WHERE email='$idValue' OR email_alt1='$idValue' OR email_alt2='$idValue'") != 0)
            {
                $result['message'] = getMessage(EXISTING_ACCOUNT_EMAIL_ADDRESS_MSG)  . '<br/><br/>';
                if(site_config_int_get(CFG_OPT_DUPLICATE_EMAIL))
                    $result['message'] .= sprintf(getMessage(EMAIL_ADDR_YOU_OBTAIN_CREDS_MSG), '/app/' . getConfig(CP_ACCOUNT_ASSIST_URL) . sessionParm(), getMessage(GET_ACCOUNT_ASSISTANCE_HERE_LBL));
                else
                    $result['message'] .= sprintf(getMessage(EMAIL_ADDR_SEND_USERNAME_RESET_MSG), '/app/' . getConfig(CP_ACCOUNT_ASSIST_URL) . sessionParm(), getMessage(GET_ACCOUNT_ASSISTANCE_HERE_LBL));
            }
        }
        elseif($idType === 'login')
        {
            if(within_sql($this->session->getProfileData('login')) === $idValue)
                return $result;

            if(sql_get_int("SELECT c_id FROM contacts WHERE login='$idValue'") != 0)
            {
                $result['message'] = getMessage(EXISTING_ACCT_USERNAME_PLS_ENTER_MSG) . '<br/><br/>';
                if($accountSetup !== 'true')
                    $result['message'] .= sprintf(getMessage(FORGOTTEN_USERNAME_PASSWD_SEND_BR_S_MSG), '/app/' . getConfig(CP_ACCOUNT_ASSIST_URL) . sessionParm(), getMessage(GET_ACCOUNT_ASSISTANCE_HERE_LBL));
                }
        }

        return $result;
    }

    /**
     * Checks to make sure that a contact recognized by cookie still exists in the database.  If a cookied contact has their record deleted we
     * need to treat them as an unknown.
     *
     * @param $contactID Contact ID to check
     * @return 0 if that id does not exist and the c_id if it does
    */
    function verifyContactID($contactID)
    {
        return sql_get_int('SELECT c_id from contacts where c_id = ' . intval($contactID));
    }

    /**
    * Checks if the contact account is disabled.
    * @param $contact Object The Contact record whose ID is used to check for disablement
    * @return Boolean|String True if the contact is enabled or an error message if the contact is disabled
    */
    function checkValidAccount(Contact $contact)
    {
        return (sql_get_int(sprintf("SELECT disabled FROM contacts WHERE c_id = %d", $contact->c_id->value)))
            ? getMessage(SORRY_THERES_ACCT_CREDENTIALS_INCOR_MSG)
            : true;
    }

    /*********************************************************************
     *
     * CONTACT UTILITY FUNCTIONS
     *
     *********************************************************************/

    /**
     * Base function to call the contact_match API and return the result
     * @private
     */
    private function lookupContact($email, $firstName, $lastName)
    {
		echo "welcome";
        $email = strtolower($email);
        $cacheKey = "existingContactEmail$email";
        $contactMatchPairData = array('email' => $email);
        if($firstName !== null)
        {
            $contactMatchPairData['first'] = $firstName;
            $cacheKey .= $firstName;
        }
        if($lastName !== null)
        {
            $contactMatchPairData['last'] = $lastName;
            $cacheKey .= $lastName;
        }
        $contact = checkCache($cacheKey);
        if($contact !== null)
            return $contact;
		print_r($contactMatchPairData);
        $contact = contact_match($contactMatchPairData);
		//print_r($contact);
		die("hold on");
        if(!$contact['c_id'])
            $contact = false;

        setCache($cacheKey, $contact, true);
        return $contact;
    }

    /**
     * Function to check if email and login fields are unique in the
     * database when operating on contact.
     *
     * @param ContactObject $contact The contact object to validate
     * @return mixed An array of error messages if there was a problem or false if successful
     */
    private function checkUniqueFields($contact)
    {
        list($mainEmail, $altOneEmail, $altTwoEmail) = array_map(function($email){
            return strtolower(within_sql($email));
        },
        array($contact->email->value, $contact->email_alt1->value, $contact->email_alt2->value));

        $login = within_sql($contact->login->value);
        $thisIsANewContact = is_null($contact->c_id->value);
        $emailString = '';
        $query = '';

        if ($mainEmail)
            $emailString = "'$mainEmail'";
        if ($altOneEmail)
            $emailString .= ", '$altOneEmail'";
        if ($altTwoEmail)
            $emailString .= ", '$altTwoEmail'";
        $emailString = ltrim($emailString, ', ');

        if(!is_null($login) && $login !== '')
            $query = "SELECT c_id, 'login', login FROM contacts WHERE login = '$login'";

        if($emailString !== '')
        {
            if ($query !== '')
                $query .= ' UNION ';

            $query .= "SELECT c_id, 'email', email FROM contacts
                WHERE email in ($emailString)
                UNION
                SELECT c_id, 'email_alt1', email_alt1 FROM contacts
                WHERE email_alt1 in ($emailString)
                UNION
                SELECT c_id, 'email_alt2', email_alt2 FROM contacts
                WHERE email_alt2 in ($emailString)";
        }

        $si = sql_prepare($query);
        sql_bind_col($si, 1, BIND_INT, 0);      //c_id
        sql_bind_col($si, 2, BIND_NTS, 81);     //field name of the conflicting credential
        sql_bind_col($si, 3, BIND_NTS, 81);     //login or email address
        $errors = array();
        while($row = sql_fetch($si))
        {
            list($contactID, $identifierWithConflict, $loginOrEmail) = $row;
            $sameContact = (!$thisIsANewContact && (((int) $contact->c_id->value) === $contactID));
            if($identifierWithConflict)
            {
                $accountAssistPage = '/app/' . getConfig(CP_ACCOUNT_ASSIST_URL) . sessionParm();

                if($identifierWithConflict === 'login')
                {
                    if(!$sameContact)
                    {
                        $errors['login'] = getMessage(EXISTING_ACCT_USERNAME_PLS_ENTER_MSG) .
                            '<br/>' . sprintf(getMessage(EMAIL_ADDR_SEND_USERNAME_RESET_MSG), $accountAssistPage, getMessage(GET_ACCOUNT_ASSISTANCE_HERE_LBL));
                    }
                }
                else
                {
                    if(!$sameContact)
                    {
                        $errors[$identifierWithConflict] = (site_config_int_get(CFG_OPT_DUPLICATE_EMAIL))
                            ? (sprintf(getMessage(EXISTING_ACCT_EMAIL_ADDRESS_PCT_S_MSG), $loginOrEmail) . '<br/>' .
                                sprintf(getMessage(EMAIL_ADDR_YOU_OBTAIN_CREDS_MSG), $accountAssistPage, getMessage(GET_ACCOUNT_ASSISTANCE_HERE_LBL)))
                            : (getMessage(EXISTING_ACCOUNT_EMAIL_ADDRESS_MSG) . '<br/>' .
                                sprintf(getMessage(EMAIL_ADDR_SEND_USERNAME_RESET_MSG), $accountAssistPage, getMessage(GET_ACCOUNT_ASSISTANCE_HERE_LBL)));
                    }
                }
            }
        }
        sql_free($si);
        if(count($errors))
            return $errors;
        else
            return false;
    }

   /**
     * Function to check if organization id and password are valid
     *
     * @param $login string The entered organization login
     * @param $pass string The entered organization password
     * @return mixed Error message on failure or true on success
     */
    private static function checkOrg(&$contact, $login, $pass)
    {
        $si = sql_prepare(sprintf("select org_id, password from orgs where (login = '%s')", within_sql($login)));

        sql_bind_col($si, 1, BIND_INT, 0);
        sql_bind_col($si, 2, BIND_BIN, 61);

        $row = sql_fetch($si);
        sql_free($si);

        if(!$row)
            return sprintf(getMessage(ORG_LOGIN_PCT_S_VALID_PLS_ENTERED_MSG), $login);

        elseif(!is_null($pass) && $row[1] != pw_rev_encrypt(htmlspecialchars_decode($pass, ENT_NOQUOTES)))
            return getMessage(PASSWD_ENTERED_DOESNT_MATCH_ORG_MSG);

        $contact->organization_login->value = $row[0];
        return true;
    }

    /**
     * Checks if the contact's existing password was entered correctly
     * @assert only called when contact is changing password, not resetting.
     * @param $contact Object The contact record to check
     * @return mixed True if passwords match or an error message otherwise
     */
    private static function checkOldPassword($contact)
    {
        $oldPassword = sql_get_str(sprintf("select password from contacts where (c_id = %d)", within_sql($contact->c_id->value)), 61);

        if($oldPassword === false && get_instance()->session->getProfileData('openLoginUsed'))
        {
            if($contact->password_new->value === '')
                $contact->password->overwrite = true;
            return true;
        }

        $passwordsMatch = ver_digest_compare_str($oldPassword, $contact->password->value);

        // When a user creates their account (including specifying a password) while asking a question and
        // smart assistant is shown, the following happens:
        // 1) user submits their question along with their new username and password
        // 2) contact is created and logged in
        // 3) smart assistant data is returned to user
        // 4) user decides to continue submission
        // 5) field_model believes that the contact is being updated
        // 6) this function is called
        // When this check is done, $contact->password->value is the encrypted value in the database,
        // so we should really be checking and verifying that the value in $contact->password_new->value
        // "still" matches what is in the database.  We'll check to make sure that $oldPassword matches the value in
        // $contact->password->value, that $contact->password was not submitted in the form, and that
        // $contact->password_new was submitted in the form as a sanity check.
        if($oldPassword === $contact->password->value
            && !(isset($contact->password->fieldSubmittedInForm) && $contact->password->fieldSubmittedInForm === true)
            && (isset($contact->password_new->fieldSubmittedInForm) && $contact->password_new->fieldSubmittedInForm === true))
            $passwordsMatch = ver_digest_compare_str($oldPassword, $contact->password_new->value);

        if(!$passwordsMatch)
            return getMessage(PASSWD_DOESNT_MATCH_PLS_RE_TYPE_MSG);
        return true;
    }

    /**
     * Given an array of hier menu ID's this function will return an array of labels in the
     * format expected for HierMenu field types.
     * @param $hierList array A list of hier menu IDs
     * @return array Labels for the ID's that were passed in
     */
    private function getProfileHierLabels($hierList)
    {
        $si = sql_prepare(sprintf("select label from labels where tbl=%d and lang_id=%d and fld=1 and label_id in (%s)", TBL_HIER_MENUS, lang_id(LANG_DIR), implode(',', $hierList)));
        sql_bind_col($si, 1, BIND_NTS, 41);

        $results = array();
        for($i=0; $row=sql_fetch($si); $i++)
        {
            $hierItem = array('label'=>$row[0], 'level'=>$i+1, 'id'=>$hierList[$i]);
            $results[] = $hierItem;
        }
        sql_free($si);
        return $results;
    }

    /**
    * Returns the string name of the specified channels.chan_type_id for channels used as Open Login providers.
    * @param $channelTypeID Int The channel type id
    * @return Mixed String name of the provider or null if the specified channel type id doesn't exist
    * @private
    */
    private function getReadableOpenLoginChannel($channelTypeID)
    {
        switch($channelTypeID)
        {
            case CHAN_FACEBOOK:
                return 'facebook';
                break;
            case CHAN_TWITTER:
                return 'twitter';
                break;
        }
    }
}
