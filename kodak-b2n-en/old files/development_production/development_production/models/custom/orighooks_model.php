<?php
use RightNow\Connect\v1_1 as RNCPHP;
require_once(get_cfg_var('doc_root').'/include/ConnectPHP/Connect_init.phph');
initConnectAPI();


class Hooks_model extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function mypre_login(&$arr)
    {
        $login = $this->input->post("login");
        logmessage("mypre_login - URI Parms string:: ". $_SERVER['REQUEST_URI']);


        $str = urldecode($_SERVER['REQUEST_URI']);

        $str2 = strtr(urldecode($str), array('&amp;'=>"&"));

        if (strpos($str2, 'blob.blob') !== false) {
           $patt_blob = "/.openid\.blob\.blob=(.+?)\&openid/";
			preg_match_all($patt_blob, $str2, $mb);
			$blob = json_decode($mb[1][0]);
			logmessage(sprintf("Blob Display Name: %s, Email: %s, Country: %s, FN: %s, LN: %s, Lang: %s", $blob->displayName, $blob->email,
                                                                                                 $blob->address->country,
                                                                                                 $blob->name->givenName,
                                                                                                 $blob->name->familyName,
                                                                                                 $blob->language));
        }																								 

         
        try
        {
            $contact = RNCPHP\Contact::first("Login = '".$login."'");

            $org_id=$contact->Organization->ID;
			

        }catch(RNCPHP_CO\ConnectAPIError $err)
        {
            $err->getMessage();
            logmessage($err->getMessage());
        }

        if ($org_id == null)
            return;
        
        if(($org_id == 115) || ($org_id == 116) || ($org_id == 117))
            return "The username or password you entered is incorrect or your account has been disabled or you do not have access to this site. ";
        else
            return;
    }

    public function after_login(&$arr)
    {

    $str = urldecode($_SERVER['REQUEST_URI']);

// need to put some code in here to handle when theres no blob.blob in the request		

    $str2 = strtr(urldecode($str), array('&amp;'=>"&"));
    if (strpos($str2, 'blob.blob') !== false) {
	    $blobfound = 'yes';
	}	
	else {
	$blobfound = 'no';
	$get_array = array();
	parse_str($str2);
//Array  [openid_ext1_value_contact_email] => patrick.dunn@kodak.com [openid_ns_sreg] => http://openid.net/sreg/1.0 [openid_sreg_email] => patrick.dunn@kodak.com [openid_sreg_nickname] => Patrickbb [openid_sreg_fullname] => Patrickbb Dunnbb [openid_sreg_language] => en [openid_sreg_country] => US [openid_sreg_postcode] => 14650-0404 ) 
        logMessage(sprintf("SREG Display Name: %s, Email: %s, Country: %s, NickName: %s, Lang: %s",$openid_sreg_fullname,
																								$openid_sreg_email,
                                                                                                $openid_sreg_country,
                                                                                                $openid_sreg_nickname,
                                                                                                $openid_sreg_language));
	
	}

 


        $patt_blob = "/.openid\.blob\.blob=(.+?)\&openid/";
        preg_match_all($patt_blob, $str2, $mb);

        $blob = json_decode($mb[1][0]);

        /* blob data elements appear as follows:
          $blob->displayName,
          $blob->email,
          $blob->address->country,
          $blob->name->givenName,
          $blob->name->familyName,
          $blob->language
        */

        try
        {

          $lang = array();
          $lang['EN'] = "English (EN)";
          $lang['FR'] = "French (FR)";
          $lang['IT'] = "Italian (IT)";
          $lang['ES'] = "Spanish (ES)";
          $lang['DE'] = "German (DE)";
          $lang['NL'] = "Dutch (NL)";

       
          $country_lbl = "";
          if ($blobfound == 'yes') {
			if($blob->address->country != "") {
					$country_res = RNCPHP\ROQL::queryObject(sprintf("SELECT country from country where lookupname = '%s'",$blob->address->country))->next();
					while($country_lu = $country_res->next()) {
					$country_lbl = $country_lu->Name;
				}
				logMessage("country_lbl is ".$country_lbl);			
			}
		  }
		  else {  //sreg code
				if($openid_sreg_country != "") {
					$country_res = RNCPHP\ROQL::queryObject(sprintf("SELECT country from country where lookupname = '%s'",$openid_sreg_country))->next();
					while($country_lu = $country_res->next()) {
					$country_lbl = $country_lu->Name;
				}
				logMessage("country_lbl is ".$country_lbl); 			
			}
		  }
            $contactID = $arr['returnValue']->c_id->value;

            $contact = RNCPHP\Contact::fetch(intval($contactID));
            $org_id=$contact->Organization->ID;
			 
//logMessage("Org ID is ".$org_id);			
			$contact->Emails = new RNCPHP\EmailArray();
			$contact->Emails[0] = new RNCPHP\Email();
			$contact->Emails[0]->AddressType=new RNCPHP\NamedIDOptList();
			$contact->Emails[0]->AddressType->LookupName = "Email - Primary";
            if ($blobfound == 'yes') {
				$contact->Emails[0]->Address = $blob->email;
            }
			else  {   // sreg code
				$contact->Emails[0]->Address = $openid_sreg_email;
			}          

            if(!is_null($contact)) {
            if ($blobfound == 'yes') {
              $contact->Name->First = $blob->name->givenName;
              $contact->Name->Last = $blob->name->familyName;

              $contact->Phones = new RNCPHP\PhoneArray();

              $officephone = new RNCPHP\Phone();
              $officephone->PhoneType = new RNCPHP\NamedIDOptList();
              $officephone->PhoneType->LookupName = "Office Phone";
              $officephone->Number = $blob->phoneNumber; 
              $contact->Phones[] = $officephone;

              $contact->CustomFields->ek_phone_extension = $blob->phoneExtension;

              if($country_lbl != "") {
                $contact->CustomFields->ek_country_safe_harbor =  new RNCPHP\NamedIDLabel();  // 'United Kingdom (GB)';  // 246;  //$blob->address->country
                    $contact->CustomFields->ek_country_safe_harbor->LookupName = $country_lbl; 
              }	
	          else
			  {
			  if ($blob->address->country == "UK") {
                 $contact->CustomFields->ek_country_safe_harbor->LookupName = "United Kingdom (GB)"; 
                } 
			  }

              if($blob->language != "") {
//logmessage(	"language is ".$blob->language);			

                $contact->CustomFields->ek_lang_pref1 =  new RNCPHP\NamedIDLabel();
				if (($blob->language == 'en') ||  ($blob->language == 'fr') || ($blob->language == 'it') || ($blob->language == 'es') || ($blob->language == 'de') || ($blob->language == 'nl') )  {
				   $contact->CustomFields->ek_lang_pref1->LookupName = $lang[strtoupper($blob->language)]; 
				}	
				else {
//logmessage(	"hit default");			
				   $contact->CustomFields->ek_lang_pref1->LookupName = $lang["EN"]; 
				}
              }
			  
	        } else
			{  //sreg
			  $parts = explode(' ', $openid_sreg_fullname);
			  $firstName = $parts[0];
			  $surname = $parts[1];
              $contact->Name->First = $firstName;
              $contact->Name->Last = $surname;
              if($country_lbl != "") {
                $contact->CustomFields->ek_country_safe_harbor =  new RNCPHP\NamedIDLabel();  // 'United Kingdom (GB)';  // 246;  //$blob->address->country
                    $contact->CustomFields->ek_country_safe_harbor->LookupName = $country_lbl; 
              }	
	          else
			  {
			  if ($openid_sreg_country == "UK") {
                 $contact->CustomFields->ek_country_safe_harbor->LookupName = "United Kingdom (GB)"; 
                } 
			  }
              if($openid_sreg_language != "") {
//logmessage(	"language is ".$openid_sreg_language);			
                $contact->CustomFields->ek_lang_pref1 =  new RNCPHP\NamedIDLabel();
				if (($openid_sreg_language == 'en') ||  ($openid_sreg_language == 'fr') || ($openid_sreg_language == 'it') || ($openid_sreg_language == 'es') || ($openid_sreg_language == 'de') || ($openid_sreg_language == 'nl') )  {
				   $contact->CustomFields->ek_lang_pref1->LookupName = $lang[strtoupper($openid_sreg_language)]; 
				}	
				else {
//logmessage(	"hit default");			
   				   $contact->CustomFields->ek_lang_pref1->LookupName = $lang["EN"]; 
				}
			}
			
			}
		if (is_null($org_id)) {	
          $contact->Organization=RNCPHP\Organization::fetch(696271);
           }

              $contact->save();  
                         
              logmessage("updated contact ".$contactID."  called.");
            }

           

        }catch(RNCPHP\ConnectAPIError $err)
        {
            $err->getMessage();
            logmessage('Error Updating Contact '.$blob->email. ' :'.$err);
        }

    }

	public function mypre_report_get(&$arr)
	{
		if($arr['data']['reportId'] == 100902)
		{
		//print_r($arr);
            if(!$arr['data']['filters']['org'])
            {
                $this->CI = & get_instance();
                $this->CI->load->model("custom/custom_org_model");
                $enhanced_customer_type = $this->CI->custom_org_model->getDirectPartnerTypeEnhanced($this->CI->session->getProfileData('org_id'));
                
                $partner_type_list = $this->CI->custom_org_model->getPartnerTypeList($this->CI->session->getProfileData('org_id'));

                $partner_types_array = array("direct","direct_plus_one","one_non_direct");
                $value;
                $search_type;
                
                if($enhanced_customer_type == 'direct')
                {
                    $value = $this->CI->session->getProfileData('org_id');
                    $search_type = "incidents.org_id";
                }
                
                //If direct plus one or one non direct then the filter need to change
                if($enhanced_customer_type == 'direct_plus_one' || $enhanced_customer_type == 'one_non_direct' )
                {
                    $value = count($partner_type_list) > 1 ? $partner_type_list[1]["ID"] :  $partner_type_list[0]["ID"];
                    $search_type = "incidents.org_id";
                }
                
                //If there are multiple then send in an org id that will result in 0 records.
                if($enhanced_customer_type == 'direct_plus_multiple' || $enhanced_customer_type == 'multiple_non_direct' )
                {
                    $value = 0;
                    $search_type = "incidents.org_id";
                }
            
                if(in_array($enhanced_customer_type,$partner_types_array))
                {
                    $arr['data']['filters']['org']->filters->fltr_id = 14;
                    $arr['data']['filters']['org']->filters->oper_id = 1;
                    $arr['data']['filters']['org']->filters->data = $value;
                    $arr['data']['filters']['org']->filters->val = $value;
                    $arr['data']['filters']['org']->filters->rnSearchType = $search_type; 
                    
                    //$arr['data']['filters']['org']->filters->fltr_id = $search_type;
                    //$arr['data']['filters']['org']->filters->oper_id = 1;
                    //$arr['data']['filters']['org']->filters->data = $value;
                    //$arr['data']['filters']['org']->filters->val = $value;
                    //$arr['data']['filters']['org']->filters->rnSearchType = 'org'; 
                    //$arr['data']['filters']['org']->filters->report_id = $arr['data']['reportId'];
                }            

            }
		}
	}
}
