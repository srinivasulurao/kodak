<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ServiceRequestActivityAdvancedSearch extends Widget
{
    function __construct()
    {
        parent::__construct();

		$this->attrs['report_id'] = new Attribute(getMessage(REPORT_ID_LC_LBL), 'INT', getMessage(ID_RPT_DISP_DATA_SEARCH_RESULTS_MSG), CP_NOV09_ANSWERS_DEFAULT);
		$this->attrs['report_id']->min = 1;
    }

    function generateWidgetInformation()
    {
        //Create information to display in the tag gallery here
        $this->info['notes'] =  getMessage(WIDGET_SERVES_TEMPL_MODEL_OWN_CUST_MSG);
        $this->parms['url_parameter'] = new UrlParam(getMessage(URL_PARAMETER_LBL), 'parm', true, getMessage(DEF_URL_PARAMETERS_AFFECT_WIDGET_LBL), 'parm/3');
    }

    function getData()
    {
		$this->data['thefilters'] = $this->CI->Report_model->getRuntimeFilters($this->data['attrs']['report_id']);
		
		$this->data['js']['rnSearchType'] = 'custom';
		$this->data['js']['searchName'] = 'custom';
		
		$this->CI->load->model('custom/custom_report_model');
		
		$country_list = $this->CI->custom_report_model->getReportDataByID(100818);
		$this->data['country_list'] = $country_list;

                $this->CI->load->model('custom/custom_contact_model');
                $internal = $this->CI->custom_contact_model->checkInternalContact($this->CI->session->getProfileData('c_id'));
                $this->data['js']['internal_user'] = $internal;
    }
}



