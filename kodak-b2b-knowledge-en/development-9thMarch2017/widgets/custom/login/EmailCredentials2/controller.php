<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class EmailCredentials2 extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->attrs['credential_type'] = new Attribute(getMessage(TYPE_OF_ACCOUNT_CREDENTIAL_FORM_LBL), 'OPTION', getMessage(SPECIFIES_TYPE_ACCT_CREDENTIALS_MSG), 'password');
        $this->attrs['credential_type']->options = array('password', 'username');
        $this->attrs['label_heading'] = new Attribute(getMessage(LABEL_HEADING_LBL), 'STRING', getMessage(LABEL_DISPLAY_WIDGET_HEADING_LBL), getMessage(RESET_YOUR_PASSWORD_CMD));
        $this->attrs['label_description'] = new Attribute(getMessage(DESCRIPTION_LBL), 'STRING', getMessage(DESCRIPTION_DISPLAY_HEADING_LABEL_MSG), getMessage(EMAIL_LINK_PAGE_CREATE_PASSWORD_MSG));
        $this->attrs['label_button'] = new Attribute(getMessage(LABEL_BUTTON_LBL), 'STRING', getMessage(LABEL_TO_DISPLAY_ON_BUTTON_LBL), getMessage(RESET_MY_PASSWORD_CMD));
        $this->attrs['label_input'] = new Attribute(getMessage(LABEL_INPUT_LBL), 'STRING', getMessage(LABEL_DISPLAY_INPUT_CONTROL_LBL), getMessage(USERNAME_LBL));
        $this->attrs['initial_focus'] = new Attribute(getMessage(INITIAL_FOCUS_LBL), 'BOOL', getMessage(SET_TRUE_FIELD_FOCUSED_PAGE_LOADED_MSG), false);
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = getMessage(WIDGET_DISP_UI_ELEMENTS_RETRIEVE_MSG);
    }

    function getData()
    {
        if($this->data['attrs']['credential_type'] === 'password')
        {
            //honor config: don't output password form
            if(!getConfig(EU_CUST_PASSWD_ENABLED)) return false;

            $this->data['js']['field_required'] = getMessage(A_USERNAME_IS_REQUIRED_MSG);
        }
        else
        {
            $this->data['js']['field_required'] = getMessage(AN_EMAIL_ADDRESS_IS_REQUIRED_MSG);
            if($this->CI->session->getSessionData('previouslySeenEmail'))
                $this->data['email'] = $this->CI->session->getSessionData('previouslySeenEmail');
        }
    }
}
