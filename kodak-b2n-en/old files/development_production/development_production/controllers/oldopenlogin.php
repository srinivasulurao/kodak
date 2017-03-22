<?php

require_once(CPCORE . 'classes/OpenLoginErrors.php');
/**
* Provides facility to communicate with third-party identity providers.
*/
class OpenLogin extends ControllerBase{
    //GENERAL
    const REQUEST_TIMEOUT_LENGTH = 5;
    const TEMP_COOKIE_NAME = 'cp_oauth_credentials';
    const MAX_OPENID_URL_LENGTH = 255; //schema restriction
    const MAX_CONTACT_NAME_LENGTH = 80; //schema restriction
    
    //URLS
    //(1) redirect the user to authorize
    const FB_AUTH_URL = 'https://graph.facebook.com/oauth/authorize';
    //(2) request an access token
    const FB_ACCESS_URL = 'https://graph.facebook.com/oauth/access_token';
    //(3) request contact data
    const FB_CONTACT_API_URL = 'https://graph.facebook.com/me';
    const FB_POST_URL = 'https://graph.facebook.com/me/feed?';
    const FB_PROFILE_PIC_URL = 'http://graph.facebook.com/%s/picture';
    const FB_REVOKE_AUTH_URL = 'https://api.facebook.com/method/auth.revokeAuthorization';
    
    //(1) request a request token (OAuth 1.X)
    const TWITTER_REQUEST_URL = 'https://api.twitter.com/oauth/request_token';
    //(2) redirect the user to authorize
    const TWITTER_AUTH_URL = 'https://twitter.com/oauth/authenticate';
    //(3) request an access token
    const TWITTER_ACCESS_URL = 'https://api.twitter.com/oauth/access_token';
    //(4) request contact data
    const TWITTER_CONTACT_API_URL = 'http://api.twitter.com/1/users/show.json';
    
    const GOOGLE_DISCOVERY_URL = 'https://www.google.com/accounts/o8/id';
    const YAHOO_DISCOVERY_URL = 'https://me.yahoo.com';
    
    private $oAuthCallbackUrl;
    private $openIDCallbackUrl;
    private $referrer;

    function __construct(){
        parent::__construct();
        // Allow full access of public methods for users who aren't logged in if CP_CONTACT_LOGIN_REQUIRED is on.
        parent::_setMethodsExemptFromContactLoginRequired(array(
            'openid',
            'oauth',
            'saml'
        ));

        $this->oAuthCallbackUrl = getShortEufBaseUrl('sameAsCurrentPage', '/ci/openlogin/oauth/callback/%s/%s');
        $this->openIDCallbackUrl = getShortEufBaseUrl('sameAsCurrentPage', '/ci/openlogin/openid/callback/%s');
        $this->referrer = $_SERVER['HTTP_REFERER']; //lerned how to spell.
    }

    /**
    * Public OpenID router method.
    * @param $action String the action being performed (authorize, callback)
    * @param $providerNameOrUrl String Either a url-encoded OpenID provider's Url 
    *           Or the name of a supported OpenID provider (google, yahoo)
    * @param $redirectUrl String [optional] the URL of the page to redirect back to once logged-in
    */
    function openid($action, $providerNameOrUrl, $redirectUrl = ''){
        header('Expires: Mon, 19 May 1997 07:00:00 GMT'); // Date in the past
        $this->openIDCallbackUrl .= sessionParm() . '/';

        if(isLoggedIn()){
            $this->_redirectBackToCPPage($this->referrer);
        }
        if($action === 'authorize'){            
            if(func_num_args() > 3 && $redirectUrl !== 'session' && func_get_arg(3) !== 'session'){
                //something's fishy here. If there's more than the three parameters it's likely because the caller
                //didn't urlencode the redirect URL
                //if there's a session parameter and the session cookie hasn't been set, then we'll deal with
                //that error later on...
                exit(getMessage(REDIRECT_PARAM_URL_ENCODED_CP_URL_MSG));
            }
            $this->_authorizeOpenID($providerNameOrUrl, $this->_buildCallbackUrl($redirectUrl));
        }
        else if($action === 'callback'){
            if(stringContains($providerNameOrUrl, '?openid')){
                //the OpenID provider just appended a querystring onto the url
                //rather than sending GET params... *shakes fist at yahoo*
                $providerNameOrUrl = getSubstringBefore($providerNameOrUrl, '?openid');
            }
            $this->_callbackOpenID($providerNameOrUrl);
        }
        else{
            exit(getMessage(ACTION_PROVIDER_INCORRECT_MSG));
        }
    }

    /**
    * Public OAuth router method.
    * @param $action String Either authorize or callback
    * @param $providerName String the OAuth provider's name. Values are
    *         facebook, twitter.
    */
    function oauth($action, $providerName /*, redirect params (for FB, must be URL segment to get past FB's restrictions)*/){
        header('Expires: Mon, 19 May 1997 07:00:00 GMT'); // Date in the past
        $this->oAuthCallbackUrl .= sessionParm() . '/';
        
        if(isLoggedIn()){
            $this->_redirectBackToCPPage($this->referrer);
        }
        if(inArrayCaseInsensitive(array('authorize', 'callback'), $action) &&
            inArrayCaseInsensitive(array('facebook', 'twitter'), $providerName)){                
            //The only entry-points are the authorize and callback actions for the OOTB supported third-party providers
            if($providerName === 'facebook'){
                //FB doesn't allow arbitrary things like our caller-specified urlencoded page that we want to redirect back to
                //when we're done to be specified as a parameter to our callback URL. But we must maintain the redirect URL
                //when FB calls our callback function, so if we keep the url as a segment, we're allowed to maintain it...
                
                //this function deals with the idiosyncratic process just described
                $redirectParams = $this->_dealWithFacebook(func_get_args());
            }
            else if($action === 'authorize'){
                if(func_num_args() > 3 && $redirectUrl !== 'session' && func_get_arg(3) !== 'session'){
                    //something's fishy here. If there's more than the three parameters it's likely because the caller
                    //didn't urlencode the redirect URL
                    //if there's a session parameter and the session cookie hasn't been set, then we'll deal with
                    //that error later on...
                    exit(getMessage(REDIRECT_PARAM_URL_ENCODED_CP_URL_MSG));
                }
                $redirectParams = $this->_buildCallbackUrl(@func_get_arg(2));
            }
            else if($action === 'callback'){                
                $redirectParams = @func_get_arg(2);
            }

            $functionToCall = "_$action" . ucfirst($providerName);
            $this->$functionToCall($redirectParams);
        }
        else{
            echo getMessage(ACTION_PROVIDER_INCORRECT_MSG);
        }
    }
    
