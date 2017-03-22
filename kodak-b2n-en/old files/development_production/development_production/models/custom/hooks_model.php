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
         
        try
        {
            $contact = RNCPHP\Contact::first("Login = '".$login."'");
//logmessage('in mypre_login for Login :'.$login);
            $org_id=$contact->Organization->ID;
			

        }catch(RNCPHP_CO\ConnectAPIError $err)
        {
            $err->getMessage();
            logmessage($err->getMessage());
        }

//        logmessage('in mypre_login code');
        if ($org_id == null)
            return;
        if(($org_id == 115) || ($org_id == 116) || ($org_id == 117))
            return "The username or password you entered is incorrect or your account has been disabled or you do not have access to this site. ";
        else
            return;
    }

    public function after_login(&$arr)
    {
	$str4="";
	$str5="";
	$str6="";

//logmessage("after_login raw argv: ".$_SERVER['argv']);
	$cntargs = $_SERVER['argc'];
//logmessage('argcnt = '.$cntargs);
    $str = $_SERVER['argv'][0];
//	logmessage("after_login  = argv[0] - string:: ".$str);

    $str3 = $_SERVER['argv'][1];
//	logmessage("after_login  = argv[1]- string:: ".$str3);

    $str4 = $_SERVER['argv'][2];
//	logmessage("after_login  = argv[2]- string:: ".$str4);


    $str5 = $_SERVER['argv'][3];
//	logmessage("after_login  = argv[3]- string:: ".$str5);

	
    $str6 = $_SERVER['argv'][4];
//	logmessage("after_login  = str6- string:: ".$str6);


    $str = $str.'+'.$str3.'+'.$str4.'+'.$str5.'+'.$str6;
//	logmessage("final string :BEGIN".$str."END");


// need to put some code in here to handle when theres no blob.blob in the request		

    $str2 = strtr(urldecode($str), array('&amp;'=>"&"));
	parse_str($str2);
//Array  [openid_ext1_value_contact_email] => patrick.dunn@kodak.com [openid_ns_sreg] => http://openid.net/sreg/1.0 [openid_sreg_email] => patrick.dunn@kodak.com [openid_sreg_nickname] => Patrickbb [openid_sreg_fullname] => Patrickbb Dunnbb [openid_sreg_language] => en [openid_sreg_country] => US [openid_sreg_postcode] => 14650-0404 ) 
        logMessage(sprintf("SREG Display Name: %s, Email: %s, Country: %s, NickName: %s, Lang: %s",$openid_sreg_fullname,
																								$openid_sreg_email,
                                                                                                $openid_sreg_country,
                                                                                                $openid_sreg_nickname,
                                                                                                $openid_sreg_language));

        try
        {

          $lang = array();
          $lang['EN'] = "English (EN)";
          $lang['FR'] = "French (FR)";
          $lang['IT'] = "Italian (IT)";
          $lang['ES'] = "Spanish (ES)";
          $lang['DE'] = "German (DE)";
          $lang['NL'] = "Dutch (NL)";
          $lang['PT'] = "Portuguese (PT)";

       
          $country_lbl = "";
				if($openid_sreg_country != "") {
					$country_res = RNCPHP\ROQL::queryObject(sprintf("SELECT country from country where lookupname = '%s'",$openid_sreg_country))->next();
					while($country_lu = $country_res->next()) {
					$country_lbl = $country_lu->Name;
				}
//				logMessage("sreg country_lbl is ".$country_lbl); 			
			}
			
            $contactID = $arr['returnValue']->c_id->value;

            $contact = RNCPHP\Contact::fetch(intval($contactID));
            $org_id=$contact->Organization->ID;
		if (is_null($org_id)) {				 
//logMessage("Org ID is ".$org_id);			
			$contact->Emails = new RNCPHP\EmailArray();
			$contact->Emails[0] = new RNCPHP\Email();
			$contact->Emails[0]->AddressType=new RNCPHP\NamedIDOptList();
			$contact->Emails[0]->AddressType->LookupName = "Email - Primary";
   			$contact->Emails[0]->Address = $openid_sreg_email;
	
            if(!is_null($contact)) {
			  $parts = explode(' ', $openid_sreg_fullname);
			  $firstName = $parts[0];
			  $surname = $parts[1];
              $contact->Name->First = $firstName;
              $contact->Name->Last = $surname;
              if($country_lbl != "") {
                $contact->CustomFields->ek_country_safe_harbor =  new RNCPHP\NamedIDLabel();  // 'United Kingdom (GB)';  // 246;  //$blob->address->country
                    $contact->CustomFields->ek_country_safe_harbor->LookupName = $country_lbl; 
              }	
			  if ($openid_sreg_country == "UK") {
                 $contact->CustomFields->ek_country_safe_harbor->LookupName = "United Kingdom (GB)"; 
                } 
              if($openid_sreg_language != "") {
//logmessage(	"language is ".$openid_sreg_language);			
//get Code Ignitor Session variable and set it to the language
$CI =& get_instance();  
$CI->session->setSessionData(array("lang" => $openid_sreg_language));
                $contact->CustomFields->ek_lang_pref1 =  new RNCPHP\NamedIDLabel();
				if (($openid_sreg_language == 'en') ||  ($openid_sreg_language == 'fr') || ($openid_sreg_language == 'it') || ($openid_sreg_language == 'es') || ($openid_sreg_language == 'de') || ($openid_sreg_language == 'nl') || ($openid_sreg_language == 'pt')  )  {
				   $contact->CustomFields->ek_lang_pref1->LookupName = $lang[strtoupper($openid_sreg_language)]; 
//logmessage(	"setting sreg language to".$lang[strtoupper($openid_sreg_language)]);			
				}	
				else {
//logmessage(	"hit default");			
   				   $contact->CustomFields->ek_lang_pref1->LookupName = $lang["EN"]; 
				}
			}

          $contact->Organization=RNCPHP\Organization::fetch(696271);
           }

              $contact->save();  
                         
              logmessage("updated contact ".$contactID."  called.");
          } 

        }catch(RNCPHP\ConnectAPIError $err)
        {
            $err->getMessage();
            logmessage('Error Updating Contact '.$openid_sreg_email. ' :'.$err);
        }

    }

	public function mypre_report_get(&$arr)
	{
		if (($arr['data']['reportId'] == 100902)  || ($arr['data']['reportId'] == 101626)  || ($arr['data']['reportId'] == 101656)  || ($arr['data']['reportId'] == 101658)|| ($arr['data']['reportId'] == 101939)|| ($arr['data']['reportId'] == 101941))
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
	function searchArray($myarray, $id) {
    foreach ($myarray as $item) {
        if ($item['id'] == $id)
            return true;
		}
    return false;
	}
    public function checkInternalNote($aid) {

          $internal = "N";
//dev          $report_id = 101667; 
          $report_id = 102034; 
          $ar= RNCPHP\AnalyticsReport::fetch( $report_id);

          $answer_filter= new RNCPHP\AnalyticsReportSearchFilter;
          $answer_filter->Name = 'a_id';

          $answer_filter->Values = array($aid);

          $filters = new RNCPHP\AnalyticsReportSearchFilterArray;
          $filters[] = $answer_filter;

          $result= $ar->run(0, $filters );

          while($row = $result->next()) {
            $internal_note = $row['internal_note'];
          }
//logmessage('internal note = '.$internal_note);
//          if (( $internal_note == "Yes") || ($internal_note == ""))
          if ( $internal_note == "Yes")
            $internal = "Y";

          return $internal;

        }

	public function get_note($aid)
	{
		$CI = get_instance();	
		$CI->load->model('standard/Answer_model');
//		$arr_results = array();
//		$arr_results = $CI->Answer_model->hierDisplayGet($aid, HM_PRODUCTS);
//$md = RNCPHP\Answer::getMetadata();
//var_export( $md );
//logMessage($md);
		$answer = RNCPHP\Answer::fetch($aid,RNCPHP\RNObject::VALIDATE_KEYS_OFF );
		$note = $answer->Comments;
		return $note;
	}

	
}
