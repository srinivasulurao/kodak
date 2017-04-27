<?php
namespace Custom\Widgets\CIHFunction;

class ContactCommunicationOptin extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
        //$sesslang=get_instance()->session->getSessionData("lang");
        $sesslang="en";
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
        // echo "<pre>";
        // echo "Hello";
        // print_r($this->sesslang);
        // echo "</pre>";
        // die;
        $this->data['js']['type'] = $ccih_lang_msg_base_array['type'];
        $this->data['js']['optin'] = $ccih_lang_msg_base_array['optin'];
        $this->data['js']['loadingmessage'] = $ccih_lang_msg_base_array['loadingmessage'];
        return parent::getData();
    }
}