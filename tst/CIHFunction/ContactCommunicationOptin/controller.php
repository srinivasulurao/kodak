<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ContactCommunicationOptin extends Widget
{
    function __construct()
    {
        parent::__construct();
		$this->attrs['label'] = new Attribute('', 'STRING', '', '');
		$this->attrs['name'] = new Attribute('', 'STRING', '', '');
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = '';
    }

    function getData()
    {
	
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
        $this->data['js']['type'] = $ccih_lang_msg_base_array['type'];
        $this->data['js']['optin'] = $ccih_lang_msg_base_array['optin'];
        $this->data['js']['loadingmessage'] = $ccih_lang_msg_base_array['loadingmessage'];
		
/*
		$this->CI->load->model('custom/custom_contact_model');
		$org_id = $this->CI->session->getProfileData('org_id');
		$contacts = $this->CI->custom_contact_model->getOrgContacts($org_id,$this->data['attrs']['include_deactivated']);
		$this->data['contacts'] = $contacts;
		$this->data['js']['last_contact_id'] = $contacts[0]['last_contact_id'];
		$this->data['js']['org_id'] = $org_id;
*/
    }
}
