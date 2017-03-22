<?php

use RightNow\Connect\v1 as RNCPHP;

require_once( get_cfg_var("doc_root")."/include/ConnectPHP/Connect_init.phph" );

class custom_org_model extends Model
{
	private $cacheHandlePrefix = 'org';
	
    function __construct()
    {
        parent::__construct();
		
		$CI = &get_instance();
		
		$this->CI = $CI;

    }
	
	function getPartnerTypeList($org_id)
	{
		// get the enhanced partner type list to determine 
		//what to select by default
		
		$enhanced_partner_type = $this->getDirectPartnerTypeEnhanced($org_id);
	        //$enhanced_partner_type = 'direct';

                logmessage("org_id = " . $org_id . "  getPartnerTypeList = " .$enhanced_partner_type);	
		$partner_type_list = array();
		
		if(!$org_id)
			return $partner_type_list;
		
		$org = RNCPHP\Organization::fetch($org_id);
	
		
		if($org->CustomFields->ek_customer_sapid)		
			$partner_type_list[] = array('ID'=>$org->CustomFields->ek_customer_sapid,'Type'=>'Direct');

                //2012.07.11 scott harris: the label is what we match on to map to the coded value, so these should not change
		if($org->CustomFields->ek_enabling_partner)  //ZENABPRN
		$partner_type_list[] = array('ID'=>$org->CustomFields->ek_enabling_partner,'Type'=>'Enabling Partner');	
		
		if($org->CustomFields->ek_mvs_manfacturer)  //ZMVSMFG
		$partner_type_list[] = array('ID'=>$org->CustomFields->ek_mvs_manfacturer,'Type'=>'Manufacturing Partner');
		
		if($org->CustomFields->ek_service_dist)  //ZSVCDIST
		$partner_type_list[] = array('ID'=>$org->CustomFields->ek_service_dist,'Type'=>'Service Distributor');
		
		if($org->CustomFields->ek_service_reseller)  //ZSVCRESL
		$partner_type_list[] = array('ID'=>$org->CustomFields->ek_service_reseller,'Type'=>'Service Reseller');
	
		if($org->CustomFields->ek_corporate)  //ZCORPACC
		$partner_type_list[] = array('ID'=>$org->CustomFields->ek_corporate,'Type'=>'Corporate');
	
		switch($enhanced_partner_type){
			case 'direct':
			case 'one_non_direct':
			$partner_type_list[0]['selected'] = 'selected';
			break;
			case 'direct_plus_one':
                                logmessage("found dir+1");
                                logmessage("count types = " .count($partner_type_list));
				$partner_type_list[1]['selected'] = 'selected';
				break;
				
			case 'direct_plus_multiple':
			case 'direct_plus_multiple':
				$partner_type_list[0]['selected'] = 'multiple';	
			
			}

		return $partner_type_list;	
				
	}
	
	function getDirectPartnerType($org_id)
	{
		$partner_type;
		if(!$org_id)
			return;
		$org = RNCPHP\Organization::fetch($org_id);
		
		if($org->CustomFields->ek_customer_sapid)		
		{
			$partner_type = 'direct';
		}

		if(!$org->CustomFields->ek_sap_payer_id)
			{
			if($org->CustomFields->ek_enabling_partner || $org->CustomFields->ek_mvs_manfacturer || $org->CustomFields->ek_service_dist || $org->CustomFields->ek_service_reseller || $org->CustomFields->ek_corporate)
				$partner_type = 'non_direct';			
			}
			
		return $partner_type;			
	
	}
	
	function getDirectPartnerTypeEnhanced($org_id)
	{
		$partner_type;
		if(!$org_id)
			return;
                $partner_type = 'direct';  //2012.10.11 scott harris: just return this while debugging


		$org = RNCPHP\Organization::fetch($org_id);
		
		if($org->CustomFields->ek_customer_sapid)		
		{
			
			$non_count = 0;
			if($org->CustomFields->ek_enabling_partner)
				++$non_count;
			if($org->CustomFields->ek_mvs_manfacturer)
				++$non_count;
			if($org->CustomFields->ek_service_dist)
				++$non_count;
			if($org->CustomFields->ek_service_reseller)
				++$non_count;
			if($org->CustomFields->ek_corporate)
				++$non_count;
			
			if($non_count == 0)
				$partner_type = 'direct';
			
			if($non_count == 1)
				$partner_type = 'direct_plus_one';
			
			if($non_count > 1)
				$partner_type = 'direct_plus_multiple';
			
		}

		if(!$org->CustomFields->ek_sap_payer_id)
		{
			if($org->CustomFields->ek_enabling_partner || $org->CustomFields->ek_mvs_manfacturer || $org->CustomFields->ek_service_dist || $org->CustomFields->ek_service_reseller || $org->CustomFields->ek_corporate)
				$partner_type = 'non_direct';
			$non_count = 0;
			if($org->CustomFields->ek_enabling_partner)
				++$non_count;
			if($org->CustomFields->ek_mvs_manfacturer)
				++$non_count;
			if($org->CustomFields->ek_service_dist)
				++$non_count;
			if($org->CustomFields->ek_service_reseller)
				++$non_count;
			if($org->CustomFields->ek_corporate)
				++$non_count;
			if($non_count == 1)
				$partner_type = 'one_non_direct';
			if($non_count > 1)
				$partner_type = 'multiple_non_direct';				
		}
		
		return $partner_type;			
		
	}
	
	
}
