<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class OpenLogin extends Widget{
    function __construct(){
        parent::__construct();
        $this->attrs['label_process_explanation'] = new Attribute(getMessage(PROCESS_EXPLANATION_LABEL_LBL), 'STRING', getMessage(TEXT_EXPLAINS_LOGIN_PROCESS_USER_LBL), getMessage(CLICK_BTN_FACEBOOK_LOG_FACEBOOK_MSG));
        $this->attrs['label_process_header'] = new Attribute(getMessage(PROCESS_HEADER_LABEL_LBL), 'STRING', getMessage(TEXT_DISPLAYS_PROCESS_EXPLANATION_LBL), getMessage(WHAT_WILL_HAPPEN_LBL));
        $this->attrs['label_service_button'] = new Attribute(getMessage(SERVICE_BUTTON_LABEL_LBL), 'STRING', getMessage(TEXT_DISPLAYS_LOGIN_PROVIDER_LBL), 'Facebook' /*not to be translated*/);
        $this->attrs['label_login_button'] = new Attribute(getMessage(LOGIN_BUTTON_LABEL_LBL), 'STRING', getMessage(TEXT_DISPLAYS_LOGIN_BUTTON_LBL), getMessage(LOG_INTO_FACEBOOK_LBL));
        $this->attrs['controller_endpoint'] = new Attribute(getMessage(CONTROLLER_ENDPOINT_LBL), 'STRING', getMessage(SPECIFIES_REDIRECT_CONFIRMED_MSG), '/ci/openlogin/oauth/authorize/facebook');
        $this->attrs['redirect_url'] = new Attribute(getMessage(REDIRECT_PAGE_LBL), 'STRING', getMessage(PG_REDIRECT_SUCCFUL_LOGIN_SET_PG_MSG), '');
        $this->attrs['openid'] = new Attribute(getMessage(OPENID_LBL), 'BOOL', getMessage(SPECIFIES_WIDGET_OBTAINING_OPENID_MSG), false);
        $this->attrs['display_in_dialog'] = new Attribute(getMessage(DISPLAY_IN_DIALOG_CMD), 'BOOL', getMessage(DISP_LOGIN_BTN_PROCESS_EXPLANATION_LBL), false);
        $this->attrs['openid_placeholder'] = new Attribute(getMessage(OPENID_PLACEHOLDER_LBL), 'STRING', getMessage(TXT_PRE_FILLED_OPENID_PROVIDER_URL_MSG), '');
        $this->attrs['preset_openid_url'] = new Attribute(getMessage(PRESET_OPENID_URL_LBL), 'STRING', sprintf(getMessage(PREDEFINED_OPENID_URL_OPENID_MSG), 'openid_placeholder'), '');
        $this->attrs['label_email_prompt'] = new Attribute(getMessage(EMAIL_PROMPT_LABEL_LBL), 'STRING', getMessage(EXPLANATORY_TXT_DISP_DIALOG_LBL), getMessage(EMAIL_ADDRESS_ORDER_CONTINUE_MSG));
        $this->attrs['label_email_prompt_title'] = new Attribute(getMessage(EMAIL_PROMPT_TITLE_LABEL_LBL), 'STRING', getMessage(TXT_DISP_TITLE_DIALOG_CONTAINING_LBL), getMessage(THANKS_YOURE_VERIFIED_LBL));
        $this->attrs['label_email_address'] = new Attribute(getMessage(EMAIL_ADDRESS_LABEL_LBL), 'STRING', getMessage(LABEL_EMAIL_ADDR_INPUT_FLD_DIALOG_LBL), getMessage(EMAIL_ADDRESS_UC_LBL));
        $this->attrs['label_email_prompt_submit_button'] = new Attribute(getMessage(EMAIL_PROMPT_SUBMIT_BUTTON_LBL), 'STRING', sprintf(getMessage(LABEL_SUBMIT_BTN_DIALOG_CONTAINING_LBL), 'label_email_prompt'), getMessage(OK_LBL));
        $this->attrs['label_email_prompt_cancel_button'] = new Attribute(getMessage(EMAIL_PROMPT_CANCEL_BUTTON_LBL), 'STRING', sprintf(getMessage(LABEL_CANCEL_BTN_DIALOG_CONTAINING_LBL), 'label_email_prompt'), getMessage(CANCEL_LBL));
    }

    function generateWidgetInformation(){
        $this->info['notes'] = getMessage(WIDGET_PROV_USERS_ABILITY_LOG_MSG);
        $this->parms['redirect'] = new UrlParam(getMessage(REDIRECT_LBL), 'redirect', false, getMessage(ENCODED_LOC_URL_REDIRECT_SUCCESSFUL_LBL), 'redirect/home');
        $this->parms['oautherror'] = new UrlParam(getMessage(OAUTH_ERROR_LBL), 'oautherror', false, getMessage(ERR_OCCURS_AUTHENTICATION_PROCESS_MSG), 'oautherror/4');
        $this->parms['emailerror'] = new UrlParam(getMessage(EMAIL_ERROR_LBL), 'emailerror', false, getMessage(TWITTERS_API_DOESNT_PROV_USERS_MSG), 'emailerror/%4532sxw33%2F...');
    }

    function getData(){
        if((strtolower($this->data['attrs']['label_service_button']) === 'facebook' || stringContains(strtolower($this->data['attrs']['controller_endpoint']), '/ci/openlogin/oauth/authorize/facebook')) &&
            (!getConfig(FACEBOOK_OAUTH_APP_ID) || !getConfig(FACEBOOK_OAUTH_APP_SECRET))) {
            $this->reportError(sprintf(ASTRgetMessage("Both of the %s and %s configuration settings must be specified."), '<m4-ignore>FACEBOOK_OAUTH_APP_ID</m4-ignore>', '<m4-ignore>FACEBOOK_OAUTH_APP_SECRET</m4-ignore>'));
            return false;
        }
        if((strtolower($this->data['attrs']['label_service_button']) === 'twitter' || stringContains(strtolower($this->data['attrs']['controller_endpoint']), '/ci/openlogin/oauth/authorize/twitter')) &&
            (!getConfig(TWITTER_OAUTH_APP_ID) || !getConfig(TWITTER_OAUTH_APP_SECRET))) {
            $this->reportError(sprintf(ASTRgetMessage("Both of the %s and %s configuration settings must be specified."), '<m4-ignore>TWITTER_OAUTH_APP_ID</m4-ignore>', '<m4-ignore>TWITTER_OAUTH_APP_SECRET</m4-ignore>'));
            return false;
        }

        if($this->data['attrs']['openid'] && $this->data['attrs']['preset_openid_url']){
            $keyToReplace = '[username]';
            if(!stringContains($this->data['attrs']['preset_openid_url'], $keyToReplace)){
                echo $this->reportError(sprintf(getMessage(PCT_S_DOESNT_CONTAIN_PCT_S_VALUE_LBL), 'preset_openid_url', $keyToReplace));
                return false;
            }
        }
        if($redirectParam = getUrlParm('redirect')){
            //check if the redirect location is a fully-qualified or relative
            $redirectLocation = urldecode(urldecode($redirectParam));
            $parsedURL = parse_url($redirectLocation);

            if($parsedURL['scheme'] || (beginsWith($parsedURL['path'], '/ci/') || beginsWith($parsedURL['path'], '/cc/'))){
                $this->data['attrs']['redirect_url'] = $redirectLocation;
            }
            else{
                $this->data['attrs']['redirect_url'] = beginsWith($redirectLocation, '/app/') ? $redirectLocation : "/app/$redirectLocation";
            }
        }
        if(!endsWith($this->data['attrs']['controller_endpoint'], '/'))
            $this->data['attrs']['controller_endpoint'] .= '/';
        
        if($errorCode = getUrlParm('oautherror')){
            require_once(CPCORE . 'classes/OpenLoginErrors.php');
            $this->data['js']['error'] = OpenLoginErrors::getErrorMessage($errorCode);
        }
        $this->data['attrs']['redirect_url'] = urlParmDelete($this->data['attrs']['redirect_url'], 'oautherror');
    }
}
