<?php
namespace Custom\Widgets\output;

class MeterHistory extends \RightNow\Libraries\Widget\Base {
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
		$this->data['js']['pidmvmh_counterid'] = $ccih_lang_msg_base_array['pidmvmh_counterid'];
		$this->data['js']['pidmvmh_description'] = $ccih_lang_msg_base_array['pidmvmh_description'];
		$this->data['js']['pidmvmh_lastreadingvalue'] = $ccih_lang_msg_base_array['pidmvmh_lastreadingvalue'];
		$this->data['js']['pidmvmh_unit'] = $ccih_lang_msg_base_array['pidmvmh_unit'];
		$this->data['js']['pidmvmh_readingsource'] = $ccih_lang_msg_base_array['pidmvmh_readingsource'];
		$this->data['js']['pidmvmh_lastreadingdate'] = $ccih_lang_msg_base_array['pidmvmh_lastreadingdate'];
		

        return parent::getData();

    }
	
}//Class Ends here.