    /**
    * Public SAML router method.
    * Expects an optional 'subject' key value pair in the url followed by an optional key 'redirect'
    * followed by the / delimited path to redirect to.
    * @param $subject String the SAML subject. If specified, must be the first key/value pair. 
    *         Valid values are: 
    *        'login', 'email', 'id', NameOfCustomField (c$name or simply name). Defaults to 'login'
    * @param $redirect String path of CP page to redirect to after successful login. Defaults to home page
    *         must begin with 'app', 'ci', or 'cc' else defaults to home page
    *         example: '/app/ask'
    * Example urls would be http://sitename.com/ci/openlogin/saml/subject/email/redirect/app/answers/list
    *                       http://sitename.com/ci/openlogin/saml/redirect/app/ask
    *                       http://sitename.com/ci/openlogin/saml/redirect/ci/social/ssoRedirect
    *                       http://sitename.com/ci/openlogin/saml
    *
    * SAML Sequence Diagram: http://bit.ly/i5jsxw
    */
    function saml(){
        $token = $this->input->post('SAMLResponse');
        $urlParameters = getUrlParmsString();
        //If the token is not present authentication fails
        if(!$token)
            $this->_redirectToSamlErrorUrl(OpenLoginErrors::SAML_TOKEN_REQUIRED, $urlParameters);
        
        $args = self::_interpretSamlArguments($this->uri->segment_array());
        $result = $this->_loginUsingSamlToken($token, $args['subject'], $args['customFieldName'], $args['redirect']);

        if($result['success']){
            ActionCapture::record('contact', 'login', 'saml');
            $this->_redirectBackToCPPage($args['redirect'] . $urlParameters);
        }
        $this->_redirectToSamlErrorUrl($result['error'], $urlParameters);
    }
    /**
    * Attach an email address to contact data retrieved from an Open Login service
    * (that doesn't provide the user's email address) and continue the login process.
    * All required parameters (email, userData) must be posted to this controller entry-point.
    */
    function provideEmail(){
        $email = $this->input->post('email');
        $encryptedUserData = $this->input->post('userData');
        if($email && $encryptedUserData){
            if(!isValidEmailAddress($email)){
                exit(OpenLoginErrors::INVALID_EMAIL_ERROR);
            }
            $userData = unserialize(ver_ske_decrypt($encryptedUserData));
            if($userData->id && $userData->providerName && $userData->userName && $userData->first_name && $userData->last_name){
                $userData->email = $email;
                $success = $this->_loginUser($userData, $userData->providerName);
                if($success === true)
                    echo 'true';
            }
        }
    }

/**
* Make the initial request for access tokens
*/

    /**
    * Makes the initial redirect to Facebook.
    * @param $redirectUrl String The CP URL that Facebook redirects back to
    * @private
    */
    private function _authorizeFacebook($redirectUrl){
        if(($facebookAppID = getConfig(FACEBOOK_OAUTH_APP_ID)) && getConfig(FACEBOOK_OAUTH_APP_SECRET)){
            //Facebook uses OAuth 2.0, which means super-simple for us -> redirect to FB's login page without first having to get a request token
            $parameters = array(
                'client_id' => $facebookAppID,
                'scope' => 'email' . ($this->_ableToPostToFacebook() ? ',publish_stream' : ''), //request user's email address in addition to common profile info
                'redirect_uri' => sprintf($this->oAuthCallbackUrl, 'facebook', $redirectUrl)
            );
            if($this->agent->supportedMobileBrowser() !== false){
                $parameters['display'] = 'touch'; //good ol' FB is the only provider that doesn't do UA detection and display the appropriate page set on their own...
            }
            $this->_redirectToThirdPartyLogin(self::FB_AUTH_URL, $parameters);
        }
        else{
            exit(getMessage(CFG_VALS_FACEBOOK_OAUTH_APP_ID_MSG));
        }
    }

    /**
    * Performs an initial GET to request temporary credentials from the Twitter API.
    * Redirects to the Twitter authorize URL.
    * @param $redirectUrl String The CP URL that Twitter redirects back to
    * @private
    */
    private function _authorizeTwitter($redirectUrl){
        if(($twitterAppID = getConfig(TWITTER_OAUTH_APP_ID)) && ($twitterAppSecret = getConfig(TWITTER_OAUTH_APP_SECRET))){
            $this->_loadCurl();
            require_once(DOCROOT . '/admin/cloud/include/oauth/oauth.php');
            //get a request token
            $consumerToken = new OAuthConsumer($twitterAppID, $twitterAppSecret);
            $callbackUrl = sprintf($this->oAuthCallbackUrl, 'twitter', $redirectUrl);
            $oAuthRequest = OAuthRequest::from_consumer_and_token(
                $consumerToken,
                null, //don't yet have an access token
                'GET',
                self::TWITTER_REQUEST_URL,
                array('oauth_callback' => $callbackUrl) //array of params to send
            );
            $oAuthRequest->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumerToken, null);
            $tempToken = OAuthUtil::decodeUrlEncodedArray($this->_makeRequest($oAuthRequest->to_url()));

            //stash off oauth token, oauth token secret
            setcookie(self::TEMP_COOKIE_NAME, ver_ske_encrypt_urlsafe("oauth_token={$tempToken['oauth_token']}&oauth_token_secret={$tempToken['oauth_token_secret']}"), 0, '/', '', getConfig(SEC_END_USER_HTTPS, 'COMMON'), true);
        
            $this->_redirectToThirdPartyLogin(self::TWITTER_AUTH_URL . '?oauth_token=' . $tempToken['oauth_token']);
        }
        else{
            exit(getMessage(CFG_VALS_TWITTER_OAUTH_APP_ID_MSG));
        }
    }
    
    /**
    * Performs OpenID document discovery on the OpenID discovery URL and then redirects the user.
    * @param $redirectUrl String The CP URL that the OpenID provider redirects back to
    * @private
    */
    private function _authorizeOpenID($providerUrl, $redirectUrl){
        $providerUrl = trim(urldecode($providerUrl));
        if(isValidUrl($providerUrl)){
            $openIDUrl = $providerUrl;
        }
        else if($providerUrl === 'google'){
            $openIDUrl = self::GOOGLE_DISCOVERY_URL;
        }
        else if($providerUrl === 'yahoo'){
            $openIDUrl = self::YAHOO_DISCOVERY_URL;
        }
        else{
            exit(getMessage(SPECIFIED_OPENID_URL_IS_INVALID_MSG));
        }
        
        if($openIDUrl){
            $this->_loadCurl();
//            require_once(CPCORE . 'classes/LightOpenID.php');
            require_once(APPPATH . 'libraries/LightOpenID.php');
            $openID = new LightOpenID();
            $openID->identity = $openIDUrl;
            $openID->realm = getShortEufBaseUrl();
            $openID->returnUrl = sprintf($this->openIDCallbackUrl, $redirectUrl);
logMessage("returnUrl is ".$openID->returnUrl );
            $openID->required = array(
                'namePerson/first',
                'namePerson/last',
                'contact/email',
                //fallbacks in case the provider doesn't give the first & last name
                'namePerson/friendly', 
                'namePerson'
            );
            try{
                $this->_redirectToThirdPartyLogin($openID->authUrl());
            }
            catch(ErrorException $invalidUrlException){
                $errorCode = $invalidUrlException->getCode();
                if($errorCode === CURLE_OPERATION_TIMEOUTED || $errorCode === CURLE_COULDNT_CONNECT){
                    $error = OpenLoginErrors::OPENID_CONNECT_ERROR;
                }
                else{
                    //the URL that the user provided isn't a valid OpenID discovery point
                    $error = OpenLoginErrors::OPENID_INVALID_PROVIDER_ERROR;
                }
                $this->_returnToCPPageAfterDance($redirectUrl, $error);
            }
        }
    }

