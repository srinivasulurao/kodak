<?php
namespace Custom\Widgets\CIHFunction;

class AjaxFormSubmit extends \RightNow\Widgets\FormSubmit {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
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
		$this->data['attrs']['label_button'] = $cih_lang_msg_base_array['submit'];
        $this->data['js']['submitmsg'] = $cih_lang_msg_base_array['submitting'];

        return parent::getData();
    }
}