<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ContactSelect extends Widget
{
    function __construct()
    {
        parent::__construct();

		$this->attrs['label'] = new Attribute('', 'STRING', '', '');
		$this->attrs['name'] = new Attribute('', 'STRING', '', '');
		$this->attrs['selected_value'] = new Attribute('Selected Value','Int','The selected value of the select box when the page loads.',0);
		
		$this->attrs['no_selection_label'] = new Attribute('No Selection Label','STRING','First option that is displayed in the dropdown.','-- Create New Contact --');
		$this->attrs['include_deactivated'] = new Attribute('Include Deactivated','BOOL','Include deactivated contacts',true);
		$this->attrs['panel_name'] = new Attribute('Panel Name', 'String', 'Parent panel name', '');
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
		$this->data[js]['no_selection_label'] = $cih_lang_msg_base_array['createnewcontact'];
		$this->CI->load->model('custom/custom_contact_model');
		$org_id = $this->CI->session->getProfileData('org_id');
		$contacts = $this->CI->custom_contact_model->getOrgContacts($org_id,$this->data['attrs']['include_deactivated']);
		$this->data['contacts'] = $contacts;
		$this->data['js']['last_contact_id'] = $contacts[0]['last_contact_id'];
		$this->data['js']['org_id'] = $org_id;

    }
}
