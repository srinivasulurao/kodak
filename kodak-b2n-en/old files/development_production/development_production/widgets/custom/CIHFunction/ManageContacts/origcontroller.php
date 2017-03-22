<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ManageContacts extends Widget
{
    function __construct()
    {
        parent::__construct();

        //Create attributes here
		$this->attrs['panel_name'] = new Attribute('Panel Name', 'String', 'Parent panel name', '');
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



