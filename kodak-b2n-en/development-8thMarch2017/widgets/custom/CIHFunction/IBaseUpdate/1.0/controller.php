<?php
namespace Custom\Widgets\CIHFunction;

class IBaseUpdate extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
		
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

        return parent::getData();

    }

    
}