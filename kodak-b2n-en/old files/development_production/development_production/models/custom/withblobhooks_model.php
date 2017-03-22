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
        //logmessage("mypre_login - URI Parms string:: ". $_SERVER['REQUEST_URI']);


        $str = urldecode($_SERVER['REQUEST_URI']);

        $str2 = strtr(urldecode($str), array('&amp;'=>"&"));

        $patt_blob = "/.openid\.blob\.blob=(.+?)\&openid/";
        preg_match_all($patt_blob, $str2, $mb);

        $blob = json_decode($mb[1][0]);

        logmessage(sprintf("Display Name: %s, Email: %s, Country: %s, FN: %s, LN: %s, Lang: %s", $blob->displayName, $blob->email,
                                                                                                 $blob->address->country,
                                                                                                 $blob->name->givenName,
                                                                                                 $blob->name->familyName,
                                                                                                 $blob->language));

         
        try
        {
            $contact = RNCPHP\Contact::first("Login = '".$login."'");

            $org_id = $contact->Organization->ID;

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

        $str2 = strtr(urldecode($str), array('&amp;'=>"&"));

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

          if($blob->address->country != "") {
            $country_res = RNCPHP\ROQL::queryObject(sprintf("SELECT country from country where lookupname = '%s'",$blob->address->country))->next();

            while($country_lu = $country_res->next()) {
              $country_lbl = $country_lu->Name;
            }

          }
            $contactID = $arr['returnValue']->c_id->value;

            $contact = RNCPHP\Contact::fetch(intval($contactID));
            $org_id = $contact->Organization->ID;

            $contact->Emails = new RNCPHP\EmailArray();
            $contact->Emails[0] = new RNCPHP\Email();
            $contact->Emails[0]->AddressType=new RNCPHP\NamedIDOptList();
            $contact->Emails[0]->AddressType->LookupName = "Email - Primary";
            $contact->Emails[0]->Address = $blob->email;

            if(!is_null($contact)) {

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

              if($blob->language != "") {

                $contact->CustomFields->ek_lang_pref1 =  new RNCPHP\NamedIDLabel();
                $contact->CustomFields->ek_lang_pref1->LookupName = $lang[strtoupper($blob->language)]; 
              }
	          if (is_null($org_id)) {	
				$contact->Organization = RNCPHP\Organization::fetch(696271);			  
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
