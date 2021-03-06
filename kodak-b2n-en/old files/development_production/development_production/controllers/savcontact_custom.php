<?
use RightNow\Connect\v1 as RNCPHP;
require_once(APPPATH.'libraries/load_msg_array.php');

class contact_custom extends ControllerBase
{
	private $_form;

	/*
	* Constructor
	*/
	public function __construct()
	{
		parent::__construct();

		$CI =& get_instance();
		
		$this->CI = $CI;
		$this->CI->load->model('custom/custom_contact_model');
		$this->CI->load->model('standard/contact_model');

		require_once(get_cfg_var('doc_root') . '/include/ConnectPHP/Connect_init.phph');
		require_once(get_cfg_var('doc_root') . '/include/ConnectPHP/Connect_init.phph');
		initConnectAPI();

	}
	
	public function get_contacts()
	{
		if($this->input->post('org_id') > 0)
			$orgID = $this->input->post('org_id');
        else
		  $orgID = $this->session->getProfileData('org_id');
	
		$deactivated = $this->input->post('deactivated');
		$contacts = $this->CI->custom_contact_model->getOrgContacts($orgID,$deactivated);
		$contacts['status'] = 1;
		print(json_encode($contacts));
		return;
		
	}
	
	public function contact_get()
	{
		$c_id= $this->input->post('c_id');
		
		$contact = $this->CI->custom_contact_model->getContact($c_id);
				
		$contact_array = $this->_getContactJSONString($contact);
		$contact_array['status'] = 1;
		print(json_encode($contact_array));
		return;
		
	}

        public function get_communication_pref()
        {

		$c_id= $this->input->post('c_id');
		
		$pref_list = $this->CI->custom_contact_model->getContactPreferences($c_id);
                $pref_list['status'] = 1;
                print(json_encode($pref_list));
                return;
        }

