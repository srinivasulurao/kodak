<?php /* Originating Release: February 2012 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ServiceRequestActivity extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->attrs['initial_focus'] = new Attribute(getMessage(INITIAL_FOCUS_LBL), 'BOOL', getMessage(SET_TRUE_FIELD_FOCUSED_PAGE_LOADED_MSG), false);
        $this->attrs['report_page_url'] = new Attribute(getMessage(REPORT_PAGE_LBL), 'STRING', getMessage(PG_DSP_BTN_CLICKED_LEAVE_BLANK_MSG), '/app/answers/list');
        $this->attrs['icon_path'] = new Attribute(getMessage(ICON_PATH_LBL), 'STRING', getMessage(LOCATION_IMAGE_FILE_SEARCH_ICON_LBL), 'images/icons/search.png');
        $this->attrs['alt_text'] = new Attribute(getMessage(ALTERNATIVE_TEXT_MSG), 'STRING', getMessage(TEXT_DISPLAYED_IMAGE_AVAILABLE_MSG), getMessage(SEARCH_CMD));
        $this->attrs['label_hint'] = new Attribute(getMessage(HINT_LABEL_LBL), 'STRING', getMessage(LABEL_DISP_SRCH_FLDS_INIT_HINT_VAL_LBL), getMessage(SEARCH_CMD));
		$this->attrs['report_id'] = new Attribute(getMessage(REPORT_ID_LBL), 'INT', getMessage(ID_RPT_DISP_DATA_SEARCH_RESULTS_MSG), null);
		$this->attrs['report_id']->min = 1;
		$this->attrs['report_id']->optlistId = OPTL_CURR_INTF_PUBLIC_REPORTS;
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = getMessage(WIDGET_PROV_SRCH_FLD_INTENDED_MSG);
    }

    function getData()
    {
		$this->CI->load->model('custom/custom_org_model');
		$customer_type = $this->CI->custom_org_model->getDirectPartnerType($this->CI->session->getProfileData('org_id'));
		$enhanced_customer_type = $this->CI->custom_org_model->getDirectPartnerTypeEnhanced($this->CI->session->getProfileData('org_id'));
		
		$this->data['customer_type'] = $customer_type;
		$this->data['js']['customer_type'] = $customer_type;
		
		$this->data['enhanced_customer_type'] = $enhanced_customer_type;
		$this->data['js']['enhanced_customer_type'] = $enhanced_customer_type;
		
		$this->data['js']['autosearch'] = true;

                $this->CI->load->model('custom/custom_contact_model');
                $internal = $this->CI->custom_contact_model->checkInternalContact($this->CI->session->getProfileData('c_id'));
                $this->data['js']['internal_user'] = $internal;
    }
}

