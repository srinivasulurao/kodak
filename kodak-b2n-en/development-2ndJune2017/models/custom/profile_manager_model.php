<?php
namespace Custom\Models; 
use RightNow\Connect\v1_2 as RNCPHP;
//require_once( get_cfg_var( 'doc_root' ).'/include/ConnectPHP/Connect_init.phph' );
//initConnectAPI();
use RightNow\Utils\Connect,
    RightNow\Utils\Framework,
    RightNow\Utils\Text,
    RightNow\Utils\Config,
    RightNow\Api;

class Profile_manager_model extends \RightNow\Models\Base
{
    function __construct()
    {
        parent::__construct();
    }
    
    public function getContact(){
        $ci=&get_instance();
        $profile = $ci->session->getProfile();
        $cid=$profile->c_id->value;
		if($cid){
        $contact=RNCPHP\Contact::fetch($cid);
        return $contact;
		}
		else
		return 0;
    }
	
	public function getProduct($prod_id){
		if($prod_id){
			return RNCPHP\ServiceProduct::fetch($prod_id);
		}
		else
			return 0;
	}
	
	public function getCategory($cat_id){
		if($prod_id){
			return RNCPHP\ServiceCategory::fetch($cat_id);
		}
		else
			return 0;
	}
		

    public function saveProfileData($formData){
       $data=$this->processFields($formData);
       $profile = $this->CI->session->getProfile();
       $cid=$profile->c_id->value;
       //$org_id=$profile->org_id->value;

     

       try{
       $contact=RNCPHP\Contact::fetch($cid);
       
       if(1)
           $contact->CustomFields->c->prod_id=(int)$data['Incident.Product']->value;
       if(1)
           $contact->CustomFields->c->cat_id=(int)$data['Incident.Category']->value;
       if(1)
           $contact->CustomFields->c->search_text=$data['Contact.CustomFields.c.search_text']->value;
       if($data['Contact.CustomFields.c.search_type']->value)
           $contact->CustomFields->c->search_type=(int)$data['Contact.CustomFields.c.search_type']->value;
       if($data['Contact.CustomFields.c.lines_per_page']->value)
           $contact->CustomFields->c->lines_per_page=$data['Contact.CustomFields.c.lines_per_page']->value;
       if($data['Contact.Name.First']->value)
           $contact->Name->First=$data['Contact.Name.First']->value;
       if($data['Contact.Name.Last']->value)
           $contact->Name->Last=$data['Contact.Name.Last']->value;
       if($data['Contact.Emails.PRIMARY.Address']->value)
           $contact->Emails->Primary->Address=$data['Contact.Emails.PRIMARY.Address']->value;
       if($data['Contact.Address.Street']->value)
           $contact->Address->Street=$data['Contact.Address.Street']->value;
       if($data['Contact.Address.City']->value)
           $contact->Address->City=$data['Contact.Address.City']->value;
       if($data['Contact.Address.Country']->value)
           $contact->Address->Country=RNCPHP\Country::fetch($data['Contact.Address.Country']->value);
       if($data['Contact.Address.StateOrProvince']->value)
           $contact->Address->StateOrProvince->ID=$data['Contact.Address.StateOrProvince']->value;
       if($data['Contact.Address.PostalCode']->value)
           $contact->Address->PostalCode=$data['Contact.Address.PostalCode']->value;
       if($data['Contact.Phones.HOME.Number']->value){
		   
		    $contact->Phones[0] = new RNCPHP\Phone();
            $contact->Phones[0]->PhoneType = new RNCPHP\NamedIDOptList();
            $contact->Phones[0]->PhoneType->LookupName = 'Home Phone'; 
            $contact->Phones[0]->Number = $data['Contact.Phones.HOME.Number']->value;
			
	   }
           
       if($data['Contact.Phones.OFFICE.Number']->value){
		   
		    $contact->Phones[1] = new RNCPHP\Phone();
            $contact->Phones[1]->PhoneType = new RNCPHP\NamedIDOptList();
            $contact->Phones[1]->PhoneType->LookupName = 'Office Phone';
            $contact->Phones[1]->Number = $data['Contact.Phones.OFFICE.Number']->value;
	   }
           
       if($data['Contact.Phones.MOBILE.Number']->value){

		    $contact->Phones[2] = new RNCPHP\Phone();
            $contact->Phones[2]->PhoneType = new RNCPHP\NamedIDOptList();
            $contact->Phones[2]->PhoneType->LookupName = 'Mobile Phone';
            $contact->Phones[2]->Number = $data['Contact.Phones.MOBILE.Number']->value;
			
	   }
         
       $contact->save();
       RNCPHP\ConnectAPI::commit();

       $arr=array();
       $arr['result']['transaction']['contact']['key']="";
       $arr['result']['transaction']['contact']['value']="$cid";
       $arr['result']['sessionParam']="";

       }
       catch(Exception $e){
        echo $e->getMessage(); 
       }

       return json_encode($arr);

    }

    private function debug($ao){
        echo "<pre>";
        print_r($ao);
        echo "</pre>";
    }

    private function processFields(array $fields, &$presentFields = array()) {
        $return = array();

        foreach ($fields as $field) {
            $fieldName = $field->name;

            if (!is_string($fieldName) || $fieldName === '') continue;

            unset($field->name);
            $return[$fieldName] = $field;

            if ($objectName = strtolower(Text::getSubstringBefore($fieldName, '.'))) {
                $presentFields[$objectName] = true;
            }
        }

        return $return;
    }


} //Class Ends here.