/**
* Callbacks after the user has logged-in with their provider and granted access.
*/
    /**
    * Called from Twitter after the authorization step has taken place.
    * @param $redirectUrl String URL encoded string containing the CP page to redirect back to upon success/failure
    * @private
    */
    private function _callbackTwitter($redirectUrl){
        $oAuthVerifier = $_REQUEST['oauth_verifier'];
        $tempTokens = $_COOKIE[self::TEMP_COOKIE_NAME];
        if($oAuthVerifier && $tempTokens){
            //hooray: the user authorized us! now get an access token
            destroyCookie(self::TEMP_COOKIE_NAME);
            $this->_loadCurl();
            require_once(DOCROOT . '/admin/cloud/include/oauth/oauth.php');
        
            $tempTokens = OAuthUtil::decodeUrlEncodedArray(ver_ske_decrypt($tempTokens));
        
            if(is_array($tempTokens)){
                $consumerToken = new OAuthConsumer(getConfig(TWITTER_OAUTH_APP_ID), getConfig(TWITTER_OAUTH_APP_SECRET));
                $accessToken = new OAuthConsumer($tempTokens['oauth_token'], $tempTokens['oauth_token_secret']);
                $callbackUrl = sprintf($this->oAuthCallbackUrl, 'twitter', $redirectUrl);
                $oAuthRequest = OAuthRequest::from_consumer_and_token(
                    $consumerToken,
                    $accessToken,
                    'GET',
                    self::TWITTER_ACCESS_URL,
                    array('oauth_verifier' => $oAuthVerifier) //array of params to send
                );
                $oAuthRequest->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumerToken, null);
                $response = OAuthUtil::decodeUrlEncodedArray($this->_makeRequest($oAuthRequest->to_url()));
                if($response['user_id']){
                    $userInfo = $this->_getTwitterUserInfo($response['user_id']);
                    if($userInfo->id){
                        $userIsLoggedIn = $this->_loginUser($userInfo, 'twitter');
                        if($userIsLoggedIn !== true){
                            if(is_string($userIsLoggedIn)){
                                //need to redirect back... user needs to verify email
                                $emailError = $userIsLoggedIn;
                            }
                            else{
                                $error = $userIsLoggedIn;
                            }
                        }
                    }
                    else if($userInfo->error){
                        $error = OpenLoginErrors::TWITTER_API_ERROR;
                    }
                }
                else{
                    //authentication problem on our part...
                    $error = OpenLoginErrors::AUTHENTICATION_ERROR;                 
                }
            }
        }
        else if(!$oAuthVerifier){
            //twitter oauth error
            $error = OpenLoginErrors::AUTHENTICATION_ERROR;
        }
        else if(!$tempTokens){
            //cookies disabled
            $error = OpenLoginErrors::COOKIES_REQUIRED_ERROR;
        }
        $this->_returnToCPPageAfterDance($redirectUrl, $error, $emailError);
    }

    /**
    * Called from Facebook after the authorization step has taken place.
    * @param $redirectSegments Array Contains segments of
    *   The page to ultimately redirect to upon a successful login (optional)
    *   /onfail/ The original requesting page to go back to if there's a failure or if no redirect page specified
    *   either ?code=... (the request token) or ?error_reason=... (String error sentence)
    * @private
    */
    private function _callbackFacebook($redirectSegments){
        $this->_loadCurl();
        //To get around FB's restrictions the redirect URL must be a URL segment as opposed to simply a urlencoded URL...
        $redirectUrl = implode('/', $redirectSegments);
        $redirectUrl = getSubStringBefore($redirectUrl, '/?code=', $redirectUrl);
        $requestToken = $_REQUEST['code'];
        
        if($requestToken){
            //get the FB access token
            $accessToken = $this->_makeRequest(self::FB_ACCESS_URL, array(
                'code' => $requestToken,
                'client_id' => getConfig(FACEBOOK_OAUTH_APP_ID),
                //The redirect_uri must be identical to the one specified in the request process otherwise the 
                //token that's returned isn't valid.
                'redirect_uri' => sprintf(urlParmDelete($this->oAuthCallbackUrl, 'session'), 'facebook', $redirectUrl),
                'client_secret' => getConfig(FACEBOOK_OAUTH_APP_SECRET)
            ));

            //get user info from Facebook API
            $userInfo = $this->_getFacebookUserInfo($accessToken);
            if($userInfo->id){
                //login the user (implicit contact creation may occur)
                $userIsLoggedIn = $this->_loginUser($userInfo, 'facebook');
                if($userIsLoggedIn !== true){
                    if(is_string($userIsLoggedIn)){
                        //need to redirect back... user needs to verify email
                        $emailError = $userIsLoggedIn;
                    }
                    else{
                        $error = $userIsLoggedIn;
                        if($error === OpenLoginErrors::FACEBOOK_PROXY_EMAIL_ERROR){
                            $this->_revokeFacebookAuthorization($accessToken);
                        }
                    }
                }
            }
            else{
                //authentication problem on our part...
                $error = OpenLoginErrors::AUTHENTICATION_ERROR;
            }
        }
        else if($_REQUEST['error_reason']){
            //we feel rejected by the user :'(
            $error = OpenLoginErrors::USER_REJECTION_ERROR;
        }
        $this->_returnToCPPageAfterDance(urlencode($redirectUrl), $error, $emailError);
    }
    
    /**
    * Called from the OpenID provider after the authorization step has taken place.
    * @param $redirectUrl String URL encoded string containing the CP page to redirect back to upon success/failure
    * @private
    */
    private function _callbackOpenID($redirectUrl){
        if($_REQUEST['openid_mode'] !== 'cancel'){
            $this->_loadCurl();
//            require_once(CPCORE . 'classes/LightOpenID.php');
            require_once(APPPATH . 'libraries/LightOpenID.php');
            if(getConfig(SEC_END_USER_HTTPS, 'COMMON')){
                //LightOpenID uses this server entry to validate the 
                //openid callback, but it's ommitted in our environment...
                $_SERVER['HTTPS'] = true;
            }
            $openID = new LightOpenID();
            try{
                if($openID->validate()){
                    $openIDUrl = $_REQUEST['openid_identity'] ?: $_REQUEST['claimed_id'];
                    if(!($userInfo = $this->_getOpenIDUserInfo($openID))){
                        $error = OpenLoginErrors::OPENID_RESPONSE_INSUFFICIENT_DATA_ERROR;                    
                    }
                    else if(($userIsLoggedIn = $this->_loginOpenIDUser($userInfo, $openIDUrl)) !== true){
                        $error = $userIsLoggedIn;
                    }
                }
                else{
                    $error = OpenLoginErrors::AUTHENTICATION_ERROR;
                }
            }
            catch(ErrorException $unexpectedError){
                $error = OpenLoginErrors::AUTHENTICATION_ERROR;
            }
        }
        else{
            $error = OpenLoginErrors::USER_REJECTION_ERROR;
        }
        $this->_returnToCPPageAfterDance($redirectUrl, $error);
    }
    
