<?php
use RightNow\Connect\v1 as RNCPHP;
use RightNow\Connect\v1\SAP_TEMP as RNCPHP_CO;

require_once( get_cfg_var("doc_root")."/include/ConnectPHP/Connect_init.phph" );
initConnectAPI();
require_once(get_cfg_var('doc_root').'/custom/nusoap.php');


load_curl();

class Ibase_Info_model extends Model
{

	function __construct()
    {
        parent::__construct();
        //This model would be loaded by using $this->load->model('custom/Sample_model');
		initConnectAPI();
    }
	
    function get_ibase_details()
    {
		$kodak_integ = $this->get_kodak_info();
        $arr_entitlement = array();
                         
        try
        {
			$profile = $this->session->getProfile();
            $langId = lang_id();
            $interface= intf_id();

            //2012.05.11 scott harris: added non support plans and contract types			 
 	    $support_plans = array(ZSPQ, ZWRC, ZSPC, ZSPP, ZPWI, ZISC, ZMSI, ZAPF);  //compare to ZZ_ProcTypeCode
            $non_support_plans = array(ZSCQ, ZSPQ, ZOSQ, ZRMQ, ZPCQ, ZPMQ, ZDNQ, ZDRQ, ZWPQ, ZRPQ, ZWFQ, ZPSQ);
            $contract_types = array(ZPWC, ZWC, ZSCC, ZPOS); //compare to ZZ_ServTranType
			 
	    $profile_determined = 0;
			
	    $sap_cust_id = "";
            $org = RNCPHP\Organization::fetch($profile->org_id->value);
            $sap_cust_id = $org->CustomFields->ek_customer_sapid;
            
            //url to access wsdl service
                        //production:
			//$url = "https://bcp.kodak.com:443/ws/Z_IC02528_QueryInstallPointCustom:QueryInstallPointCustom?wsdl";
			
            //testing:
            //$url = "https://bcp.kodak.com:443/ws/Z_IC02528_QueryInstallPointCustom:QueryInstallPointCustom?wsdl";

			$url = $kodak_integ['Z_IC02528_IBaseGetDetail'];
			
			//setup the wsdl client
			$client = new soapclient($url, true, false, false, false, false, 0, 120, 'HTTPS_Port');
			$client->certRequest['sslcertfile'] = "";
			$client->certRequest['passphrase'] = '';
			$client->certRequest['sslkeyfile'] = '';
			$client->certRequest['verifypeer'] = 0;
			$client->certRequest['verifyhost'] = 0;
			$client->soap_defencoding = "UTF-8";
                        //production:
			$client->setCredentials($kodak_integ['user'], $kodak_integ['pw'], "basic");
		        //$client->setCredentials("RNTQualUser", "summer20!!", "basic");	
                        //testing:
                        //$client->setCredentials("RNTDevUser", "Spr!ng2011", "basic");
			
			$client->setDebugLevel( 3 );

			$req_data = array('ZZ_PartnerID' =>$sap_cust_id,'ZZ_DateRequest' => "10132011");
		
			$ret_data = $client->call('SI_OUT_SYN_Z_IC02528IBaseGetDetail', array('MT_IBaseGetDetail_Input'=>$req_data));
			$ret_data = array('MT_IBaseGetDetail_Output' => $ret_data);

			if ($err) {  
				// Display the error  
				echo '<p><b>Constructor error: ' . $err . '</b></p>';  
				// At this point, you know the call that follows will fail  
			}  
			  
			// Check for a fault  
			//print_r($result[InstallationPoint][AllComponents]);
						
			if ($client->fault) {  
				echo '<h2>Fault</h2><pre>';  
				print_r($result);  
				echo '</pre>';  
			} else {  
				// Check for errors  
				$err = $client->getError();  
				if ($err) {  
					// Display the error  
					echo '<h2>Error</h2><pre>' . $err . '</pre>';  
				} 
			}  
 
			 //header title
			 $dataset['headers'][0]["heading"] = "K#";
			 $dataset['headers'][1]["heading"] = "SN";
			 $dataset['headers'][2]["heading"] = "Product Description";
			 $dataset['headers'][3]["heading"] = "Alias";
			 $dataset['headers'][4]["heading"] = "Support Plan";
			
			$ibase_num = 0;

            //build array of mainframes and sub components			
			$equip = array();
				
			//foreach($result[InstallationPoint][AllComponents] as $component) {  
			foreach($ret_data[MT_IBaseGetDetail_Output][AllComponents][ArrayOfAllComponentsItem] as $component) {

			   // print_r($component);
				
 				if($component[ZZ_MF_ind] == 'Y') { 
					$equip[$component[InstallationPointID]] = array('name'=>$component[IBCompObj][ProductDescription]); 
			  	}	 
			
			  	if($component[ZZ_MF_ind] == 'N') { 
					$equip[$component[ParentInstallationPointID]]['sub'][] = array('name'=>$component[IBCompObj][ProductDescription]); 
			  	} 
			} 
 						 
			foreach($ret_data[MT_IBaseGetDetail_Output][AllComponents][ArrayOfAllComponentsItem] as $comp) {
/* for debugging			  
			  print("--------------------------------------------</br>"); 
			  print("K#: " . $comp[InstallationPointIdentificationID]."</br>"); 
			  print("equipment: " .$comp[InstallationPointName]."</br>"); 
			  print("Alias: " . $comp[ZZ_EquipAlias]."</br>"); 
			  print("mainframe: " . $comp[ZZ_MF_ind]."</br>"); 
			  print("SN: " . $comp[IBCompObj][ZZ_SerialNbr] ."</br>"); 
			  print("Comp ID: " . $comp[InstallationPointID] ."</br>"); 
			  print("RNT Prod ID: " . ltrim($comp[ZZ_RNTProdID],"0") ."  raw'".$comp[ZZ_RNTProdID]."'"."</br>"); 
			  print("Material #: " . $comp[IBCompObj][IndividualMaterialInternalID]."</br>");
			  print("--------------------------------------------</br>"); 
*/
			 $prodHier = "";			  

             if($comp[ZZ_RNTProdID] != '') {  
			   $prod = new RNCPHP\ServiceProduct();
			   $queryResult = RNCPHP\ROQL::queryObject("select serviceproduct from serviceproduct where Descriptions.Language.ID = 1 and id =".ltrim($comp[ZZ_RNTProdID],"0"))->next();
			   $prod = $queryResult->next();
			
			   //$prodName = "find by lu on " .ltrim($comp[ZZ_RNTProdID],"0") . " def to " . $comp[InstallationPointName];
			   $prodName = $prod->Descriptions[0]->LabelText == "" ? $comp[InstallationPointName] : $prod->Descriptions[0]->LabelText;
						
			   $prodHier = "";
	 
			   foreach($prod->ProductHierarchy as $hier) {
					$prodHier .=  $hier->ID . ",";
			   }
			   $prodHier = rtrim($prodHier, ",") . "," . $prod->ID;
			 }
			 else
			   $prodName = $comp[IBCompObj][ProductDescription];
			 
			 $dataset['data'][$ibase_num][0]= $comp[InstallationPointIdentificationID];
			 

			 //$dataset['data'][$ibase_num][1]= "<a href=\"/app/ask/p/0\">".$comp[InstallationPointName]."</a>";
			 //$dataset['data'][$ibase_num][1]= "<a href=\"/app/ask/p/".$prodHier."\">".$prodName."</a>";
			 
			 //new
			 $subs = $equip[$comp[InstallationPointID]]['sub']; 
			 
			 $sublist = "";
			   	 			 			 
			 foreach($subs as $subcomp) { 
		     	$sublist .= $subcomp['name'].'</br>'; 
  			 } 
			 //end new
			 
			 $dataset['data'][$ibase_num][1]= $comp[IBCompObj][ZZ_SerialNbr];			 
			 $dataset['data'][$ibase_num][3]= $comp[ZZ_EquipAlias];
			 
			 $hasActiveEntitlement = 0;
			 
			 $def_serv_profile = $comp[ZZ_MatDefServProfID];
			 $def_resp_profile = $comp[ZZ_MatDefRespProfID];

  	      foreach($comp[ComponentContract] as $all_ent) { 

			   foreach($all_ent as $ent) {

/* for debugging
 print("</br>---------------------CONTRACTS -----------------------</br>"); 												 
 print("Description: $ent[ZZ_ServProdDesc]"."</br>");
 print("ID: $ent[BusinessTransactionDocumentID]"."</br>");
 print("type: $ent[ZZ_ProcTypeCode]"."</br>"); 
 print("Service : $ent[ZZ_ServProfileID]"."</br>"); 
 print("Response : $ent[ZZ_ResposeProfile]"."</br>"); 
 print("From: " . $ent[DatePeriodStartDate] . " TO: " . $ent[DatePeriodEndDate]."</br>"); 
 print("--------------------------------------------</br>"); 												 
*/
                          //20120511 scott harris: added check on contract types
			  if(in_array($ent[ZZ_ProcTypeCode], $support_plans) && in_array($ent[ZZ_ServTranType], $contract_types) ) {   //this is a valid support plan				 

                                   //20120511 scott harris: correct calc
                                   $start_diff = time() - strtotime($ent[DatePeriodStartDate]);
                                   $end_diff = time() - strtotime($ent[DatePeriodEndDate]);
  
				   if($start_diff > 0 && $end_diff < 0) {														  
					 $hasActiveEntitlement = 1;
  
					 
				   }
				   
				   $dataset['data'][$ibase_num][4]= $ent[ZZ_ServProdDesc] . "</br><h4>".$ent[DatePeriodStartDate] ." to ". $ent[DatePeriodEndDate]."</h4>";
						  
				 } 
				 else {  //this is a support product
				   
				   if($hasActiveEntitlement) {
					   
					 if(array_key_exists('ZZ_ServProfileID',$ent)) {
						$serv_profile = $ent[ZZ_ServProfileID];	 
					 }
					 else {
						$serv_profile = $def_serv_profile;
					 }
						
					 if(array_key_exists('ZZ_ResposeProfile',$ent)) {
						$resp_profile =  $ent[ZZ_ResposeProfile];
					 }
					 else {
						$resp_profile = $def_resp_profile;
					 }
					 
					 if($comp[ZZ_ServDelvStrg] == "R") {  //remote 
					 
					   if($ent[ZZ_ProcTypeCode] == "ZRMW" || $ent[ZZ_ProcTypeCode] == "ZRMC") {  //remote
						 $comp['sp'] = $serv_profile;
						 $comp['rp'] = $resp_profile;
						 $profile_determined = 1;
                                                 $arr_entitlement[] = array('descr' => $ent[ZZ_ServProdDesc],'sp'=> $serv_profile, 'rp'=> $resp_profile, 'name'=>$ent[ZZ_ServProdDesc]);
					   }
					 }
					 
					 if($comp[ZZ_ServDelvStrg] == "O") {  //onsite
					   if($ent[ZZ_ProcTypeCode] == "ZOSW" || $ent[ZZ_ProcTypeCode] == "ZOSC" || $ent[ZZ_ProcTypeCode] == "ZWRC") {  //onsite
						 $comp['sp'] = "";
						 $comp['rp'] = "";
						 $profile_determined = 1;
					   }
					 }
					 
				   }  //end if active entitlement
				 }  //end the else
			
				 //if($profile_determined)
				  // break;
						
			   }  //end ent foreach
			 }  //end foreach
			 
			 if($hasActiveEntitlement) {
				  $sds = $comp[ZZ_ServDelvStrg] == 'R' ? 40 : 39;  //2011.07.15 Scott Harris: hard-code cf menu choice ids
				  
				  $dataset['data'][$ibase_num][2]= "<a href=\"/app/ask/p/".$prodHier.
				  "/incidents.c\$ek_equip_component_id/".$comp[InstallationPointID].
				  "/incidents.c\$ek_k_number/".$comp[InstallationPointIdentificationID].
				  "/src/ibase/incidents.c\$ek_serial_number/".$comp[IBCompObj][ZZ_SerialNbr].
				  "/incidents.c\$ek_sap_product_id/".$comp[IBCompObj][IndividualMaterialInternalID].
				  "/incidents.c\$ek_sds/".$sds.
                                  "/incidents.c\$ek_sap_soldto_custid/".$sap_cust_id;
				  
				  if(is_array($arr_entitlement[0])) {
                                    $dataset['data'][$ibase_num][2] .= "/incidents.c\$ek_service_profile/".$arr_entitlement[0]['sp'];
                                  }
                                  if(is_array($arr_entitlement[0])) {
                                    $dataset['data'][$ibase_num][2] .= "/incidents.c\$ek_response_profile/".$arr_entitlement[0]['rp'];
                                  }
					
				  $dataset['data'][$ibase_num][2] .= "\"><img src=\"/euf/assets/themes/Kodak/images/chat_send.png\" width=\"20\" height=\"20\"/>&nbsp;&nbsp;".
				   $prodName."-".$comp[InstallationPointIdentificationID]."</a></br><h6>".$sublist."</h6>";
			 }
			 else {
				 $dataset['data'][$ibase_num][2] = "<h4>".$prodName."-".$comp[InstallationPointIdentificationID]."</h4></br><h6>".$sublist."</h6>";
			 }

			 $ibase_num++;
		}   //end foreach
		
		}
		catch ( RNCPHP\ConnectAPIError $err )
	    {
    	    $dataset['exception'] = $err->getMessage();
	    };

		$dataset['data']['equipment'] = array();
		return($dataset);		
  	}

