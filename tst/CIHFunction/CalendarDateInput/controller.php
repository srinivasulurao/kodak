<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CalendarDateInput extends Widget
{
    function __construct()
    {
        parent::__construct();

        //Create attributes here
		$this->attrs['search_filter_id'] = new Attribute('Filter ID', 'INT', 'Search filter id.', null);
		$this->attrs['search_report_id'] = new Attribute('Report ID', 'INT', 'Search report id.', null);
		$this->attrs['search_operator_id'] = new Attribute('Operator ID', 'INT', 'Search operator id.', null);	
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



