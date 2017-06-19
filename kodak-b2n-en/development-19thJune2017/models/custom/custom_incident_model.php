<?php
namespace Custom\Models;

use RightNow\Connect\v1 as RNCPHP;
require_once( get_cfg_var("doc_root")."/include/ConnectPHP/Connect_init.phph" );
initConnectAPI();
class custom_incident_model extends \RightNow\Models\Base {
    private $cacheHandlePrefix = 'incident';
    
    function __construct()
    {
        parent::__construct();
        
        $CI = &get_instance();
        
        $this->CI = $CI;
    
    }
    
    function addThread($values,$thread){
        $i_id = $values['obj_id'];
        
        $arr_result = array();
        
        $incident = RNCPHP\Incident::fetch($i_id);
        $new_thread = new RNCPHP\Thread();
        $new_thread->EntryType = new RNCPHP\NamedIDOptList();
        $new_thread->EntryType->ID = 3;
        $new_thread->Channel = new RNCPHP\NamedIDLabel();
        $new_thread->Channel->ID = 6;
        $new_thread->Text = urldecode($thread);
        $incident->Threads[] = $new_thread;
        $incident->StatusWithType->Status = new RNCPHP\NamedIDOptList();
        $incident->StatusWithType->Status->LookupName = 'Updated';
        $incident->save();
        RNCPHP\ConnectAPI::commit();
        
    }
    
