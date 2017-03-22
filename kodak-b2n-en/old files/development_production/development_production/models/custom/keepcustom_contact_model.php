<?php
require_once(APPPATH.'libraries/load_msg_array.php');
use RightNow\Connect\v1 as RNCPHP;
use RightNow\Connect\v1\CIH as RNCPHP_CO_CIH;

require_once( get_cfg_var("doc_root")."/include/ConnectPHP/Connect_init.phph" );
initConnectAPI();
class custom_contact_model extends Model
{
	private $cacheHandlePrefix = 'contact';
    function __construct()
    {
        parent::__construct();
        $this->load->model("standard/Customfield_model");
    }
	
	function getOrgContacts($orgID = null,$deactivated)
	{
		$status = $deactivated == 'true' ? 1:0;
		$query = sprintf("Select Contact From Contact where Contact.Organization.ID = %d Order By Contact.CreatedTime DESC",$orgID);
		if($status == 0)
			$query = sprintf("Select Contact From Contact where Contact.Organization.ID = %d and Contact.c\$ek_deactivate != 1  Order By Contact.CreatedTime DESC",$orgID);
		$contacts = RNCPHP\ROQL::queryObject($query)->next();
		$contactListArray = array();
		$last_contact = $this->getLastContact($orgID);
		
		$last_contact_id = $last_contact->ID;
				
		while($contact = $contacts->next())
		{
			$contactListArray[] = 	array('c_id'=>$contact->ID,'first_name'=>$contact->Name->First,'last_name'=>$contact->Name->Last,'phone'=>self::_formatPhone($contact->Phones[0]->Number),'last_contact_id'=>$last_contact_id);	
		}

//$sql = sprintf("SELECT c_id,first_name,last_name,ph_office FROM contacts WHERE org_id = %d order by created desc", $orgID);
// if($status == 0)
// $sql = sprintf("SELECT c_id,first_name,last_name,ph_office FROM contacts WHERE org_id = %d AND c\$ek_deactivate !=1 order by created desc", $orgID);
// $si = sql_prepare($sql);
// sql_bind_col($si, 1, BIND_INT, 0);
// sql_bind_col($si, 2, BIND_NTS, 100);
// sql_bind_col($si, 3, BIND_NTS, 100);
// sql_bind_col($si, 4, BIND_NTS, 100);
  
// $contactListArray = array();
// $last_contact = $this->getLastContact($orgID);
  
// $last_contact_id = $last_contact->ID;
  
// while($row = sql_fetch( $si ))
//{
// logMessage('looping first_name is'.$row[1]);
// $contactListArray[] = array('c_id'=>$row[0],'first_name'=>$row[1],'last_name'=>$row[2],'phone'=>$row[3],'last_contact_id'=>$last_contact_id);
// }
		foreach($contactListArray as $key => $row)
		{
			$lastName[$key] = $row['last_name'];	
		}
		
		array_multisort($lastName,SORT_ASC,$contactListArray);
		
		return $contactListArray;
	}
	
	public function getLoggedInUsersRole()
	{
                $contactID = $this->session->getProfileData('c_id');
		if(!$contactID)
			return;
                $contact = RNCPHP\Contact::fetch($contactID);
		

                $roleID = $contact->CustomFields->ek_role_id;

                return $roleID;
	}
	
	function getPPErrorOrgContacts($orgID = null)
	{
		if(!$orgID)
		{
			$profile = $this->session->getProfile();
			$orgID = $profile->org_id->value;
		}
		
		$query = sprintf("Select Contact From Contact where Contact.Organization.ID = %d And c\$ek_pp_data_error = 1 ",$orgID);
		$contacts = RNCPHP\ROQL::queryObject($query)->next();
		$contactListArray = array();
		
		while($contact = $contacts->next())
		{
			$contactListArray[] = array('c_id'=>$contact->ID,'first_name'=>$contact->Name->First,'last_name'=>$contact->Name->Last,'phone'=>self::_formatPhone($contact->Phones[0]->Number));	
		}
				
		foreach($contactListArray as $key => $row)
		{
			$lastName[$key] = $row['last_name'];	
		}
		
		array_multisort($lastName,SORT_ASC,$contactListArray);
		
		return $contactListArray;
	}
	