/**
* Get contact fields from third-party APIs
*/
    /**
    * Retrieves the user's info from Twitter's API.
    * @param $UserID String The user's Twitter user id
    * @return Object containing the user's profile info
    * @private
    */
    private function _getTwitterUserInfo($userID){
        //This is an object containing the user's id, screen name, name, and other info
        $userInfo = json_decode($this->_makeRequest(self::TWITTER_CONTACT_API_URL . '?user_id=' . $userID));
        if($userInfo && $userInfo->id){
            //normalize properties across the various services
            $userInfo->avatar_url = $userInfo->profile_image_url;
            //Sorry, people w/ spacey names... We're gonna mangle it for you...
            list($userInfo->first_name, $userInfo->last_name) = explode(' ', $userInfo->name, 2);
            $userInfo->last_name = $userInfo->last_name ?: $userInfo->first_name;
            //... Wait. Y'know what? No. We're not sorry.
            //You're either already used to stuff like this or you already changed your legal name.
            $userInfo->userName = $userInfo->screen_name;
            $userInfo->id = $userInfo->id;
        }
        return $userInfo;
    }

    /**
    * Retrieves the user's info from Facebook's API.
    * @param $accessToken String A valid Facebook access token
    * @return Object containing the user's profile info and email.
    * @private
    */
    private function _getFacebookUserInfo($accessToken){
        //This is an object containing the user's id, first_name, last_name, email, and other info
        $userInfo = json_decode($this->_makeRequest(self::FB_CONTACT_API_URL . '?' . $accessToken));
        if($userInfo && $userInfo->id){
            $userInfo->avatar_url = sprintf(self::FB_PROFILE_PIC_URL, $userInfo->id);
        }
        return $userInfo;
    }
    
    /**
    * Retrieves the user's info from provider's OpenID response.
    * @param $openIDObject Object A LightOpenID object.
    * @return Object with first_name, last_name, and email members.
    * @private
    */
    private function _getOpenIDUserInfo($openIDObject){
        $userInfo = $openIDObject->getAttributes();
logmessage('in _getOpenIDUserInfo email is '.$userInfo['contact/email']);		
        if($userInfo['contact/email']){
            if(!$userInfo['namePerson/first'] && !$userInfo['namePerson/last'] &&
                ($userInfo['namePerson'] || $userInfo['namePerson/friendly'])){
                $fullName = $userInfo['namePerson'] ?: $userInfo['namePerson/friendly'];
                list($userInfo['namePerson/first'], $userInfo['namePerson/last']) = explode(' ', $fullName, 2);
            }

            return (object) array(
                'first_name' => ($userInfo['namePerson/first'] ?: ''),
                'last_name' => ($userInfo['namePerson/last'] ?: ''),
                'email' => $userInfo['contact/email']
            );
        }
    }
    
    /**
    * Logs in the currently logged-in Facebook user given a valid access token.
    * @private
    */
    function loginFacebookUser(){
        if(FACEBOOK_REQUEST && $accessToken = $this->input->post('accessToken')){
            $this->_loadCurl();
            $results = $this->_getFacebookUserInfo("access_token=$accessToken");
            if($results->id){
                echo json_encode($this->_loginUser($results, 'facebook'));
            }
            else{
                echo 'Failed to get Facebook user info';
            }
        }
    }
    
    /**
    * Endpoint for Facebook's Registration Tool (http://www.facebook.com/about/login)
    * Maps named values onto a Contact and either creates or updates the Contact.
    * @private
    */
    function facebookRegistration(){
        if($this->input->post('signed_request')){
            $response = $this->_parseSignedFacebookRequest($this->input->post('signed_request'));
            if(($userInfo = $response->registration) && $userInfo->email && $response->user_id){
                $this->load->model('standard/Contact_model');
                
                if(!$userInfo->first_name || !$userInfo->last_name){
                    list($userInfo->first_name, $userInfo->last_name) = explode(' ', $userInfo->name, 2);
                }
                
                if($existingContactID = $this->Contact_model->lookupContactByEmail($userInfo->email, $userInfo->first_name, $userInfo->last_name)){
                    $contact = $this->Contact_model->get($existingContactID);
                    $contactPrevData = $contact->toPairData(null);
                }
                else{
                    $contact = $this->Contact_model->getBlank();
                }
                  
                foreach($userInfo as $key => $value){
                    if(property_exists($contact, $key)){
                        if($key === 'password'){
                            $this->_updateFieldValue($contact->password_new, $value);
                        }
                        else{
                            $this->_updateFieldValue($contact->{$key}, $value);
                        }
                    }
                    else{
                        foreach($contact->custom_fields as $customFieldID => $customField){
                            if($customField->col_name === $key){
                                $this->_updateFieldValue($contact->custom_fields[$customFieldID], $value);
                                break;
                            }
                        }
                    }
                }
                $contact->source->value = SRC2_EU_OPENLOGIN;
                $channelID = $this->Contact_model->getOpenLoginChannel('facebook');
                $contact->channels[$channelID] = new ChannelField($channelID, 'facebook', $userInfo->login ?: $userInfo->email, $response->user_id);
                if($contactPrevData){
                    if($contact->password_new->value !== null){
                        $changingPassword = true;
                        $contact->password->overwrite = true;
                        $contact->password_verify->value = $contact->password_new->value;
                    }
                    $contactID = $this->Contact_model->update($contact, $contactPrevData, $changingPassword);
                }
                else{
                    if($contact->password_new->value !== null){
                        $settingPassword = true;
                        $contact->password_verify->value = $contact->password_new->value;
                    }
                    $contactID = $this->Contact_model->create($contact, $settingPassword);
                }
                if($contactID){
                    $this->_doLogin($contact, 'facebook', $response->user_id);
                }
            }
        }
        if(count($redirect = func_get_args()) > 0){
            $redirect = implode('/', $redirect);
            if(beginsWith($redirect, 'app/') || beginsWith($redirect, 'ci/') || beginsWith($redirect, 'cc/')){
                $redirect = "/$redirect";
            }
        }
        else{
            $redirect = '/app/' . getConfig(CP_HOME_URL);
        }
        header("Location: $redirect");
        exit;
    }
    
