<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class IBaseUpdate extends Widget
{
    function __construct()
    {
        parent::__construct();

        //Create attributes here
		$this->attrs['widget_index'] = new Attribute('Widget Index', 'Int', 'Index of the widget on the page when multiple instances are used on the same page.', 0);
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
 		$sesslang = get_instance()->session->getSessionData("lang");
		switch ($sesslang) {
        case "en":
			$ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
        case "fr":
			$ccih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 
			break;
        case "es":
			$ccih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 
			break;
        case "pt":
			$ccih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 
			break;
        default:
			$ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
		}						
		$this->data['js']['loadingmessage'] = $ccih_lang_msg_base_array['loadingmessage'];
    }
}



