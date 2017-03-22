<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class WaitingPanel extends Widget
{
    function __construct()
    {
        parent::__construct();

        //Create attributes here
        $this->attrs['title'] = new Attribute("Title", 'String', "Title of dialog to be displayed", "text");
        $this->attrs['message'] = new Attribute("Message", 'String', "Text of the message to be displayed", "text");
        $this->attrs['fetch_url'] = new Attribute("Fetch URL", 'String', "url to retrieve data from ", "");
    }

    function generateWidgetInformation()
    {
        //Create information to display in the tag gallery here
        $this->info['notes'] =  "The popup dialog displays with an OK button.";
    }

    function getData()
    {
        //Perform php logic here		$sesslang = get_instance()->session->getSessionData("lang");		switch ($sesslang) {        case "en":			$ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 			break;        case "fr":			$ccih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 			break;        case "es":			$ccih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 			break;        case "pt":			$ccih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 			break;        default:			$ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 			break;		}								$this->data['js']['loadingmessage'] = $ccih_lang_msg_base_array['loadingmessage']; 
    }
}



