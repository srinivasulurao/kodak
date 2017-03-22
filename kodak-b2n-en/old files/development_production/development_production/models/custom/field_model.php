<?php /* Originating Release: February 2012 */

class Field_model extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Sends an email to the specified user
     *
     * @param $sendTo string Email address to send to
     * @param $name string name of sender
     * @param $from string Email address of sender
     * @param $answerID int Answer ID
     * @return mixed A string if an error was encountered or true if successful
     */
    function emailToFriend($sendTo, $name, $from, $answerID)
    {
        //check token validity
        $token = $this->input->post('emailAnswerToken');
        if(isValidSecurityToken($token, 146) === false)
           return true;

        $CI = get_instance();
        //get subject and validate name
        $subject = sprintf(getMessage(FORWARD_ANS_LBL), sql_get_str("select summary from answers where (a_id = $answerID)", 241));
        $subject = print_text2str($subject, OPT_VAR_EXPAND|OPT_ESCAPE_SCRIPT|OPT_ESCAPE_HTML);

        $name = trim($name);
        if($name === '')
        {
            return getMessage(THERE_WAS_ERROR_EMAIL_WAS_NOT_SENT_LBL);
        }
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $emailAddressError = function($email) {
            return (!isValidEmailAddress($email) || stringContains($email, ';') || stringContains($email, ',') || stringContains($email, ' ')); 
        };
        if ($emailAddressError($sendTo))
            return getMessage(RECIPIENT_EMAIL_ADDRESS_INCORRECT_LBL);
        if ($emailAddressError($from))
            return getMessage(SENDER_EMAIL_ADDRESS_INCORRECT_LBL);

        $fromHeader = "\"$name\" <$from>";
        //Email header must be less than 80 characters so truncate the name portion of the string
        if(strlen($fromHeader) > 80)
        {
            $name = substr($name, 0, (80 - (strlen("'' <$from>"))));
            $fromHeader = "\"$name\" <$from>";
        }
        AbuseDetection::check();
        $emailSent = ans_eu_forward(
            array('a_id' => intval($answerID),
                  'subject' => $subject,
                  'comment' => null,
                  'suppress_output' => 1,
                  'from_addr' => $fromHeader,
                  'sendto' => "\t\t\t\t" . $sendTo,
                  'replyto_addr' => $from,
                  'name' => $name));
        ActionCapture::record('answer', 'email', intval($answerID));
        $CI->session->setSessionData(array('previouslySeenEmail'=>$from));
        return ($emailSent !== 0) ? true : getMessage(SORRY_WERENT_ABLE_SEND_EMAIL_PLS_MSG);
    }

    /**
    * Handles password reset form submission for a non logged-in, pre-existing contact
    * by reading in an encrypted string containing the contact's c_id.
    *
    * @param $data array All submitted form data
    * @param $pwReset string Encrypted contact_id/expire_time string
    * used to validate user in order to reset their password
    */
    function resetPassword(&$data, $pwReset)
    {
        //Test the security synchronization token to ensure that
        //it matches with the source side
        $formToken = $this->input->post('f_tok');
        if(isValidSecurityToken($formToken, 0) === false)
            return(array('status' => '-1'));

        if($pwReset)
        {
            $pwReset = RightNowApi::ver_ske_decrypt($pwReset);
            //in the form 'c_id/exp_time'
            //and optionally /login/ (if creating a username for existing contact)
            //and optionally /email (if creating a new contact with the [shared] email address)
            $contactCredential = explode('/', $pwReset);
            if(is_array($contactCredential))
            {
                $CI = get_instance();
                $CI->load->model('standard/Contact_model');

                $contactID = intval($contactCredential[0]);
                $contactCreate = false;

                if($contactID > 0)
                {
                    //contact update
                    $contactsObject = $CI->Contact_model->get(intval($contactCredential[0]));
                    $prevData = $contactsObject->toPairData(null);
                }
                else
                {
                    //contact create
                    $contactCreate = true;
                    $contactsObject = $CI->Contact_model->getBlank();
                    $contactsObject->email->value = $contactCredential[3];
                }
                foreach($data as $formField)
                {
                    $fieldObject = $contactsObject->{$formField->name};

                    if($formField->required)
                        $fieldObject->required = true;
                    $fieldObject->value = $formField->value;
                    if(!$contactCreate)
                        $fieldObject->previousValue = $formField->prev;
                }

                $status = array();
                $contactsObject->password->overwrite = true;
                if(isPta() && !getConfig(PTA_IGNORE_CONTACT_PASSWORD))
                {
                    $status['contact']['update'] = getMessage(SORRY_ALLOWED_UPDATE_PROFILE_MSG);
                }
                else if($contactCreate)
                {
                    $status['contact']['create'] = $CI->Contact_model->create($contactsObject);
                    //If contact create was successful and user has a login, try and log in user
                    if(is_int($status['contact']['create']) && !is_null($contactsObject->login->value))
                    {
                        $profile = $CI->Contact_model->getProfileSid($contactsObject->login->value, $contactsObject->password_new->value, $CI->session->getSessionData('sessionID'));
                        if($profile !== null && !is_string($profile))
                        {
                            $this->session->createProfileCookie($profile);
                        }
                    }
                }
                else
                {
                    $status['contact']['update'] = $CI->Contact_model->update($contactsObject, $prevData, true);
                }
                $result = $this->getStatus($status);
                if((!$CI->session->canSetSessionCookies() || !$CI->session->getSessionData('cookiesEnabled')) && !checkForTemporaryLoginCookie())
                    $result['redirectOverride'] = '/app/error/error_id/7';
                return $result;
            }
        }
        return false;
    }

    /**
     * Generic function to handle form submission. Either incident create/update or
     * contact update/create
     *
     * @param $data array All submitted form data
     * @param $incidentID int Current Incident ID
     * @param $smartAsst boolean True/false denoting if smart assistant should be run
     * @return int status of operation
     */
    function sendForm(&$data, &$incidentID, &$smartAsst)
    {
        $CI = get_instance();
        $CI->load->model('custom/Contact_model');
        $CI->load->model('standard/Incident_model');

        $profile = $CI->session->getProfile();
        if($profile !== null)
        {
            $contactsObject = $CI->Contact_model->get($profile->c_id->value);
            $contactPrevData = $contactsObject->toPairData(null);
        }
        else
        {
            $contactsObject = $CI->Contact_model->getBlank();
        }
        if($incidentID)
        {
            $incidentsObject = $CI->Incident_model->get($incidentID);
            $incidentPrevData = $incidentsObject->toPairData();
        }
        else
        {
            $incidentsObject = $CI->Incident_model->getBlank();
        }

        $contacts = $incidents = 0;
        $password = '';
        $passwordNewPresent = $emailFieldSeen = false;

        $status = array();
		
        foreach($data as $field)
        {
            //The $fieldObject variable is assigned to the field object stored within
            //the contact or incident middle layer object. Objects in PHP are always pass by
            //reference, so modifying $fieldObject is automatically modifying the
            //same field in the contact or incident middle layer object.

            if($field->profile)
            {
                $fieldObject = ${$field->table.'Object'}->profile->{$field->name};
            }
            else if($field->channelID)
            {
                $fieldObject = ${$field->table.'Object'}->channels[$field->channelID];
            }
            else if($field->custom != "true")
            {
                $fieldObject = ${$field->table.'Object'}->{$field->name};
            }
            else
            {
                $fieldObject = ${$field->table.'Object'}->custom_fields[$field->customID];
            }
            
            //Set flag so that we know we should validate this fields value
            $fieldObject->fieldSubmittedInForm = true;

            if($field->required)
                $fieldObject->required = true;

            $fieldObject->previousValue = $field->prev;
            $fieldObject->value = $field->value;

            // Increments the 'contacts', 'incidents', etc. variable from above.
            ${$field->table} += 1;

            //We need to store the password so we can log them in later
            //we also need to know if it's on the page so that we can flag contact_model->update
            if($field->name === 'password_new')
            {
                $password = $field->value;
                $passwordNewPresent = true;
            }
            
            if($field->name === 'email' && $field->table === 'contacts' && !$field->custom)
                $emailFieldSeen = true;
        }
		

        //Not sure what happened here, but the form had no contact or incident fields. Bail out which
        //will cause the widget to display a generic error message
        if($contacts === 0 && $incidents === 0){
            return "";
        }
        // Test the security synchronization token to ensure that
        // it matches with the source side.  Return the error
        // condition indicating a token error.
        $formToken = $this->input->post('f_tok');
        if(isValidSecurityToken($formToken, 0) === false)
           return array('status' => '-1', 'sessionParm' => sessionParm());
        
        if ($contacts > 0)
        {
            if($profile === null)
            {
                if($emailFieldSeen && $contactsObject->email->value && $incidents)
                {
                    //In the process of creating an incident:
                    //Since there is an email address field on the page, we need to check if it exists. In this check
                    //we'll also pass the first/last name values to help get the correct contact if the duplicate email 
                    //config is on. If the contact exists, we'll ignore all other contact fields (even if they have changes) 
                    //and just create a new incident
                    $firstNameValue = $contactsObject->first_name->value ?: null;
                    $lastNameValue = $contactsObject->last_name->value ?: null;
                    list($existingContactID, $existingOrgId) = $CI->Contact_model->lookupContactAndOrgIdByEmail($contactsObject->email->value, $firstNameValue, $lastNameValue);
                    if($existingContactID)
                    {
                        $contactsObject->c_id->value = $existingContactID;
                    }
                    else
                    {
                        $status['contact']['create'] = $CI->Contact_model->create($contactsObject);
                    }
                }
                else
                {
				die("profile empty 3");
                    $status['contact']['create'] = $CI->Contact_model->create($contactsObject, $passwordNewPresent);
                }
            }
            else
            {
			die("trying update");
                $status['contact']['update'] = $CI->Contact_model->update($contactsObject, $contactPrevData, $passwordNewPresent);
            }
        }
        $newContactWasCreatedAndLoggedIn = false;
        $newFormToken = '';
        //If contact create was successful and user has a login, try and log in user
        if(is_int($status['contact']['create']) && !is_null($contactsObject->login->value))
        {
            $profile = $CI->Contact_model->getProfileSid($contactsObject->login->value, $password, $CI->session->getSessionData('sessionID'));
            if($profile !== null && !is_string($profile))
            {
                $this->session->createProfileCookie($profile);
                $newContactWasCreatedAndLoggedIn = true;
            }
        }

        if($incidents > 0)
        {
            if ($newContactWasCreatedAndLoggedIn)
            {
                $contactID = $contactsObject->c_id->value;
            }
            else if ($profile === null || is_string($profile))
            {
                // Verify non logged-in contact isn't disabled
                if (is_string($disabledMessage = $CI->Contact_model->checkValidAccount($contactsObject)))
                {
                    $status['contact']['create'] = $disabledMessage;
                }
                else
                {
                    $contactID = $contactsObject->c_id->value;
                }
            }
            else
            {
                $contactID = $profile->c_id->value;
            }
            
            if($contactID)
            {
                $incidentsObject->c_id->value = $contactID;
                if($existingOrgId)
                    $incidentsObject->org_id->value = $existingOrgId;
                if($incidentID)
                {
                    $incidentsObject->i_id->value = $incidentID;
                    $status['incident']['update'] = $CI->Incident_model->update($incidentsObject, $incidentPrevData);
                }
                else
                {
                    //convert smart assistance variable to a boolean
                    if(!is_bool($smartAsst))
                        $smartAsst = ($smartAsst === 'true') ? true : false;
                    $status['incident']['create'] = $CI->Incident_model->create($incidentsObject, $smartAsst);
                    //Generate a new token if SA results were returned and a new contact has been created. We need a new token 
                    //because it's generated based off the contact ID of the person logged in. When the first token is generated 
                    //there isn't a user logged in, but during the first submit when SA results are returned, the user does become
                    //logged in (if a new account was created). Therefore, during the second submit, the original token is no longer valid.
                    if(is_array($status['incident']['create']) && !$status['incident']['create']['refno'] && $newContactWasCreatedAndLoggedIn){
                        $newFormToken = cpCreateTokenExp(0);
                    }
                }
            }
            else if(!is_string($status['contact']['create']))
            {
                return array('message' => getMessage(ENCOUNTERED_QUESTION_PROCESSED_MSG));
            }
        }
        $result = $this->getStatus($status);
        
        //If the current token being used will no longer be valid on the next submit, pass the new token back
        //to the client so they can resubmit
        if($newFormToken !== '')
            $result['newFormToken'] = $newFormToken;
        
        //Check if the user created a new account (with a login), but has cookies disabled. In this case we need to take them to an error page.
        if($newContactWasCreatedAndLoggedIn && (!$CI->session->canSetSessionCookies() || !$CI->session->getSessionData('cookiesEnabled')) && !checkForTemporaryLoginCookie())
            $result['redirectOverride'] = '/app/error/error_id/7';
            
        return $result;
    }

/****************************************************************************
*
* FIELD MODEL UTILITY FUNCTIONS
*
****************************************************************************/

    /**
     * Builds up the correct status return type
     *
     * @param $stat array Current return from API call
     * @return array Status of the form submission
     */
    private static function getStatus($stat)
    {
        $return['sessionParm'] = sessionParm();
        foreach($stat as $table => $action)
        {
            $return["table"]=$table;
            foreach($action as $key => $status)
            {
                $return["action"]=$key;
                if($status <= 0)
                {
                    $return["message"] = $status;
                    return $return;
                }
                else if(is_array($status))
                {
                    if($status['refno'])
                    {
                        $return['refno'] = $status['refno'];
                        $return['status'] = 1;
                    }
                    else
                    {
                        $return["sa"] = $status;
                    }
                    return $return;
                }
            }
        }
        if(isset($stat["incident"]["create"]))
            $return["i_id"] = $stat["incident"]["create"];

        $return["status"]=1;
        return $return;
    }
}
