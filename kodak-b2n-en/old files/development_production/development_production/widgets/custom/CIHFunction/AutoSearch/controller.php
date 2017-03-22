<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class AutoSearch extends Widget
{
    function __construct()
    {
        parent::__construct();

        //Create attributes here
        $this->attrs['attribute'] = new Attribute(getMessage(ATTRIBUTE_NAME_LBL), 'String', getMessage(ATTRIBUTE_DESCRIPTION_LBL), getMessage(ATTRIBUTE_DEFAULT_VALUE_LBL));
    }

    function generateWidgetInformation()
    {
        //Create information to display in the tag gallery here
        $this->info['notes'] =  getMessage(WIDGET_SERVES_TEMPL_MODEL_OWN_CUST_MSG);
        $this->parms['url_parameter'] = new UrlParam(getMessage(URL_PARAMETER_LBL), 'parm', true, getMessage(DEF_URL_PARAMETERS_AFFECT_WIDGET_LBL), 'parm/3');
    }

    function getData()
    {
		$enhanced_customer_type = $this->CI->custom_org_model->getDirectPartnerTypeEnhanced($this->CI->session->getProfileData('org_id'));
		
		$this->data['enhanced_customer_type'] = $enhanced_customer_type;
		$this->data['js']['enhanced_customer_type'] = $enhanced_customer_type;
    }
}



