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
 		$sesslang = get_instance()->session->getSessionData("lang");
		switch ($sesslang) {
        case "en":
			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
        case "fr":
			$cih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 
			break;
        case "es":
			$cih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 
			break;
        case "pt":
			$cih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 
			break;
        default:
			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
		}						
        $this->data['js']['loadingmessage'] = $cih_lang_msg_base_array['loadingmessage'];

   }
}



