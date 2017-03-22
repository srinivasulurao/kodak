<?php /* Originating Release: February 2012 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');

class SimpleProductSearch extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->attrs['initial_focus'] = new Attribute(getMessage(INITIAL_FOCUS_LBL), 'BOOL', getMessage(SET_TRUE_FIELD_FOCUSED_PAGE_LOADED_MSG), false);
        $this->attrs['report_page_url'] = new Attribute(getMessage(REPORT_PAGE_LBL), 'STRING', getMessage(PG_DSP_BTN_CLICKED_LEAVE_BLANK_MSG), '/app/answers/list');
        $this->attrs['icon_path'] = new Attribute(getMessage(ICON_PATH_LBL), 'STRING', getMessage(LOCATION_IMAGE_FILE_SEARCH_ICON_LBL), 'images/icons/search.png');
        $this->attrs['alt_text'] = new Attribute(getMessage(ALTERNATIVE_TEXT_MSG), 'STRING', getMessage(TEXT_DISPLAYED_IMAGE_AVAILABLE_MSG), getMessage(SEARCH_CMD));
        $this->attrs['label_hint'] = new Attribute(getMessage(HINT_LABEL_LBL), 'STRING', getMessage(LABEL_DISP_SRCH_FLDS_INIT_HINT_VAL_LBL), getMessage(SEARCH_CMD));
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = getMessage(WIDGET_PROV_SRCH_FLD_INTENDED_MSG);
    }

    function getData()
    {
        if($this->data['attrs']['report_page_url'] === '')
            $this->data['attrs']['report_page_url'] = '/app/' . $this->CI->page;
        if($this->CI->agent->browser() === 'Internet Explorer')
            $this->data['isIE'] = true;


        $this->CI->load->model('custom/custom_contact_model');
        $role_id = $this->CI->custom_contact_model->getLoggedInUsersRole();
        $this->data['js']['roleID'] = $role_id;

        $this->CI->load->model('custom/roles_model');
        $this->data['js']['role_functions'] = $this->CI->roles_model->getRole2FunctionsByRoleID($role_id);
    }
}