    function createIncident($values,$thread = null)
    {
        $c_id = $values['c_id'];
            $arr_result = array();
                $contact_is_new = 0;
                $lu_ibase_update_types = array();
                $lu_ibase_update_types[460] = 'Equipment Removal';
                $lu_ibase_update_types[461] = 'Equipment Relocation';
                $list_partners = "";
    
        $incident = new RNCPHP\Incident();
        $note = "";
    
        $incident->StatusWithType->Status = new RNCPHP\NamedIDOptList();
        $incident->StatusWithType->Status->LookupName = 'Open';
        
        if(!$c_id) { 
                        $contact_is_new = 1;
            $c_id = $this->CI->model("custom/custom_contact_model")->createContact($values);
                }
        
        // 2014.6.24 Jaime Grochowski: When type is Repair request, load model and pass contact ID and form data values object to
        // initiate credit check and conditionally update form data with new properties for incident creation.
        if ($values['ek_type']=="Repair") {
            $this->CI->model("custom/credit_check_model")->repairRequestCreditCheck($c_id, $values);
        }
        
        $incident->PrimaryContact = RNCPHP\Contact::fetch($c_id);
                $orgID = $this->CI->model("custom/custom_contact_model")->getOrgBySAPID($values['ek_sap_soldto_custid']);
                $incident->Organization = RNCPHP\Organization::fetch($orgID);
                //$incident->Organization = RNCPHP\Organization::fetch($values['org_id']);
        
        if($values['c_id'] != $values['managed_cid'] && $values['secondarycontact'] && $values['managed_cid'])
            {
                $secondary_contact = RNCPHP\Contact::fetch($values['managed_cid']);
                $incident->OtherContacts = new RNCPHP\ContactArray;
                $incident->OtherContacts[] = $secondary_contact;
            }
                //$prod_list = explode(',',$values['prod']);
                $prod_list = $values['prod'];
                //$cat_list = explode(',',$values['cat']);
                $cat_list = $values['cat'];
                $last_prod = $prod_list[count($prod_list) - 1];
                $last_cat = $cat_list[count($cat_list) - 1];
        if($last_prod)
            {
                $queryResult = RNCPHP\ROQL::queryObject("SELECT ServiceProduct FROM ServiceProduct WHERE ID = $last_prod")->next();
                $incident->Product = $queryResult->next();
            }
                
        if($last_cat)
            {
                $queryResult = RNCPHP\ROQL::queryObject("SELECT ServiceCategory FROM ServiceCategory WHERE ID = $last_cat")->next();
                $incident->Category = $queryResult->next(); 
            }
        $incident->CustomFields->ek_type = new RNCPHP\NamedIDLabel();
        $incident->CustomFields->ek_type->LookupName = $values['ek_type'];
        
        $incident->Threads = new RNCPHP\ThreadArray();
        $incident->Threads[0] = new RNCPHP\Thread();
        $incident->Threads[0]->EntryType = new RNCPHP\NamedIDOptList();
        $incident->Threads[0]->EntryType->ID = 3;
        if($thread)
            {
                $comments =  $thread;   
            }
        else
            {
                $comments =      $values['thread'];
            }
        $incident->Threads[0]->Text = urldecode($comments);
                if($values['ek_enabling_partner'] != "") {
                  $incident->CustomFields->ek_enabling_partner = $values['ek_enabling_partner'];
                  $list_partners .= sprintf("'%s',", $values['ek_enabling_partner']);
                }
                if($values['ek_mvs_manfacturer'] != "") {
                  $incident->CustomFields->ek_mvs_manfacturer = $values['ek_mvs_manfacturer'];
                  $list_partners .= sprintf("'%s',", $values['ek_mvs_manfacturer']);
                }
                if($values['ek_service_dist'] != "") {
                  $incident->CustomFields->ek_service_dist = $values['ek_service_dist'];
                  $list_partners .= sprintf("'%s',", $values['ek_service_dist']);
                }
                if($values['ek_service_reseller'] != "") {
                  $incident->CustomFields->ek_service_reseller = $values['ek_service_reseller'];
                  $list_partners .= sprintf("'%s',", $values['ek_service_reseller']);
                }
                if($values['ek_corporate'] != "")
                  $incident->CustomFields->ek_corporate = $values['ek_corporate'];
                if($values['ek_sds'] != "")
                  $incident->CustomFields->ek_sds->LookupName = $values['ek_sds'];
                if($values['ek_k_number'] != "")
                  $incident->CustomFields->ek_k_number = $values['ek_k_number'];
                if($values['ek_serial_number'] != "")
                  $incident->CustomFields->ek_serial_number = $values['ek_serial_number'];
                if($values['ek_sap_product_id'] != "")
                  $incident->CustomFields->ek_sap_product_id = $values['ek_sap_product_id'];
                if($values['ek_sap_soldto_custid'] != "")
                  $incident->CustomFields->ek_sap_soldto_custid = $values['ek_sap_soldto_custid'];
                if($values['ek_service_profile'] != "")
                  $incident->CustomFields->ek_service_profile = $values['ek_service_profile'];
                if($values['ek_response_profile'] != "")
                  $incident->CustomFields->ek_response_profile = $values['ek_response_profile'];
                if($values['ek_equip_component_id'] != "")
                  $incident->CustomFields->ek_equip_component_id = $values['ek_equip_component_id'];
                if($values['ek_error_code'] != "")
                  $incident->CustomFields->ek_error_code = $values['ek_error_code'];
                if($values['ek_ext_ref_no'] != "")
                  $incident->CustomFields->ek_ext_ref_no = $values['ek_ext_ref_no'];
                if($values['ek_remote_eos'] != "")
                  $incident->CustomFields->ek_remote_eos = $values['ek_remote_eos'];
                if($values['ek_onsite_eos'] != "")
                  $incident->CustomFields->ek_onsite_eos = $values['ek_onsite_eos'];
                if($values['orig_submit_id'] != "")
                    $incident->CustomFields->orig_submit_id = $values['orig_submit_id'];
                if($values['orig_submit_name'] != "")
                    $incident->CustomFields->orig_submit_name = $values['orig_submit_name'];
//$txt = $values["ek_remote_access_perm"];
//logmessage("ek_remote_access_perm is $txt");					
                if($values['ek_remote_access_perm'] != "")
                    $incident->CustomFields->ek_remote_access_perm = $values['ek_remote_access_perm'];
				else $incident->CustomFields->ek_remote_access_perm = "0";
                
                // 2014.6.24 Jaime Grochowski: check for properties added by credit_check_model
                if(isset($values['ek_credit_hold']))
                    $incident->CustomFields->ek_credit_hold = $values['ek_credit_hold'];
                if(isset($values['ek_incident_hold']))
                    $incident->CustomFields->ek_incident_hold = $values['ek_incident_hold'];
                if(isset($values['ek_pymt_terms']))
                    $incident->CustomFields->ek_pymt_terms = $values['ek_pymt_terms'];
                if(!empty($values['credit_private_note'])) {
                    /**** 07/21/2016 - Integration logs to Incident Threads - will now be written to PSLog
                    $incident->Threads[1] = new RNCPHP\Thread();
                    $incident->Threads[1]->EntryType = new RNCPHP\NamedIDOptList();
                    $incident->Threads[1]->EntryType->ID = 1;
                    $incident->Threads[1]->Text = $values['credit_private_note'];
                    ****/
					$note = $values['credit_private_note'];
                }
                if(isset($values['assigned_to'])) {
                    $incident->AssignedTo = new RNCPHP\GroupAccount();
                    $incident->AssignedTo->Account = RNCPHP\Account::fetch($values['assigned_to']);
                }
        
				if($values['ek_severity'])
				{
					$incident->CustomFields->ek_severity = new RNCPHP\NamedIDLabel();
					$incident->CustomFields->ek_severity->ID = $values['ek_severity'];
				}
        
				if($values['ek_severity'])
				{               
					$incident->CustomFields->ek_repeatability = new RNCPHP\NamedIDLabel();
					$incident->CustomFields->ek_repeatability->ID = $values['ek_repeatability'];                
				}
				if($values['ek_ibase_updt_type'])
				{
					$incident->CustomFields->ek_ibase_updt_type = new RNCPHP\NamedIDLabel();
					$incident->CustomFields->ek_ibase_updt_type->ID = $values['ek_ibase_updt_type'];
				}
                //2012.12.31 scott harris: retrieve contacts for partners where communication is requested
                $comm_type_id = $this->lookup_type_byname($values['ek_type']);
                if($values['ek_type'] == "Administrative") {
                  $comm_type_id = $this->lookup_type_byname($lu_ibase_update_types[$values['ek_ibase_updt_type']]);
                  
                }
                $all_contacts = array();
                $query = sprintf("select organization from organization where c\$ek_customer_sapid in (%s)", rtrim($list_partners,",") == "" ? "''" : rtrim($list_partners,","));
                $result = RNCPHP\ROQL::QueryObject($query)->next();
                while($row = $result->next()) {
                  $id = $row->ID;
  
                  if($this->check_org_comm_pref($id, $comm_type_id)) {  //if org is opted in for this type, gather all contacts needed
                    //find all contacts for org that are set to receive repair type messages
                    $query = sprintf("select ID from Contact where Organization.ID = %d and CIH\$ek_contact_comm_optn\$C_ID.communication_type = %d", $id, $comm_type_id);
                    $comm_result = RNCPHP\ROQL::query($query)->next();
                    while($row = $comm_result->next()) {
                      $all_contacts[] = $row['ID'];
                    }
                  }
                }
                if(count($all_contacts) > 0) {  //only add secondary contacts if needed
                  $incident->OtherContacts = new RNCPHP\ContactArray;
                  for($i=0; $i<count($all_contacts); $i++) {
                    $secondary_contact = RNCPHP\Contact::fetch($all_contacts[$i]);
                    $incident->OtherContacts[] = $secondary_contact;
                  }
                }
            
                if($contact_is_new) {
                  //$incident->CustomFields->ek_contact_hold = 1;
                  $incident->CustomFields->ek_sap_svc_order_guid = "0";
				  $incident->save(RNCPHP\RNObject::SuppressExternalEvents);
                } 
                else
				$incident->save(); 
			    
				
                //2012.09.19 scott harris: local time is being set by ee_inc.php external event
				//set the local time
				//$this->check_and_set_local_time($incident->ID);
        
				//return $incident->ID;
                $arr_result['i_id'] = $incident->ID;
				$ref_incident = RNCPHP\Incident::fetch($incident->ID);  
                //$arr_result['refno'] = $ref_incident->ID;  //$ref_incident->ReferenceNumber;
                $arr_result['refno'] = $ref_incident->ReferenceNumber;
				// Write Integration logs to PSLOG
				if(!empty($note) && intval($incident->ID)>0)
					$this->save_as_thread_note($incident->ID,$note);
                return $arr_result;
        
    }
	function save_as_thread_note($i_id, $note)
	{	
	
		 try {
			 
			$inc = RNCPHP\Incident::fetch( $i_id, RNCPHP\RNObject::VALIDATE_KEYS_OFF );
			// constants type- AddIn = 1;CP = 2;Cron = 3;CustomAPI = 4;Export = 5;ExternalEvent = 6;Import = 7;CPM = 8;
			// severity - Fatal = 1; Error = 2;Warning = 3;Notice = 4;Info = 5;Debug = 6;
			$type = 2;
			$severity = 5;
			$subType = 'IntegrationLogs';
			// create new log record
			$log = new RNCPHP\PSLog\Log();
			$log->Message = substr($note, 0, 255);
			$log->Note = $note;
			$log->Incident = $inc;
			$log->Source = __FILE__;
			$log->Type = RNCPHP\PSLog\Type::fetch($type);
			$log->Severity = RNCPHP\PSLog\Severity::fetch($severity);
			$log->SubType = $subType;
			$log->save(RNCPHP\RNObject::SuppressAll);  

		} catch ( RNCPHP\ConnectAPIError $err )
		{
			//phpoutlog("HDONG - save as thread note - Exception -- " . $err);	
		}
	}
	
