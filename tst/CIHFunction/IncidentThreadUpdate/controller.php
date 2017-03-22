<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class IncidentThreadUpdate extends Widget
{
    function __construct()
    {
        parent::__construct();

		$this->attrs['label_input'] = new Attribute(getMessage(INPUT_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_INPUT_CONTROL_LBL), '{default_label}');
		$this->attrs['label_error'] = new Attribute(getMessage(ERROR_LABEL_LBL), 'STRING', sprintf(getMessage(PCT_S_IDENTIFY_FLD_NAME_ERR_MSGS_MSG), 'label_input'), '');
		$this->attrs['label_required'] = new Attribute(getMessage(REQUIRED_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_REQUIREMENT_MESSAGE_LBL), getMessage(PCT_S_IS_REQUIRED_MSG));
		$this->attrs['name'] = new Attribute(getMessage(NAME_LBL), 'STRING', getMessage(COMBINATION_TB_FLD_INPUT_ATTRIB_MSG), '');
		$this->attrs['name']->required = true;
		$this->attrs['required'] = new Attribute(getMessage(REQUIRED_LBL), 'BOOL', getMessage(SET_TRUE_FLD_CONT_VAL_CF_SET_REQD_MSG), false);
		$this->attrs['hint'] = new Attribute(getMessage(HINT_LBL), 'STRING', getMessage(HINT_TXT_DISP_FLD_CF_VAL_OVRRIDE_MSG), '');
		$this->attrs['always_show_hint'] = new Attribute(getMessage(ALWAYS_SHOW_HINT_LBL), 'BOOL', getMessage(SET_TRUE_FLD_HINT_HINT_DISPLAYED_MSG), false);
		$this->attrs['initial_focus'] = new Attribute(getMessage(INITIAL_FOCUS_LBL), 'BOOL', getMessage(SET_TRUE_FIELD_FOCUSED_PAGE_LOADED_MSG), false);
		$this->attrs['validate_on_blur'] = new Attribute(getMessage(VALIDATE_ON_BLUR_LBL), 'BOOL', getMessage(VALIDATES_INPUT_FLD_DATA_REQS_FOCUS_LBL), false);
		$this->attrs['always_show_mask'] = new Attribute(getMessage(ALWAYS_SHOW_MASK_LBL), 'BOOL', getMessage(SET_TRUE_FLD_MASK_VAL_EXPECTED_MSG), false);
		$this->attrs['default_value'] = new Attribute(getMessage(DEFAULT_VALUE_LBL), 'STRING', sprintf(getMessage(DF_VL_PPULATE_FLD_CF_VAL_OVRRIDE_MSG), 'now'), '');
		$this->attrs['allow_external_login_updates'] = new Attribute(getMessage(ALLOW_EXTERNAL_LOGIN_UPDATES_LBL), 'BOOL', getMessage(ALLWS_USERS_AUTHENTICATE_CP_EXT_MSG), false);
		$this->attrs['hide_hint'] = new Attribute(getMessage(HIDE_HINT_CMD), 'BOOL', getMessage(SPECIFIES_HINTS_HIDDEN_DISPLAYED_MSG), false);
    }

    function generateWidgetInformation()
    {
        //Create information to display in the tag gallery here
        $this->info['notes'] =  getMessage(WIDGET_SERVES_TEMPL_MODEL_OWN_CUST_MSG);
        $this->parms['url_parameter'] = new UrlParam(getMessage(URL_PARAMETER_LBL), 'parm', true, getMessage(DEF_URL_PARAMETERS_AFFECT_WIDGET_LBL), 'parm/3');
    }

    function getData()
    {
        //Perform php logic here
    }
}