	/*
	* Answer review submission controller
	*/
	public function contact_update_submit()
	{
		
		$this->_instantiateForm();
		
		$c_id = $this->input->post('c_id') ||  $this->_form['c_id'];
//	logmessage('lang from form is '.$this->_form['sesslang'] );
		switch ($this->_form['sesslang']) {
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

//		if ($this->_form['sesslang'] == "fr") { $cih_lang_msg_base_array=load_array("csv_cih_french_strings.php");  }
//		else $cih_lang_msg_base_array=load_array("csv_cih_english_strings.php");
		
		$result = array();
// 		$result['dialog_title'] = 'Manage Contacts';
 		$result['dialog_title'] = $cih_lang_msg_base_array['managecontacts'];

		try
		{
			if($c_id > 0)
			{
	
  			        $found_cid = $this->CI->contact_model->lookupContactByEmail($this->_form['emailaddress']);

                                if($found_cid != $this->_form['c_id'] && $found_cid != "") {
                                  $error = $cih_lang_msg_base_array['emailalreadyexists'];
//                                  $error = "An account with this email already exists.";

                                  throw new Exception($error);
                                }
                                else {

                                  $this->_updateContact();

                                  $result['message'] = sprintf($cih_lang_msg_base_array['contactupdatesuccessfully'],$this->_form['firstname'],$this->_form['lastname']);
//                                  $result['message'] = sprintf("Contact %s %s was updated successfully.",$this->_form['firstname'],$this->_form['lastname']);
                                }
			}
			else
			{
logmessage("try create contact: ".$this->_form['emailaddress']);
				// Check to see if the contact exists by email address
				if($this->CI->contact_model->lookupContactByEmail($this->_form['emailaddress']))
				{
					$error = $cih_lang_msg_base_array['emailalreadyexists'];
//					$error = "An account with this email already exists.";
					throw new Exception($error);		
				}
				
				$this->_createContact();
				$result['message'] = sprintf("Contact %s %s was created successfully.",$this->_form['firstname'],$this->_form['lastname']);
//				$result['message'] = sprintf($cih_lang_msg_base_array['contactcreatedsuccessfully'],$this->_form['firstname'],$this->_form['lastname']);
			}	
			
		}
		catch(Exception $e)
		{
			$result['status']  = 0;
			$result['message'] = 'Error: ' . $e->getMessage();
			$result['form_values'] = $this->_form;
			print(json_encode($result));
			return;
		}
		
		
		$result['status']  = 1;
		$result['form_values'] = $this->_form;
		$result['c_id'] = $c_id; 
		print(json_encode($result));
		return;			

		
	}

	/*
	* Instantiate the easy-to-use form property.
	*/
	private function _instantiateForm()
	{
		$form = $this->CI->input->post('form');

		if (is_string($form))
			$form = json_decode($form);

		if (is_array($form))
			foreach ($form as $field)
				$this->_form[$field->name] = $field->value;
	}

	/*
	* Update contact
	*/
	private function _updateContact()
	{
		$c_id = $this->CI->custom_contact_model->updateContact($this->_form);		
	}
	
	/*
	* Create Contact
	*/
	
	private function _createContact()
	{
                logmessage("from custom_contact.php _createContact");
		$c_id = $this->CI->custom_contact_model->createContact($this->_form);
	}
	
	private function _getContactJSONString($contact)
	{

		$contact_json['status'] = 1;
		$contact_json['firstname'] = $contact->Name->First;
		$contact_json['lastname'] = $contact->Name->Last;
		$contact_json['emailaddress'] = $contact->Emails[0]->Address;
		//setup empty phone filed
		$contact_json['officephone'] = '';
		$contact_json['mobilephone'] = '';
		$contact_json['homephone'] = '';
		$contact_json['faxnumber'] = '';
		$contact_json['language1'] = '' ;
		$contact_json['language2'] = '' ;
		$contact_json['language3'] = '' ;
		$contact_json['optinglobal'] = false ;
		$contact_json['optinincident'] = false ;
		$contact_json['optincisurvey'] = false ;
		$contact_json['country'] = '';
		$contact_json['disabled'] = false;
		$contact_json['deactivate'] = false;
		
		if($contact->CustomFields->ek_lang_pref1->ID)
			$contact_json['language1'] = $contact->CustomFields->ek_lang_pref1->ID;
		
		if($contact->CustomFields->ek_lang_pref2->ID)
			$contact_json['language2'] = $contact->CustomFields->ek_lang_pref2->ID;
	
		if($contact->CustomFields->ek_lang_pref3->ID)
			$contact_json['language3'] = $contact->CustomFields->ek_lang_pref3->ID;
		
		if($contact->MarketingSettings->MarketingOptIn == 1)
			$contact_json['optinglobal'] = true ;
		
		if($contact->CustomFields->ek_inc_update_optin == 1)
			$contact_json['optinincident'] =true;
		
		if($contact->CustomFields->ek_closed_in_optin == 1)
			$contact_json['optincisurvey'] = true;
		
		if($contact->CustomFields->ek_country_safe_harbor->ID)
			$contact_json['country'] = $contact->CustomFields->ek_country_safe_harbor->ID;
		
		if($contact->Disabled == 1)
			$contact_json['disabled'] = true ;
		
		if($contact->CustomFields->ek_deactivate == 1)
			$contact_json['deactivate'] = true ;
		
		$contact_json['role'] = $contact->CustomFields->ek_role_id;
		
		$contact_json['login'] = $contact->Login;
		
		foreach($contact->Phones as $key=> $phone)
			{
				switch (strtolower($phone->PhoneType->LookupName))
				{
					case "office phone":
						$contact_json['officephone'] = $phone->Number;
					break;
					case "mobile phone":
						$contact_json['mobilephone'] = $phone->Number;
					break;
					case "home phone":
						$contact_json['homephone'] = $phone->Number;
					break;
					case "fax":
						$contact_json['faxnumber'] = $phone->Number;
					break;
				}
				
			}
			
		$contact_json['pperrormessage'] = $contact->CustomFields->ek_pp_integration_message;	
			
		return $contact_json;
		
	}
	

}
