<?php
namespace Custom\Models;

class cih_lang extends \RightNow\Models\Base {
    function __construct() {
        parent::__construct();
    }

    function get_msg_base_array($lang) {
		if(empty($lang))
			$lang = get_instance()->session->getSessionData("lang");

		switch ($lang) {
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
		return $cih_lang_msg_base_array;
    }
}
