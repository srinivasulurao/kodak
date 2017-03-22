<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ServiceRequestDetailsData extends Widget
{
	
	function __construct()
    {
        parent::__construct();

        //Create attributes here
		$this->attrs['i_id'] = new Attribute('', 'Int', '', null);
    }

    function generateWidgetInformation()
    {
        //Create information to display in the tag gallery here
        $this->info['notes'] =  getMessage(WIDGET_SERVES_TEMPL_MODEL_OWN_CUST_MSG);
        $this->parms['url_parameter'] = new UrlParam(getMessage(URL_PARAMETER_LBL), 'parm', true, getMessage(DEF_URL_PARAMETERS_AFFECT_WIDGET_LBL), 'parm/3');
    }

    function getData()
    {
		$this->CI->load->model('custom/custom_incident_model');
		$srd = $this->CI->custom_incident_model->getServiceRequestDetails($this->data['attrs']['i_id']);
    }
}