	function _formatPhone($num)
	{
		$num = preg_replace('/[^0-9]/', '', $num);
		
		$len = strlen($num);
		if($len == 7)
			$num = preg_replace('/([0-9]{3})([0-9]{4})/', '$1-$2', $num);
		elseif($len == 10)
			$num = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $num);
		
		return $num;
		
	}
	
	function getMenuList($lang, $fieldName,$table,$core_field = false)
	{
		$menu_array = array();
		if ($lang == "fr") 			$ccih_lang_msg_base_array=load_array("csv_cih_french_strings.php");
		else $ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php");

		if($core_field)
			{
			$namedValues = RNCPHP\ConnectAPI::GetNamedValues(sprintf('RightNow\\Connect\\v1\\%s',$table), $fieldName);
			}
		else{
				$namedValues = RNCPHP\ConnectAPI::GetNamedValues(sprintf('RightNow\\Connect\\v1\\%s.CustomFields',$table), $fieldName);
			}
		
		foreach($namedValues as $namedID)
		{			
//			$menu_array[] = array('ID'=>$namedID->ID,'LookupName'=>$namedID->LookupName);
			$menu_array[] = array('ID'=>$namedID->ID,'LookupName'=>$ccih_lang_msg_base_array[$namedID->ID]);
		}

		return 	$menu_array;
		
	}
	
	function getCountries($customFieldName)
	{
		$language_array = array();

		$namedValues = RNCPHP\ConnectAPI::GetNamedValues('RightNow\\Connect\\v1\\Contact.CustomFields', $customFieldName);
		
		foreach($namedValues as $namedID)
		{			
			$language_array[] = array('ID'=>$namedID->ID,'LookupName'=>$namedID->LookupName);
		}

		return 	$language_array;
		
	}
	
	function getContact($c_id)
	{
		$contact = RNCPHP\Contact::fetch($c_id);
		if($c_id < 1)
			$contact = $this->getLastContact();
		return $contact;	
	}
	
	function getLastContact($orgID = null)
	{
		if(!$orgID)
		{
			$profile = $this->session->getProfile();
			$orgID = $profile->org_id->value;
		}
		
		$query = sprintf("Select Contact From Contact where Contact.c\$ek_deactivate != 1 and Contact.Organization.ID = %d Order By Contact.CreatedTime DESC LIMIT 1",$orgID);
		$contacts = RNCPHP\ROQL::queryObject($query)->next();
		$contactListArray = array();
		
		while($contact = $contacts->next())
		{
			return $contact;	
		}
		
	}
	
	function createContact($values)
	{
		$CI =& get_instance();

                $internal =  $this->checkInternalContact($CI->session->getProfileData('c_id'));

                if($internal && $values['selectedOrg']) {
                  $orgID = $values['selectedOrg'];
                }
                elseif($values['ek_sap_soldto_custid']) {
                  $orgID = $this->getOrgBySAPID($values['ek_sap_soldto_custid']); //2012.10.17 scott harris: org needs to be that of the equipment owner
                }
                else {
                  $orgID = $CI->session->getProfileData('org_id');
                }
	
		$managed_cid = $CI->session->getProfileData('c_id');

		$contact = new RNCPHP\Contact;
		$contact->Organization = RNCPHP\Organization::fetch($orgID);
		$contact->Name = new RNCPHP\PersonName();
		$contact->Name->First = $values['firstname'];
		$contact->Name->Last = $values['lastname'];
		
		$contact->Emails = new RNCPHP\EmailArray();
		$contact->Emails[0] = new RNCPHP\Email();
		$contact->Emails[0]->AddressType=new RNCPHP\NamedIDOptList();
		$contact->Emails[0]->AddressType->LookupName = "Email - Primary";
		$contact->Emails[0]->Address = $values['emailaddress'];
		
		$contact->Phones = new RNCPHP\PhoneArray();		
		
		$officephone = new RNCPHP\Phone();
		$officephone->PhoneType = new RNCPHP\NamedIDOptList();
		$officephone->PhoneType->LookupName = "Office Phone";
		$officephone->Number = $values['officephone'] ? $values['officephone'] : "";
		$contact->Phones[] = $officephone;

		$mobilephone = new RNCPHP\Phone();
		$mobilephone->PhoneType = new RNCPHP\NamedIDOptList();
		$mobilephone->PhoneType->LookupName = "Mobile Phone";
		$mobilephone->Number = $values['mobilephone'] ? $values['mobilephone']  : "";
		$contact->Phones[] = $mobilephone;		

		$homephone = new RNCPHP\Phone();
		$homephone->PhoneType = new RNCPHP\NamedIDOptList();
		$homephone->PhoneType->LookupName = "Home Phone";
		$homephone->Number = $values['homephone'] ? $values['homephone'] : "";
		$contact->Phones[] = $homephone;		

		$fax = new RNCPHP\Phone();
		$fax->PhoneType = new RNCPHP\NamedIDOptList();
		$fax->PhoneType->LookupName = "Fax Phone";
		$fax->Number = $values['faxnumber'] ? $values['faxnumber'] : "";
		$contact->Phones[] = $fax;
		
		if($values['language1'])
		{
			$contact->CustomFields->ek_lang_pref1 = new RNCPHP\NamedIDLabel();
			$contact->CustomFields->ek_lang_pref1->ID = $values['language1'] ? $values['language1'] : "";		
		}
		
		if($values['language2'])
		{
			$contact->CustomFields->ek_lang_pref2 = new RNCPHP\NamedIDLabel();
			$contact->CustomFields->ek_lang_pref2->ID = $values['language2'] ? $values['language2'] : "";	
		}
		
		if($values['language3'])
		{
			$contact->CustomFields->ek_lang_pref3 = new RNCPHP\NamedIDLabel();
			$contact->CustomFields->ek_lang_pref3->ID = $values['language3'] ? $values['language3'] : "";	
		}
		
		if($values['country'])
			{
			$contact->CustomFields->ek_country_safe_harbor = new RNCPHP\NamedIDLabel();
			$contact->CustomFields->ek_country_safe_harbor->ID = $values['country'] ? $values['country'] : "";		
			}
		
		$contact->MarketingSettings->MarketingOptIn = $values['optinglobal'];
		
		$contact->CustomFields->ek_inc_update_optin = $values['optinincident'];

		$contact->CustomFields->ek_closed_in_optin  = $values['optincisurvey'];
		
		$contact->Disabled = $values['disabled'] ? $values['disabled'] : false;
		
		//$contact->CustomFields->ek_deactivate = $values['deactivate'];
		
		
		
		$contact->CustomFields->ek_managed_by_contact = $managed_cid;
		
		if($values['disabled'] == true)
			{
				$contact->Login = "";
			$contact->CustomFields->ek_role_id  = null;
			}
		else{
				$contact->Login = $values['emailaddress'];
			$contact->CustomFields->ek_role_id  = $values['role'];
			}

                //create note for history
                $mng_contact = $this->getContact(intval($managed_cid));
                $contact->Notes = new RNCPHP\NoteArray();
                $contact->Notes[0] = new RNCPHP\Note();
                $contact->Notes[0]->Text = sprintf("created (GMT) %s, created by %s %s", gmdate("Y-m-d H:i:s"), $mng_contact->Name->First, $mng_contact->Name->Last);
                $contact->save();
	
		return $contact->ID;	
	}
	
	function updateContact($values)
	{

		$managed_cid = $this->CI->session->getProfileData('c_id');
		$contact = RNCPHP\Contact::fetch( $values['c_id']);
		
		$contact->Name->First = $values['firstname'];
		$contact->Name->Last = $values['lastname'];		
		$contact->Emails = new RNCPHP\EmailArray();
		$contact->Emails[0] = new RNCPHP\Email();
		$contact->Emails[0]->AddressType=new RNCPHP\NamedIDOptList();
		$contact->Emails[0]->AddressType->LookupName = "Email - Primary";
		$contact->Emails[0]->Address = $values['emailaddress'];
		
		$contact->Phones = new RNCPHP\PhoneArray();		
		
		$officephone = new RNCPHP\Phone();
		$officephone->PhoneType = new RNCPHP\NamedIDOptList();
		$officephone->PhoneType->LookupName = "Office Phone";
		$officephone->Number = $values['officephone'] ? $values['officephone'] : "";
		$contact->Phones[] = $officephone;

		$mobilephone = new RNCPHP\Phone();
		$mobilephone->PhoneType = new RNCPHP\NamedIDOptList();
		$mobilephone->PhoneType->LookupName = "Mobile Phone";
		$mobilephone->Number = $values['mobilephone'] ? $values['mobilephone']  : "";
		$contact->Phones[] = $mobilephone;		

		$homephone = new RNCPHP\Phone();
		$homephone->PhoneType = new RNCPHP\NamedIDOptList();
		$homephone->PhoneType->LookupName = "Home Phone";
		$homephone->Number = $values['homephone'] ? $values['homephone'] : "";
		$contact->Phones[] = $homephone;		

		$fax = new RNCPHP\Phone();
		$fax->PhoneType = new RNCPHP\NamedIDOptList();
		$fax->PhoneType->LookupName = "Fax Phone";
		$fax->Number = $values['faxnumber'] ? $values['faxnumber'] : "";
		$contact->Phones[] = $fax;
		
		if($values['language1'])
		{
			$contact->CustomFields->ek_lang_pref1 = new RNCPHP\NamedIDLabel();
			$contact->CustomFields->ek_lang_pref1->ID = $values['language1'] ? $values['language1'] : "";		
		}
		
		if($values['language2'])
		{
			$contact->CustomFields->ek_lang_pref2 = new RNCPHP\NamedIDLabel();
			$contact->CustomFields->ek_lang_pref2->ID = $values['language2'] ? $values['language2'] : "";	
		}
		
		if($values['language3'])
		{
			$contact->CustomFields->ek_lang_pref3 = new RNCPHP\NamedIDLabel();
			$contact->CustomFields->ek_lang_pref3->ID = $values['language3'] ? $values['language3'] : "";	
		}
				
		if($values['country'])
		{
			$contact->CustomFieldsek_country_safe_harbor = new RNCPHP\NamedIDLabel();
			$contact->CustomFields->ek_country_safe_harbor->ID = $values['country'] ? $values['country'] : "";		
		}
		
		
		$contact->MarketingSettings->MarketingOptIn = $values['optinglobal'];
		
		$contact->CustomFields->ek_inc_update_optin = $values['optinincident'];

		$contact->CustomFields->ek_closed_in_optin  = $values['optincisurvey'];
		
		$contact->Disabled = $values['disabled'];
		
		//$contact->CustomFields->ek_deactivate = $values['deactivate'];
		
		
		
		$contact->CustomFields->ek_managed_by_contact = $managed_cid;
		
		if($values['disabled'] == true)
		{
			$contact->Login = "";
			$contact->CustomFields->ek_role_id  = null;
		}
		else
		{			
			$contact->Login = $values['emailaddress'];

                        if($values['role'] && $values['role'] != "")
			  $contact->CustomFields->ek_role_id  = $values['role'];
		}
		
		$contact->save();

                //perform updates to communication preferences
                $optin_results = $this->parseOptinValues($values['communication_optin_list']);

                if(array_key_exists('add',$optin_results)) {
                  
                  for($i=0; $i<count($optin_results['add']); $i++) {


                    $optn = new RNCPHP_CO_CIH\ek_contact_comm_optn();
                    $optn->c_id=$contact;  //$values['c_id'];
              
                    $comm_type = RNCPHP_CO_CIH\ek_comm_type::fetch($optin_results['add'][$i]);
                    $optn->communication_type=$comm_type;
                    $optn->save(RNCPHP_CO_CIH\RNObject::SuppressAll);

                  }

                }

                if(array_key_exists('del',$optin_results)) {

                  for($i=0; $i<count($optin_results['del']); $i++) {

                    $comm_optin = RNCPHP_CO_CIH\ek_contact_comm_optn::first(sprintf("c_id = %d and communication_type = %d", $values['c_id'], $optin_results['del'][$i]));

                    if($comm_optin != null) {
                      $del_comm = RNCPHP_CO_CIH\ek_contact_comm_optn::fetch($comm_optin->ID);
                      $del_comm->destroy();
                      RNCPHP\ConnectAPI::commit();
                    }
                  }


                }



		return $contact->ID;
		
	}
	
	function EmailExists($email,$contactid = null)
	{
		$query = sprintf("Emails.Address = '%s' AND Emails.AddressType = '0'",$email);
		if($contactid)
			$query = sprintf("ID != %d AND Emails.Address = '%s' AND Emails.AddressType = '0'",$contactid,$email);
		$contact = RNCPHP\Contact::first($query);
		if($contact)
			return true;
		return false;	
	}

        function getOrgBySAPID($id) {

          $org_id = 0;

          $query = sprintf("select organization from organization where organization.c\$ek_customer_sapid = '%s'",$id);

          $result = RNCPHP\ROQL::queryObject($query)->next();
          $org = $result->next();

          $org_id = $org->ID;

          return $org_id;

        }	

        function getContactPreferences($c_id) {
 
          $results_array = array();
          $arr = array();
          $comm_preferences = array();
          $full_list_report_id = 100980;

          $ar= RNCPHP\AnalyticsReport::fetch( $full_list_report_id);

          $result= $ar->run(0, null);
          $lang = get_instance()->session->getSessionData("lang");
		  if ($lang == "fr") 			$ccih_lang_msg_base_array=load_array("csv_cih_french_strings.php");
		  else $ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php");
//logMessage("lang is ".$lang);

          while($row = $result->next()) {
		    if (($row['Name'] == 'AUR' ) && ($lang == "fr")) 
				$row['Name'] = $ccih_lang_msg_base_array['compref_aur'];
		    if (($row['Name'] == 'Equipment Installation' ) && ($lang == "fr")) 
				$row['Name'] = $ccih_lang_msg_base_array['compref_equipinstallation'];
		    if (($row['Name'] == 'Equipment Relocation' ) && ($lang == "fr")) 
				$row['Name'] = $ccih_lang_msg_base_array['compref_equiprelocation'];
		    if (($row['Name'] == 'Equipment Removal' ) && ($lang == "fr")) 
				$row['Name'] = $ccih_lang_msg_base_array['compref_equipremoval'];
		    if (($row['Name'] == 'Field Change Order/Mod' ) && ($lang == "fr")) 
				$row['Name'] = $ccih_lang_msg_base_array['compref_fieldchange'];
		    if (($row['Name'] == 'HSE (Smoke/Smell/Safety)' ) && ($lang == "fr")) 
				$row['Name'] = $ccih_lang_msg_base_array['compref_hse'];
		    if (($row['Name'] == 'Preventative Maintenance' ) && ($lang == "fr")) 
				$row['Name'] = $ccih_lang_msg_base_array['compref_pm'];
		    if (($row['Name'] == 'Repair' ) && ($lang == "fr")) 
				$row['Name'] = $ccih_lang_msg_base_array['compref_repair'];
            $comm_preferences[$row['Name']] = array('ID'=>$row['ID'], 'Optin'=>0, 'Label'=>$row['Name']); 
          }

          $optin_list_report_id = 100983;

          $ar= RNCPHP\AnalyticsReport::fetch( $optin_list_report_id);

          $contact_filter= new RNCPHP\AnalyticsReportSearchFilter;
          $contact_filter->Name = 'c_id';
          $contact_filter->Values = array($c_id);

          $filters = new RNCPHP\AnalyticsReportSearchFilterArray;
          $filters[] = $contact_filter;

          $result= $ar->run(0, $filters );

          while($row = $result->next()) {
 		    if (($row['communication_type'] == 'AUR' ) && ($lang == "fr")) 
				$row['communication_type'] = $ccih_lang_msg_base_array['compref_aur'];
		    if (($row['communication_type'] == 'Equipment Installation' ) && ($lang == "fr")) 
				$row['communication_type'] = $ccih_lang_msg_base_array['compref_equipinstallation'];
		    if (($row['communication_type'] == 'Equipment Relocation' ) && ($lang == "fr")) 
				$row['communication_type'] = $ccih_lang_msg_base_array['compref_equiprelocation'];
		    if (($row['communication_type'] == 'Equipment Removal' ) && ($lang == "fr")) 
				$row['communication_type'] = $ccih_lang_msg_base_array['compref_removal'];
		    if (($row['communication_type'] == 'Field Change Order/Mod' ) && ($lang == "fr")) 
				$row['communication_type'] = $ccih_lang_msg_base_array['compref_fieldchange'];
		    if (($row['communication_type'] == 'HSE (Smoke/Smell/Safety)' ) && ($lang == "fr")) 
				$row['communication_type'] = $ccih_lang_msg_base_array['compref_hse'];
		    if (($row['communication_type'] == 'Preventative Maintenance' ) && ($lang == "fr")) 
				$row['communication_type'] = $ccih_lang_msg_base_array['compref_pm'];
		    if (($row['communication_type'] == 'Repair' ) && ($lang == "fr")) 
				$row['communication_type'] = $ccih_lang_msg_base_array['compref_repair'];
            $comm_preferences[$row['communication_type']]['Optin']=1;
          }

          foreach($comm_preferences as $key=>$val) {
            $arr[] = $val; 
          }

          if(count($comm_preferences) > 0)
            $results_array[] = array('communication_preferences'=>$arr);

          return $results_array;


        }

        function parseOptinValues($str) {

          $result = array();
          $patt_add = "/A([0-9]{1,})+/";
          $patt_del = "/D([0-9]{1,})+/";
          $found_add = preg_match_all($patt_add, $str, $adds);
          $found_del = preg_match_all($patt_del, $str, $deletes);

          $str_add = implode($adds[1],",");
          $str_del = implode($deletes[1],",");

          if($found_add) {
            logmessage("parseOptinValues::found adds");
            $result['add'] = $adds[1];
          }
          if($found_del)
            $result['del'] = $deletes[1];

          return $result;
        }

        function checkInternalContact($c_id) {

          $internal = "N";

          $CI =& get_instance();

          $orgID = $CI->session->getProfileData('org_id');

          $email = $CI->session->getProfileData('email');

          $internal_org_id = 0;

          $report_id = 101148; 
          $ar= RNCPHP\AnalyticsReport::fetch( $report_id);

          $contact_filter= new RNCPHP\AnalyticsReportSearchFilter;
          $contact_filter->Name = 'c_id';

          $contact_filter->Values = array($c_id);

          $filters = new RNCPHP\AnalyticsReportSearchFilterArray;
          $filters[] = $contact_filter;

          $result= $ar->run(0, $filters );

          while($row = $result->next()) {
            $internal_org_id = $row['internal_orgid'];
          }

          if(preg_match("/@kodak.com$/i", $email) && $orgID == $internal_org_id)
            $internal = "Y";

          return $internal;

        }
     
}
