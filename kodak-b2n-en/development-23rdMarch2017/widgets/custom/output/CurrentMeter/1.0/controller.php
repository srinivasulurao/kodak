<?php
namespace Custom\Widgets\output;

class CurrentMeter extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
		
    }

    function getData() {

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
		$this->data['js']['pidmv_counterid'] = $ccih_lang_msg_base_array['pidmv_counterid'];
		$this->data['js']['pidmv_description'] = $ccih_lang_msg_base_array['pidmv_description'];
		$this->data['js']['pidmv_lastreadingvalue'] = $ccih_lang_msg_base_array['pidmv_lastreadingvalue'];
		$this->data['js']['pidmv_unit'] = $ccih_lang_msg_base_array['pidmv_unit'];
		$this->data['js']['pidmv_readingsource'] = $ccih_lang_msg_base_array['pidmv_readingsource'];
		$this->data['js']['pidmv_lastreadingdate'] = $ccih_lang_msg_base_array['pidmv_lastreadingdate'];
		$this->data['js']['pidmv_currentreading'] = $ccih_lang_msg_base_array['pidmv_currentreading'];
		$this->data['js']['pidmv_dateofreading'] = $ccih_lang_msg_base_array['pidmv_dateofreading'];
		$this->data['js']['pidmv_action'] = $ccih_lang_msg_base_array['pidmv_action'];
		$this->data['js']['update'] = $ccih_lang_msg_base_array['update'];
        $this->data['js']['loadingmessage'] = $ccih_lang_msg_base_array['loadingmessage'];

    }
}