/**
* Helpers
*/
    
    /**
    * Updates the value of the specified field if the value passes minimal
    * data type and bounds validation. Fields that are hidden, readonly, or
    * custom fields that have a mask are not touched.
    * @param $fieldObject Object An instance of Field
    * @param $value String The value to update the field with
    * @return Boolean True if the field's value was set to $value otherwise false
    * @assert $value is always required; the only case where $value may be
    *       empty string / null is for the value for a radio-type field.
    * @private
    */
    private function _updateFieldValue($fieldObject, $value){
        if($fieldObject instanceof Field && !$fieldObject->hidden && !$fieldObject->readonly && !$fieldObject->mask){
            $valueToSave = null;
            switch($fieldObject->data_type){
                case EUF_DT_DATE:
                case EUF_DT_DATETIME:
                    $valueToSave = strtotime($value);
                    break;
                case EUF_DT_RADIO:
                    $valueToSave = ($value == 1);
                    break;
                case EUF_DT_MEMO:
                case EUF_DT_VARCHAR:
                case EUF_DT_PASSWORD:
                    $valueToSave = $value;
                    if($fieldObject->field_size && rnt_mb_strlen($value) > $fieldObject->field_size){
                        $valueToSave = truncateText($value, $fieldObject->field_size, false);
                    }
                    break;
                case EUF_DT_INT:
                    if(is_int($value) || (!stringContains($value, '.') && !stringContainsCaseInsensitive($value, 'e'))){
                        $value = (int) $value;
                        if($fieldObject->min_val !== null && $value < $fieldObject->min_val)
                            return;
                        if($fieldObject->max_val !== null && $value > $fieldObject->max_val)
                            return;
                        $valueToSave = $value;
                    }
                    break;
                case EUF_DT_SELECT:
                    $value = (int) $value;
                    if(array_key_exists($value, $fieldObject->menu_items)){
                        $valueToSave = $value;
                    }
                    break;
                default:
                    return;
                    break;
            }
            if($valueToSave !== null){
                $fieldObject->value = $valueToSave;
                return true;
            }
        }
        return false;
    }
    
    /**
    * Makes a request to Facebook's API in order to revoke the app's authorization from the user.
    * @param $accessToken String A valid Facebook access token
    * @private
    */
    private function _revokeFacebookAuthorization($accessToken){
        $this->_makeRequest(self::FB_REVOKE_AUTH_URL . '?' . $accessToken);
    }
    
    /**
    * Parses the specified data from a Facebook register request.
    * @param $signedRequest String Request data
    * @return Mixed Object Response or null if erroneous data was given
    */
    private function _parseSignedFacebookRequest($signedRequest){
        if($facebookAppSecret = getConfig(FACEBOOK_OAUTH_APP_SECRET)){
            list($encodedSignature, $payload) = explode('.', $signedRequest, 2);
        
            //decode the data
            $decode = function($input){
                //this form of base64 uses two different characters and doesn't have padding
                return base64_decode(strtr($input, '-_', '+/'));
            };
            $signature = $decode($encodedSignature);
            $data = @json_decode($decode($payload));
        
            if($data){
                if(strtoupper($data->algorithm) !== 'HMAC-SHA256') {
                    //Unknown algorithm. Expected HMAC-SHA256
                    return;
                }
                //This is where the signature should be verified, but since there's
                //no sha256 hashing function available...
                // if(hash_hmac('sha256', $payload, $facebookAppSecret, true) !== $signature){
                //     return;
                // }
                return $data;
            }
        }
    }

    /**
    * Handle the specialized logic required in order to massage the callback URI that's passed to/from Facebook.
    * @param $parameters Array contains
    *   $provider, 
    *   $action [authorize|callback], 
    *   $redirectOnSuccessPage [in authorize action case] | $redirectAndOriginalPageURLSegments [in callback action case]
    * @return Mixed 
    *   A string containing the redirect URL segment we want Facebook to pass back in the callback (e.g. app/account/overview/onfail/app/utils/login_form)
    *   OR
    *   An array containing the segments that Facebook has passed back thru the callback (e.g. array('app','account','overview','onfail', ...))
    * @private
    */
    private function _dealWithFacebook($parameters){
        $action = $parameters[0];
        $numberOfParams = count($parameters);
        if($action === 'authorize'){
            //CP caller specifies the urlencoded redirect URL as the third parameter for /ci/openlogin/oauth/facebook/authorize/
            //e.g. for account%2Foverview, $parameters = (authorize, facebook, <optional> account%2Foverview, <optional> session, <optional> sessionParamValue)
            if($numberOfParams <= 5){
                //If the third param isn't specified, the orig. requesting page is used for the success/failure page.
                return urldecode($this->_buildCallbackUrl($parameters[2]));
            }
            else if($parameters[2] !== 'session' && $parameters[3] !== 'session'){
                //something's fishy here. If there's more than the three parameters it's likely because the caller
                //didn't urlencode the redirect URL
                exit(getMessage(REDIRECT_PARAM_URL_ENCODED_CP_URL_MSG));
            }
        }
        else if($action === 'callback' && $numberOfParams > 3){
            //FB has called this; redirect URL is now a parameter segment
            return array_slice($parameters, 2);
        }
    }

    /**
    * Processes user data retrieved from a third-party authentication service:
    * Creates a new contact if one with the given email doesn't exist.
    * Updates an existing contact if:
    *   -There's no OpenLogin record for the contact
    *   -There's already an existing OpenLogin record for the contact but the
    *       OpenLogin record is incomplete (previously existed for a cloud monitor contact)
    *   -There's already an existing OpenLogin record for the contact but the
    *       contact.email no longer matches what the provider now gives us
    *   -There's already an existing OpenLogin record for the contact but the
    *       contact.login has been cleared out since the user last logged-in
    * Logs a valid user in.
    * Returns 
    * @param userInfo Object Must contain first_name, last_name, email members.
    *    If it does not contain an email address, the email error code is returned.
    * @param providerName String name of provider
    * @return True if the contact is successfully logged in; 
    *         False if an unknown API error occurred; 
    *         String error code if the user's email is invalid;
    *         String Encrypted serialized user object if user data is provided but an email isn't provided.
    * @private
    */
    private function _loginUser($userInfo, $providerName){
        if($userInfo->email && isValidEmailAddress($userInfo->email)){
            $this->load->model('standard/Contact_model');
            $contact = $contactPrevData = null;
            $this->_conformContactNames($userInfo);
            $existingOpenLoginAccount = $this->Contact_model->lookupContactByOpenLoginAccount($providerName, $userInfo->id, $userInfo->userName);
        
            if($existingOpenLoginAccount){
                $contact = $this->Contact_model->get($existingOpenLoginAccount['contactID']);
                $needToUpdateContact = $this->_updateContactFields($contact, $userInfo, $contactPrevData, $providerName);
                $contactShouldBeLoggedIn = true;
            }
            else{
                $existingContactID = $this->Contact_model->lookupContactByEmail($userInfo->email, $userInfo->first_name, $userInfo->last_name);
                if($existingContactID){
                    $contact = $this->Contact_model->get($existingContactID);
                    //add a new open login record for this contact
                    $this->_updateContactFields($contact, $userInfo, $contactPrevData, $providerName);
                    $channelID = $this->Contact_model->getOpenLoginChannel($providerName);
                    $contact->channels[$channelID] = new ChannelField($channelID, $providerName, $userInfo->userName ?: $userInfo->email, $userInfo->id);
                    $needToUpdateContact = $contactShouldBeLoggedIn = true;
                }
                else{
                    //whew. New contact--simple and easy.
                    $contact = $this->Contact_model->getBlank();
                    $contact->email->value = $contact->login->value = $userInfo->email;
                    $contact->first_name->value = $userInfo->first_name;
                    $contact->last_name->value = $userInfo->last_name;
                    $contact->source->value = SRC2_EU_OPENLOGIN;
                    //add a new open login record for this contact
                    $channelID = $this->Contact_model->getOpenLoginChannel($providerName);
                    $contact->channels[$channelID] = new ChannelField($channelID, $providerName, $userInfo->userName ?: $userInfo->email, $userInfo->id);
                    $newContactID = $this->Contact_model->create($contact);
                    if(is_int($newContactID)){
                        $contactShouldBeLoggedIn = true;
                    }
                    else{
                        return false;
                    }
                }
            }
        }
        else{
            if(!$userInfo->email){
                //first, make sure the user doesn't already exist
                $this->load->model('standard/Contact_model');
                $existingOpenLoginAccount = $this->Contact_model->lookupContactByOpenLoginAccount($providerName, $userInfo->id, $userInfo->userName);
                if($existingOpenLoginAccount && ($contact = $this->Contact_model->get($existingOpenLoginAccount['contactID'])) && $contact->email->value){
                    $needToUpdateContact = $this->_updateContactFields($contact, $userInfo, $contactPrevData, $providerName);
                    $contactShouldBeLoggedIn = true;
                }
                else{
                    //the user didn't allow us their email. we need their email.
                    return ver_ske_encrypt_urlsafe(serialize((object)array(
                            'first_name' => $userInfo->first_name,
                            'last_name' => $userInfo->last_name,
                            'userName' => $userInfo->userName,
                            'id' => $userInfo->id,
                            'providerName' => $providerName)));
                }
            }
            else{
                //invalid email provided
                if($providerName === 'facebook' && stringContains($userInfo->email, 'proxymail')){
                    //Facebook's proxy email feature
                    return OpenLoginErrors::FACEBOOK_PROXY_EMAIL_ERROR;
                }
                return OpenLoginErrors::INVALID_EMAIL_ERROR;
            }
        }

        if(getConfig(COMMUNITY_ENABLED, 'RNW')){
            $contactID = ($newContactID) ?: $contact->c_id->value;
            $this->load->model('standard/Social_model');
            if($newContactID || !($existingCommunityUser = $this->Social_model->getCachedCommunityUser(array('contactID' => $contactID))) || 
                ($existingCommunityUser->error && $existingCommunityUser->error->code === COMMUNITY_ERROR_NO_EXISTING_USER)){
                $userCreated = $this->Social_model->createUser(array(
                    'contactID' => $contactID,
                    'name' => $userInfo->name,
                    'email' => $userInfo->email,
                    'avatarUrl' => $userInfo->avatar_url,
                ));
                if($userCreated->error && $userCreated->error->code === COMMUNITY_ERROR_NON_UNIQUE_NAME){
                    //collision on community user's name.
                    //that means a user with a name something like "John Smith" already exists
                    //so tack a four-digit sequence onto the end
                    //NOTE: there's an impending community project to remove the unique name
                    //requirement, thereby removing the need for this additional step and alleviating
                    //concerns that yet another collision would occur on this call.
                    $this->Social_model->createUser(array(
                        'contactID' => $contactID,
                        'name' => $userInfo->name . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9),
                        'email' => $userInfo->email,
                        'avatarUrl' => $userInfo->avatar_url,
                    ));                    
                }
            }
            else if($existingCommunityUser->user->email !== $userInfo->email){
                $this->Social_model->updateUser($contactID, array(
                    'email' => $userInfo->email
                ));
            }
        }
        
        if($contactShouldBeLoggedIn){
            if($needToUpdateContact === true){
                $contact->source->value = SRC2_EU_OPENLOGIN;
                $this->Contact_model->update($contact, $contactPrevData);
            }
            return $this->_doLogin($contact, $providerName, $userInfo->id);
        }
    }
    
    /**
    * Processes user data retrieved from a OpenID authentication service:
    * Creates a new contact if one with the given email doesn't exist.
    * Updates an existing contact if:
    *   -There's no OpenID record for the contact
    *   -There's already an existing OpenID record for the contact but the
    *       contact.email no longer matches what the provider now gives us
    *   -There's already an existing OpenID record for the contact but the
    *       contact.login has been cleared out since the user last logged-in
    * Logs a valid user in.
    * Returns 
    * @param $userInfo Object Must contain first_name, last_name, email members.
    *    If it does not contain an email address, the email error code is returned.
    * @param $openIDUrl String OpenID url that is unique to the contact
    * @return True if the contact is successfully logged in; 
    *         False if an unknown API error occurred; 
    *         String error code if the user's email is invalid;
    *         String Encrypted serialized user object if user data is provided but an email isn't provided.
    * @private
    */
    private function _logInOpenIDUser($userInfo, $openIDUrl){
        if(!$openIDUrl || !$this->_validateAndConformOpenIDUrl($openIDUrl)){
            return OpenLoginErrors::OPENID_RESPONSE_INVALID_PROVIDER_ERROR;
        }
        
        if($userInfo->email && isValidEmailAddress($userInfo->email)){
            $this->load->model('standard/Contact_model');
            $contact = $contactPrevData = null;
            $this->_conformContactNames($userInfo);
            $existingOpenIDAccount = $this->Contact_model->lookupContactByOpenLoginAccount('openid', $openIDUrl);
            
            if($existingOpenIDAccount){
                $contact = $this->Contact_model->get($existingOpenIDAccount['contactID']);
                $needToUpdateContact = $this->_updateContactFields($contact, $userInfo, $contactPrevData, 'openid');
                $contactShouldBeLoggedIn = true;
            }
            else{
                $existingContactID = $this->Contact_model->lookupContactByEmail($userInfo->email, $userInfo->first_name, $userInfo->last_name);
                if($existingContactID){
                    $contact = $this->Contact_model->get($existingContactID);
                    $this->_updateContactFields($contact, $userInfo, $contactPrevData, 'openid');
                    //add a new openid record for this contact
                    $contact->openID->value = $openIDUrl;
                    $needToUpdateContact = $contactShouldBeLoggedIn = true;
                }
                else{
                    //new contact
                    $contact = $this->Contact_model->getBlank();
                    $contact->openID->value = $openIDUrl;
                    $contact->email->value = $contact->login->value = $userInfo->email;
                    $contact->first_name->value = $userInfo->first_name;
                    $contact->last_name->value = $userInfo->last_name;
                    $contact->source->value = SRC2_EU_OPENLOGIN;
                    $newContactID = $this->Contact_model->create($contact);
                    if(is_int($newContactID)){
                        $contactShouldBeLoggedIn = true;
                    }
                    else{
                        return false;
                    }
                }
            }
        }
        else{
            //no email or invalid email provided
            return OpenLoginErrors::OPENID_RESPONSE_INSUFFICIENT_DATA_ERROR;
        }
        
        if($contactShouldBeLoggedIn){
            if($needToUpdateContact === true){
                $contact->source->value = SRC2_EU_OPENLOGIN;
                $this->Contact_model->update($contact, $contactPrevData);
            }
            $urlDetails = @parse_url($openIDUrl);
            $hostname = $urlDetails ? $urlDetails['host'] : null;
            return $this->_doLogin($contact, 'openID', null, $hostname);
        }
    }
    
    /**
    * Makes sure that the openid_identity URL is valid (admittedly a minimal validity check).
    * Strips whitespace and forward slashes from the URL.
    * @param $url String The URL given as the request's openid_identity / openid_claimed_id
    * @return Boolean whether or not the URL is valid
    * @private
    */
    private function _validateAndConformOpenIDUrl(&$url){
        $url = trim($url, ' /');
        return (isValidUrl($url) && rnt_mb_strlen($url) <= self::MAX_OPENID_URL_LENGTH);
    }
    
    /**
    * Truncates the first_name, last_name members of the passed-in object if their length exceeds the database's max length.
    * @param $userInfo a reference to an object containing first_name, last_name members
    */
    private function _conformContactNames(&$userInfo){
        if(rnt_mb_strlen($userInfo->first_name) > self::MAX_CONTACT_NAME_LENGTH)
            $userInfo->first_name = truncateText($userInfo->first_name, self::MAX_CONTACT_NAME_LENGTH, false);
        if(rnt_mb_strlen($userInfo->last_name) > self::MAX_CONTACT_NAME_LENGTH)
            $userInfo->last_name = truncateText($userInfo->last_name, self::MAX_CONTACT_NAME_LENGTH, false);
    }
    
    /**
    * Updates several contact fields depending on what they're currently set at and what user info is provided.
    *   -Updates email if a different, valid, non-existent email is provided.
    *   -Updates login to the email address if the login is blank.
    *   -Updates the first and last names if they're blank and a first and last name is provided.
    * @param $contact Object reference of a Contact object
    * @param $userInfo Object user data provided by a service
    * @param $contactPrevData Object reference to the contact's prev data state before any modifications occur;
    *           The value of any reference passed-in will be overwritten with the contact's prev data
    * @param $providerName String name of the service (either twitter, facebook, openid)
    * @return Boolean True if the contact object was modified and contact_update() needs to occur False otherwise
    * @private
    */
    private function _updateContactFields($contact, $userInfo, &$contactPrevData, $providerName){
        $updated = false;
        $contactPrevData = $contact->toPairData(null);
        
        if($contact->email->value !== $userInfo->email && isValidEmailAddress($userInfo->email) && !($someoneAlreadyHasThisEmail = $this->Contact_model->lookupContactByEmail($userInfo->email))){
            //update the contact's email to what the third-party provider is now giving us
            //(if a different contact doesn't already have their new email address)
            if($contact->disabled->value === true && $contact->email->value === ''){
                //only enable a disabled contact if it had been disabled due to not having an email address
                $contact->disabled->value = false;
            }
            $updated = true;
            $contact->email->value = $userInfo->email;
        }
        if($contact->login->value === ''){
            //update the contact's login if it was cleared out since the last time they logged-in
            $updated = true;
            $contact->login->value = $contact->email->value;
        }
        if($contact->first_name->value === '' && $userInfo->first_name !== ''){
            $updated = true;
            $contact->first_name->value = $userInfo->first_name;
        }
        if($contact->last_name->value === '' && $userInfo->last_name !== ''){
            $updated = true;
            $contact->last_name->value = $userInfo->last_name;
        }
        
        if($providerName !== 'openid'){
            $channelID = $this->Contact_model->getOpenLoginChannel($providerName);
            if(!$contact->channels[$channelID]->userID){
                //found the pre-existing contact channel record, but it didn't have the user's third-party userID (i.e. was used by the cloud feature prior to this Open Login capability)
                $updated = true;
                $contact->channels[$channelID]->userID = $userInfo->id;
            }
        }
        return $updated;
    }
    
    /**
    * Logs the contact in and sets the openLoginUsed member on the session.
    * @param $contact Object Contact middle layer object that's properly inflated w/ valid contact data
    * @param $provider String The name of the provider
    * @param $userID String {optional} The user's id on the third-party service
    * @param $providerDomain String Domain name of the provider being used. Only provided when logging in via OpenID
    * @return Mixed Bool true if the operation was successful or an Int error code if the contact is disabled.
    * @private
    */
    private function _doLogin($contact, $provider, $userID = null, $providerDomain = null){
        $preHookData = array('data'=>array('source'=>'OPENLOGIN'));
        RightNowHooks::callHook('pre_login', $preHookData);
        $apiProfile = contact_federated_login(array(
            'login' => $contact->login->value,
            'sessionid' => $this->session->getSessionData('sessionID'),
            'login_method' => CP_LOGIN_METHOD_OPENLOGIN,
        ));
        if(!$apiProfile){
            return OpenLoginErrors::CONTACT_LOGIN_ERROR;
        }
        $apiProfile = (object) $apiProfile;
        ActionCapture::record('contact', 'login', 'openlogin');
        ActionCapture::record('openlogin', 'authenticate', substr($providerDomain ?: $provider, 0, ActionCapture::OBJECT_MAX_LENGTH));
        
        if($apiProfile->disabled){
            return OpenLoginErrors::CONTACT_DISABLED_ERROR;
        }
        $apiProfile->openLoginUsed = array('provider' => $provider);
        if($userID){
            $apiProfile->openLoginUsed['userID'] = $userID;
        }
        $profile = $this->session->createMapping($apiProfile);
        $postHookData = array('returnValue'=>$profile, 'data'=>array('source'=>'OPENLOGIN'));
        RightNowHooks::callHook('post_login', $postHookData);
        $this->session->createProfileCookie($profile);
        return true;
    }
    
    /**
    * Builds up a callback redirect url to send along with the user when redirecting to the third-party service.
    * If a session cookie isn't read, then either the user has cookies disabled or the authorize entry-point is
    * attempting to be called from somewhere outside of CP. Either way, that's an error and there's no
    * point in doing anything more; if this error is encountered redirect back with a cookie required message.
    * @param $redirectUrl String optional CP url to redirect to after a successful login; if left unspecified
    *       the referrer page is redirected back to after a successful login
    * @return Url encoded String that consists of app/successPagePath/onfail/app/failurePagePath where successPagePath
    *       is either redirectUrl or the referrer and failurePagePath is always the referrer page.
    * @private
    */
    private function _buildCallbackUrl($redirectUrl = ''){
        if($redirectUrl === 'session'){
            $redirectUrl = '';
        }
        if($redirectUrl){
            $redirectUrl = rtrim(urldecode($redirectUrl), '/');
            $parsedUrl = parse_url($redirectUrl);
            if($parsedUrl['scheme'] || $parseUrl['host']){
                exit(getMessage(REDIRECT_PARAM_URL_ENCODED_CP_URL_MSG));
            }
        }
        //Capture the orig. page to go back to on success or on any error condition.
        //If redirectUrl isn't specified, the orig. requesting page is used for the success/failure page.
        $originalPage = $this->_getRequestingPage();
logMessage(	"originalPage is ".$originalPage);	
        $redirectUrl = $redirectUrl ?: $originalPage;
logMessage(	"redirectUrl is ".$redirectUrl);	
        return urlencode(urlParmDelete(ltrim("$redirectUrl/onfail$originalPage", '/'), 'session'));
//        return urlParmDelete(ltrim("$redirectUrl/onfail$originalPage", '/'), 'session');
    }

    /**
    * Returns the CP relative URL of the requesting page.
    * If the requesting page is outside of CP an error message is output and execution stops.
    * @return String requesting page URL.
    * @private
    */
    private function _getRequestingPage(){
        $cpBaseUrl = getShortEufBaseUrl('sameAsRequest');
        if(stringContains($this->referrer, $cpBaseUrl)){
            //strip out any superflous parameters from the callback we supply to the provider
            return rtrim(urlParmDelete(urlParmDelete(urlParmDelete(getSubStringAfter($this->referrer, $cpBaseUrl), 'redirect'), 'oautherror'), 'emailerror'), '/');
        }
        //FAIL: C'mon at least you could try a little harder next time...
        exit(getMessage(REQUESTING_PAGE_MUST_BE_CP_MSG));
    }
    
    /**
    * Determines if additional permission should be requested from FB to post
    * to the user's wall; based on if the referrer is within the Facebook app,
    * and if the FACEBOOK_ENABLED and FACEBOOK_WALL_POST_ENABLED configs are set
    * @param $referrerUrl (optional) String referrer URL
    * @return Boolean True if able to post to Facebook, False otherwise
    * @private private private!
    */
    private function _ableToPostToFacebook($referrerUrl = ''){
        if($referrerUrl === '')
            $referrerUrl = $this->referrer;
        $referrerUrl = parse_url($referrerUrl);
        return beginsWith($referrerUrl['path'], '/cx/facebook') && getConfig(FACEBOOK_ENABLED, 'RNW') && getConfig(FACEBOOK_WALL_POST_ENABLED, 'RNW');
    }
    
    /**
    * Goes back to the correct CP page after the authentication dance has occurred.
    * @param $encodedRedirectUrls String The encoded redirect URL string that was constructed by _buildCallbackUrl()
    * @param $errorCode Int (optional) The error code of the error that has occurred
    * @param $emailError Int (optional) The email error code of the email error that has occurred
    * @private
    */
    private function _returnToCPPageAfterDance($encodedRedirectUrls, $errorCode = null, $emailError = null){
	logmessage('got to returnToCPPageAfterDance');
        $pageSegmentString = urldecode($encodedRedirectUrls);
		logmessage('encodeRedirectUrls'.$encodedRedirectUrls);
        if(beginsWith($pageSegmentString, 'app/'))
            $pageSegmentString = "/$pageSegmentString";
		logmessage('pageSegmentString'.$pageSegmentString);
		logmessage('before list found error'.$errorCode);
        list($successPage, $originalPage) = explode('/onfail', $pageSegmentString);
        if($errorCode || $emailError){
		logmessage('found error'.$errorCode);
            $url = urlParmAdd($originalPage, 'redirect', urlencode(urlencode($successPage)));
            $url = ($errorCode) ? urlParmAdd($url, 'oautherror', $errorCode) : urlParmAdd($url, 'emailerror', $emailError);
        }
        else{
            $url = $successPage;
        }
        $url .= sessionParm();
        $this->_redirectBackToCPPage($url);
    }

    /**
    * Redirects to the specified URL. If none is provided, redirects to CP_LOGIN_URL.
    * @param $pageUrl (optional) String The URL
    * @private
    */
    private function _redirectBackToCPPage($pageUrl = null){
        $pageUrl = ($pageUrl) ?: '/app/' . getConfig(CP_LOGIN_URL);
        header('Location: ' . ((!beginsWith($pageUrl, '/') && !beginsWith($pageUrl, 'http')) ? "/$pageUrl" : $pageUrl));
        exit;
    }
    
    /**
     * Redirect to the SAML error URL, passing along the specified error. If the
     * config setting isn't set, the user will be taken to the generic CP error page
     * @param $errorID int The error code to pass along
     * @param $urlParametersToPersist string Additional URL parameters to add to the URL
     * @private
     */
    private function _redirectToSamlErrorUrl($errorID, $urlParametersToPersist = '')
    {
        ActionCapture::record('saml', 'error', $errorID);
        if(($errorUrl = getConfig(SAML_ERROR_URL, 'RNW')))
        {
            $errorUrl = str_ireplace('%session%', urlencode(getSubstringAfter(sessionParm(), "session/")), str_ireplace('%error_code%', $errorID, $errorUrl));
            if($urlParametersToPersist !== '' && endsWith($errorUrl, '/'))
            {
                $urlParametersToPersist = getSubstringAfter($urlParametersToPersist, '/');
            }
            $errorUrl .= $urlParametersToPersist;
            header("Location: $errorUrl");
            exit;
        }
        $this->_redirectBackToCPPage("/app/error/error_id/" . OpenLoginErrors::mapOpenLoginErrorsToPageErrors($errorID) . $urlParametersToPersist);
    }

    /**
    * Redirects to the specified URL that is assumed to be a third-party login site.
    * @param $url String The URL
    * @param $parameters (optional) Array query string parameters
    * @private
    */
    private function _redirectToThirdPartyLogin($url, $parameters = null){
        header("Location: $url" . (($parameters) ? '?' . http_build_query($parameters, null, '&') : ''));
        exit;
    }
    
    /**
    * Makes a request to the specified URL via CURL.
    * @param $url String URL
    * @param $postFields (optional) Array post fields to send
    * @return String the results of the request
    * @private
    */
    private function _makeRequest($url, $postFields = null){
        $curl = curl_init();
        $options = array(
          CURLOPT_URL            => $url,
          CURLOPT_TIMEOUT        => self::REQUEST_TIMEOUT_LENGTH,
          CURLOPT_CONNECTTIMEOUT => self::REQUEST_TIMEOUT_LENGTH,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_SSL_VERIFYHOST => false,
          CURLOPT_SSL_VERIFYPEER => false //required for SSL
        );
        if(is_array($postFields)){
            $options[CURLOPT_POSTFIELDS] = $postFields;
        }
        curl_setopt_array($curl, $options);
        $results = curl_exec($curl);
        if ($results === false) {
            echo 'error_code: ' . curl_errno($curl);
            echo 'message: ' . curl_error($curl);
            curl_close($curl);
            exit;
        }
        curl_close($curl);
        return $results; 
    }
    
    /**
    * Loads the curl library if it hasn't already been loaded
    * @private
    */
    private function _loadCurl(){
        if(!extension_loaded('curl') && !@dl('curl_php5.so')){
            exit;
        }
    }

   /**
     * Validates the SAML assertion and logs the contact in
     *
     * @return (Array) An associative array of the form
     *          array(success => boolean, error => OpenLoginErrors::ERROR_CODE)
     * @param $token the base64 encoded SAML Assertion
     * @param $subject the subject of the SAML Assertion
     * @param $customFieldName the name of the custom field. Only used if $subject === 'CustomField'
     * @param $redirectTarget The URL the login request asked to go to.  We need it here so that we can send it to the 
     *        controller method which ensures cookies are enabled.
     */
    private function _loginUsingSamlToken($token, $subject, $customFieldName, $redirectTarget)
    {
        $token = base64_decode($token);
        if(!$token)
            return array('success' => false, 'error' => OpenLoginErrors::SAML_TOKEN_FORMAT_INVALID);

        $result = sso_contact_token_validate(array(
            'token' => $token,
            'type' => SSO_TOKEN_TYPE_SAML20_RESPONSE_POST,
            'subject' => $subject,
            'cf_name' => $customFieldName,
            'url' => getShortEufBaseUrl('sameAsCurrentPage', $_SERVER['REQUEST_URI'])
        ));
        if($result['result'] !== SSO_ERR_OK)
            return array('success' => false, 'error' => OpenLoginErrors::SSO_CONTACT_TOKEN_VALIDATE_FAILED);

        $login = $result['contact_login'];

        $preHookData = array('data'=>array('source'=>'SAML'));
        RightNowHooks::callHook('pre_login', $preHookData);
        $apiProfile = contact_federated_login(array(
            'login' => $login, 
            'sessionid' => $this->session->getSessionData('sessionID'),
            'login_method' => CP_LOGIN_METHOD_SAML,
        ));
        if(!$apiProfile)
            return array('success' => false, 'error' => OpenLoginErrors::FEDERATED_LOGIN_FAILED);

        $apiProfile = (object) $apiProfile;

        if($apiProfile->disabled)
            return array('success' => false, 'error' => OpenLoginErrors::CONTACT_DISABLED_ERROR);

        $profile = $this->session->createMapping($apiProfile);
        $postHookData = array('returnValue'=>$profile, 'data'=> array('source'=>'SAML'));
        RightNowHooks::callHook('post_login', $postHookData);
        $this->session->createProfileCookie($profile);

        if(!$this->session->getProfileData('cookiesEnabled'))
            $this->_redirectBackToCPPage("/ci/openlogin/ensureCookiesEnabled/" . urlencode(urlencode($redirectTarget)) . '/' . sessionParm());
            
        return array('success' => true); 
    }

    /**
     * Ensures that the user has cookies enabled. If cookies are enabled (i.e. the user is logged in) then
     * we take them on their way. Otherwise we redirect them to an error page
     * @private
     */
    public function ensureCookiesEnabled($redirectTarget) 
    {
        if(!$this->session->getSessionData('cookiesEnabled'))
            $this->_redirectToSamlErrorUrl(OpenLoginErrors::COOKIES_REQUIRED_ERROR);

        $this->_redirectBackToCPPage(urldecode(urldecode($redirectTarget)));
    }

   /**
     * maps a string (presumably from a url to a constant)
     *
     * @return (Integer) A constant to be passed to the API 
     * @param $subject (String) the lay man's term 
     */
    private static function _mapSamlSubjectStringToConstant($subject)
    {
        $subject = strtolower($subject);
        if($subject === "contact.login")
            return SSO_TOKEN_SUBJECT_LOGIN;
        if($subject === "contact.emails.address")
            return SSO_TOKEN_SUBJECT_EMAIL;
        if($subject === "contact.id")
            return SSO_TOKEN_SUBJECT_ID;
        if(beginsWith($subject, "contact.customfields."))
            return SSO_TOKEN_SUBJECT_CF;
        return SSO_TOKEN_SUBJECT_LOGIN;
    }
    
    /**
     * analyzes the URL passed in and calculates the values of the SAML subject, 
     * redirect, and CustomFieldName parameters. Explicitly set to public to 
     * enable testing, but prefixed with _ so it can't be called from a browser
     * @return (Array) an associative array containing the three values
     * @private
     */
    public static function _interpretSamlArguments($segments)
    {
        //first key must be subject or redirect
        $samlSubject = $segments[3] === 'subject' ? $segments[4] : null;
        $results = array('subject' => self::_mapSamlSubjectStringToConstant($samlSubject),
                         'customFieldName' => null,
                         'redirect' => getSubstringAfter(implode('/', $segments), '/redirect/', getShortEufBaseUrl())
                        );
        
        if(!(beginsWith($results['redirect'], 'app/') || beginsWith($results['redirect'], 'ci/') || beginsWith($results['redirect'], 'cc/'))){
            $results['redirect'] = getShortEufBaseUrl();
        }
        if($results['subject'] === SSO_TOKEN_SUBJECT_CF){
            //Custom field values look like contact.customfields.<name>, so explode it, limiting to 3 items, to get the name
            $customFieldComponents = explode('.', $samlSubject, 3);
            $customFieldName = strtolower($customFieldComponents[2]);
            $results['customFieldName'] = beginsWith($customFieldName, 'c$') ? $customFieldName : 'c$' . $customFieldName;
        }
        return $results;
    }
}
