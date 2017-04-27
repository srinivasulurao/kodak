<?php
namespace Custom\Widgets\CIHFunction;

class PartnerTypeListSearch extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
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
		$filter_options = array();
        $org_id = $this->CI->session->getProfileData('org_id');
        if(!$org_id)
			return;
		$partnerTypes = array();
		
		$customer_type = $this->CI->model('custom/custom_org_model')->getDirectPartnerType($org_id);
        $this->data['js']['org_id'] = $org_id;
		$this->data['js']['customer_type'] = $customer_type;
        $partners= $this->CI->model('custom/custom_org_model')->getPartnerTypeList($org_id);
		$partnerTypes = array_merge($partnerTypes,$partners);
		$enhanced_partner_type = $this->CI->model('custom/custom_org_model')->getDirectPartnerTypeEnhanced($org_id);        
        foreach($partnerTypes as $key=>$partner)
			{
        	switch($partner['Type'])
				{
					case $cih_lang_msg_base_array["direct"]:
						$partnerTypes[$key]['ID'] = 'direct';
					case 'Enabling Partner':
					$filter_options[] = array('partner_type'=>'Enabling Partner','partner_type_id'=>$partner['ID'],'filter_id'=>13,'oper_id'=>1);
					break;					
					case 'Manufacturing Partner':
					$filter_options[] = array('partner_type'=>'Manufacturing Partner','partner_type_id'=>$partner['ID'],'filter_id'=>14,'oper_id'=>1);
					break;					
					case 'Service Distributor':
					$filter_options[] = array('partner_type'=>'Service Distributor','partner_type_id'=>$partner['ID'],'filter_id'=>15,'oper_id'=>1);
					break;					
					case 'Service Reseller':
					$filter_options[] = array('partner_type'=>'Service Reseller','partner_type_id'=>$partner['ID'],'filter_id'=>16,'oper_id'=>1);
					break;
					case $cih_lang_msg_base_array["corporate"]:
//logMessage('hit switch on Corporate');					
					$filter_options[] = array('partner_type'=>$cih_lang_msg_base_array["corporate"],'partner_type_id'=>$partner['ID'],'filter_id'=>18,'oper_id'=>1);
					break;
					
				}
			}
    switch($enhanced_partner_type){
			case 'direct':
			case 'one_non_direct':
				$partnerTypes[0]['selected'] = 'selected';
				break;
			case 'direct_plus_one':
				$partnerTypes[1]['selected'] = 'selected';
				break;
			
			case 'direct_plus_multiple':
			case 'direct_plus_multiple':
				$partnerTypes[0]['selected'] = 'multiple';	
			
		}
        $this->data['js']['partner_filters'] = $filter_options;
		$this->data['partnertypes'] = $partnerTypes;
		  
     $filter_options[] = array('partner_type'=>'Corporate','partner_type_id'=>$partner['ID'],'filter_id'=>18,'oper_id'=>1);
                //end testing new code
       
		$i = 0;

		while ($i < count($partnerTypes))
		{
//		logmessage ('in loop setting defaultFilter partnerTypes selected=te'.$partnerTypes[$i]['selected']);	
			if($partnerTypes[$i]['selected'] == 'selected')
			{
				$this->data['js']['defaultFilter'] =$partnerTypes[$i]['ID'];
				break;
			}
			if($partnerTypes[$i]['selected'] == 'multiple')
			{
				$this->data['js']['defaultFilter'] = 0;
				break;
			}

                        $i++;			
		}
        return parent::getData();
    }
}