        function verifyAccess($i_id)
        {
          if(!$i_id)
            return "0";
          $CI =& get_instance();
          $loggedinContactOrgID = $CI->session->getProfileData('org_id');
          $loggedinContactOrg = RNCPHP\Organization::fetch($loggedinContactOrgID);
          $internal = $CI->model("custom/custom_contact_model")->checkInternalContact($CI->session->getProfileData('c_id'));
          $incident = RNCPHP\Incident::fetch($i_id);
          $contact = RNCPHP\Contact::fetch($incident->PrimaryContact->ID);
          $partner = false;logMessage('incident->CustomFields->ek_enabling_partner'.$incident->CustomFields->ek_enabling_partner);logMessage('loggedinContactOrg->CustomFields->ek_customer_sapid'.$loggedinContactOrg->CustomFields->ek_customer_sapid);
          if($incident->CustomFields->ek_enabling_partner && ($incident->CustomFields->ek_enabling_partner == $loggedinContactOrg->CustomFields->ek_customer_sapid) ||
             $incident->CustomFields->ek_mvs_manfacturer && ($incident->CustomFields->ek_mvs_manfacturer == $loggedinContactOrg->CustomFields->ek_customer_sapid) ||
             $incident->CustomFields->ek_service_dist && ($incident->CustomFields->ek_service_dist == $loggedinContactOrg->CustomFields->ek_customer_sapid) ||
             $incident->CustomFields->ek_corporate && ($incident->CustomFields->ek_corporate == $loggedinContactOrg->CustomFields->ek_customer_sapid) ||
             $incident->CustomFields->ek_service_reseller && ($incident->CustomFields->ek_service_reseller == $loggedinContactOrg->CustomFields->ek_customer_sapid))			 {logMessage('partner = true');
            $partner = true;}
          if($internal == 'N' && !$partner && $contact->Organization->CustomFields->ek_customer_sapid != $loggedinContactOrg->CustomFields->ek_customer_sapid)
            return "0";
          else
            return "1";
        }
        function verifyCorpAccess($i_id, $corp_id)
        {
          if(!$i_id)
            return "0";
          $CI =& get_instance();
          $loggedinContactOrgID = $CI->session->getProfileData('org_id');
          $loggedinContactOrg = RNCPHP\Organization::fetch($loggedinContactOrgID);
          $incident = RNCPHP\Incident::fetch($i_id);
          $contact = RNCPHP\Contact::fetch($incident->PrimaryContact->ID);
          $partner = false;
          		  if($incident->CustomFields->ek_enabling_partner == $corp_id && $incident->CustomFields->ek_enabling_partner == $loggedinContactOrg->CustomFields->ek_enabling_partner) 
            $partner = true;          if($incident->CustomFields->ek_corporate == $corp_id && $incident->CustomFields->ek_corporate == $loggedinContactOrg->CustomFields->ek_corporate)             $partner = true;          if($incident->CustomFields->ek_corporate == $corp_id && $incident->CustomFields->ek_corporate == $loggedinContactOrg->CustomFields->ek_corporate)             $partner = true;          if($incident->CustomFields->ek_corporate == $corp_id && $incident->CustomFields->ek_corporate == $loggedinContactOrg->CustomFields->ek_corporate)             $partner = true;          if($incident->CustomFields->ek_corporate == $corp_id && $incident->CustomFields->ek_corporate == $loggedinContactOrg->CustomFields->ek_corporate)             $partner = true;
          if($partner)
            return "1";
          else
            return "0";
        }
    