    function get_kodak_info() {
	
	$arr = array();
	
	$result = RNCPHP\ROQL::query("Select ek_ws_user,
							ek_ws_password,
							ek_contact_create_ws_PI,
							ek_contact_update_ws_PI,
							ek_custmaster_create_ws_PI,
							ek_svcorder_confirm_ws_PI,
							ek_svcorder_update_ws_PI,
							ek_svcorder_create_ws_PI,
							ek_sla_dates_ws_PI,
							ek_ibase_ws_wsdl_PI,
							ek_ibase_parent_list_ws_wsdl_P,
							ek_ibase_site_list_ws_wsdl_PI,
							ek_meter_read_create_PI
					from CIH.ek_configuration where ID = 1")->next();
	
	 $my_config = $result->next();
	
	 $arr['user'] = $my_config['ek_ws_user'];
	
	 $arr['pw'] = $my_config[ek_ws_password];
	 $arr['Z_IC02522_ContactCreate'] = $my_config[ek_contact_create_ws_PI];
	 $arr['Z_IC02523ContactUpdate'] = $my_config[ek_contact_update_ws_PI];
	 $arr['Z_IC02536_CreateCustMaster'] = $my_config[ek_custmaster_create_ws_PI];
	 $arr['Z_IC02526_ServiceOrderConfirm'] = $my_config[ek_svcorder_confirm_ws_PI];
	 $arr['Z_IC02519_UpdateServiceOrder'] = $my_config[ek_svcorder_update_ws_PI];
	 $arr['Z_IC02518_CreateServiceOrder'] = $my_config[ek_svcorder_create_ws_PI];
	 $arr['Z_IC02535_GetSLADates'] = $my_config[ek_sla_dates_ws_PI];
	 $arr['Z_IC02528_IBaseGetDetail'] = $my_config[ek_ibase_ws_wsdl_PI];
	 $arr['Z_IC02792_IBaseParentList'] = $my_config[ek_ibase_parent_list_ws_wsdl_P];
	 $arr['Z_IC02792_IBaseList'] = $my_config[ek_ibase_site_list_ws_wsdl_PI];
	 $arr['Z_IC02871_MeterReadCreate'] = $my_config[ek_meter_read_create_PI];
	
      return $arr;
    }	

}


