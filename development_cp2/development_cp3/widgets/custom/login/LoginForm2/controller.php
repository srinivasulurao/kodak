<?php /* Originating Release: February 2014 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');

class LoginForm2 extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->attrs['label_username'] = new Attribute(getMessage(USERNAME_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_USERNAME_BOX_LBL), getMessage(USERNAME_LBL));
        $this->attrs['label_password'] = new Attribute(getMessage(LABEL_PASSWORD_LBL), 'STRING', getMessage(LABEL_DISPLAY_PASSWORD_BOX_LBL), getMessage(PASSWORD_LBL));
        $this->attrs['label_login_button'] = new Attribute(getMessage(LOGIN_BUTTON_LABEL_LBL), 'STRING', getMessage(LABEL_TO_DISPLAY_ON_SUBMIT_BUTTON_LBL), getMessage(LOG_IN_LBL));
        $this->attrs['redirect_url'] = new Attribute(getMessage(REDIRECT_PAGE_LBL), 'STRING', getMessage(PG_REDIRECT_SUCCFUL_LOGIN_SET_PG_MSG), '');
        $this->attrs['append_to_url'] = new Attribute(getMessage(PARAMETER_TO_APPEND_TO_URL_LBL), 'STRING', getMessage(PARAM_APPEND_URL_REDIRECTED_LBL), '');
        $this->attrs['disable_password'] = new Attribute(getMessage(DISABLE_PASSWORD_INPUT_CMD), 'BOOL', getMessage(ST_TRUE_VAL_HONORED_EU_CUST_PASSWD_MSG), false);
        $this->attrs['initial_focus'] = new Attribute(getMessage(INITIAL_FOCUS_LBL), 'BOOL', getMessage(SET_TRUE_FIELD_FOCUSED_PAGE_LOADED_MSG), false);
        $this->attrs['disable_password_autocomplete'] = new Attribute(getMessage(DISABLE_PASSWORD_AUTO_COMPLETE_CMD), 'BOOL', getMessage(PREVENT_BROWSER_AUTOCOMPLETION_MSG), false);
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = getMessage(WIDGET_DISPLAYS_LOGIN_FORM_USER_MSG);
        $this->parms['redirect'] = new UrlParam(getMessage(REDIRECT_LBL), 'redirect', false, getMessage(ENCODED_LOC_URL_REDIRECT_SUCCESSFUL_LBL), 'redirect/home');
        $this->parms['username'] = new UrlParam(getMessage(USERNAME_LBL), 'username', false, getMessage(POPULATES_USERNAME_FLD_VALUE_URL_MSG), 'username/JohnDoe');
    }

    function getData()
    {
        if(isLoggedIn())
            return false;
        redirectIfPageNeedsToBeSecure();
        if(getUrlParm('redirect'))
        {
            //We need to check if the redirect location is a fully qualified URL, or just a relative one
            $redirectLocation = urldecode(urldecode(getUrlParm('redirect')));
            $parsedURL = parse_url($redirectLocation);

            if($parsedURL['scheme'] || (beginsWith($parsedURL['path'], '/ci/') || beginsWith($parsedURL['path'], '/cc/')))
            {
                $this->data['js']['redirectOverride'] = $redirectLocation;
            }
            else
            {
                $this->data['js']['redirectOverride'] = beginsWith($redirectLocation, '/app/') ? $redirectLocation : "/app/$redirectLocation";
            }
        }

        //honor: (1) attribute's T value (2) config
        $this->data['attrs']['disable_password'] = ($this->data['attrs']['disable_password']) ? $this->data['attrs']['disable_password'] : !getConfig(EU_CUST_PASSWD_ENABLED);
        $this->data['username'] = getUrlParm('username');
        if($this->CI->agent->browser() === 'Internet Explorer')
            $this->data['isIE'] = true;
    }
}