    function getServiceRequestDetails($i_id)
    {

        if(!$i_id)
            return;
        $srd_arr = array();
        $incident = RNCPHP\Incident::fetch($i_id);  
        $contact = RNCPHP\Contact::fetch($incident->PrimaryContact->ID);
        //Get the event log archive status record
        $query = sprintf("Select CIH.ek_event_log from CIH.ek_event_log L where L.ek_i_id = %s and L.ek_status.LookupName = 'Archived'",$i_id);
        $event_log = RNCPHP\ROQL::QueryObject($query)->next();
        
        while($event = $event_log->next())
        {
            $srd_arr['close_date'] = $event->ek_transaction_datetime;   
        }
        
        $address;
        
        //Determine the address
        if($incident->CustomFields->ek_sloc_street)
            {
                $address = sprintf("%s<br/>%s<br/> %s<br/>%s<br/>%s<br/>",$incident->CustomFields->ek_sloc_street,$incident->CustomFields->ek_sloc_city,$incident->CustomFields->ek_sloc_region->LookupName,$incident->CustomFields->ek_sloc_postal_code,$incident->CustomFields->ek_sloc_country->LookupName);
                $address = str_ireplace("<br/><br/>","<br/>",$address);
            }
        else{
                $org_address = $contact->Organization->Addresses[0];
                
                if($org_address)
                    {
                $address = sprintf("%s<br/>%s<br/>%s<br/>%s<br/>%s<br/>",$org_address->Street,$org_address->City,$org_address->StateOrProvince->LookupName,$org_address->PostalCode,$org_address->Country->LookupName);
                $address = str_ireplace("<br/><br/>","<br/>",$address); 
                    }
                    
            }
        $srd_arr['incident'] = $incident;
        $srd_arr['contact'] = $contact;
        $srd_arr['address'] = $address;
        
        return $srd_arr;
    }
    
    function getIncident($i_id)
    {
        if(!$i_id)
            return;
        $result = array();
        $incident = RNCPHP\Incident::fetch($i_id);
        $result['inc'] = $incident;
        return $result; 
    }
    
    function getIncidentThreads($i_id)
    {
        if(!$i_id)
            return;
        $result = array();
        $incident = RNCPHP\Incident::fetch($i_id);
        $result['threads'] = $incident->Threads;
        return $result; 
    }
    
    function get_local_time($tServerTime, $zone)
    {
        $dtServer = new DateTime(Date("Y-m-d H:i:s", $tServerTime));        
        $dtServer->setTimezone(new DateTimeZone($zone));    
        $sLocal = $dtServer->format('Y-m-d H:i:s');
        $tLocalTime = strtotime($sLocal);
        
        return $tLocalTime;
    }
    
    function check_and_set_local_time($i_id)
    {   
        $inc = RNCPHP\Incident::fetch( $i_id, RNCPHP\RNObject::VALIDATE_KEYS_OFF ); 
        if (is_object($inc->Organization))
        {
            $zone = $inc->Organization->CustomFields->ek_timezone;
            if ($zone)
            {
                $zones = timezone_identifiers_list();       
                if (in_array($zone, $zones))
                {                               
                    if (!$inc->CustomFields->ek_inc_create_local_time)
                        {                   
                                                        logmessage("update cust local time zone $zone");
                            $inc->CustomFields->ek_inc_create_local_time = get_local_time($inc->CreatedTime, $zone);
                            $inc->save(RNCPHP\RNObject::SuppressAll);               
                            RNCPHP\ConnectAPI::commit();
                        }   
                }
            }
        }
    }
  function check_org_comm_pref($org_id, $type) {
    $allowed_flag = true;
    $query_org = sprintf("select ID from CIH.ek_org_comm_optout where org_id = %d and communication_type = %d", $org_id,$type);
    logmessage("check_org_comm_pref:: $query_org");
    $org_comm_result = RNCPHP\ROQL::query($query_org)->next();
    if($row2 = $org_comm_result->next()) {
      $allowed_flag = false;
      logmessage("found opt out for $type");
    }
    return $allowed_flag;
  }
  function lookup_type_byname($type) {
    $comm_type_id = 0;
    $query_type = sprintf("select ID from CIH.ek_comm_type where name = '%s'", $type);
    $type_result = RNCPHP\ROQL::query($query_type)->next();
    while($row = $type_result->next()) {
      $comm_type_id = $row['ID'];
    }
    return $comm_type_id;
  }
}
