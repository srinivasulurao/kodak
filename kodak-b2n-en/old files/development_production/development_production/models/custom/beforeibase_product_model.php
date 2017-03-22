<?php
use RightNow\Connect\v1_1 as RNCPHP;
use RightNow\Connect\v1_1\SAP_TEMP as RNCPHP_CO;


require_once( get_cfg_var("doc_root")."/include/ConnectPHP/Connect_init.phph" );
initConnectAPI();
require_once(get_cfg_var("doc_root")."/custom/nusoap.php");


load_curl();

class Ibase_product_model extends Model
{
    function __construct()
    {
        parent::__construct();
        //This model would be loaded by using $this->load->model('custom/Sample_model');
    }
       
    public function getIbaseList($searchBy, $partner_type, $sapPartnerID, $name, $city, $street, $zip, $country, $region, $matl_id, $ibasePartnerID, $internal_contact="N") { 

      $results_array = array();

      $kodak_integ = $this->get_kodak_info();

      //todo: add config param for this
      $url = $kodak_integ['Z_IC02792_IBaseList'].'?wsdl';
      //$url = 'https://bcq.kodak.com:443/ws/Z_IC02792_IBaseList:IBaseList?wsdl';
      //setup the wsdl client
      $client = new soapclient($url, true, false, false, false, false, 0, 120);
      $client->certRequest['sslcertfile'] = "";
      $client->certRequest['passphrase'] = '';
      $client->certRequest['sslkeyfile'] = '';
      $client->certRequest['verifypeer'] = 0;
      $client->certRequest['verifyhost'] = 0;
      $client->soap_defencoding = "UTF-8";
      $client->setCredentials($kodak_integ['user'], $kodak_integ['pw'], "basic");

      $client->setDebugLevel( 3 );

      $CI =& get_instance();

      //$req_partner = array('RequestingPartnerID'=>$sapID, 'RequestingPartnerFunction'=>$partner_type);
      //2013.04.01 scott harris: begin changes for internal user
      $email = $CI->session->getProfileData('email');

      $CI->load->model("custom/custom_contact_model");
      $internal_contact = $CI->custom_contact_model->checkInternalContact($CI->session->getProfileData('c_id'));

      if($internal_contact == "N") {
        $req_data = array('RequestingPartnerID'=>$sapPartnerID, 'RequestingPartnerFunction'=>$partner_type, 'MaterialNbr'=>$matl_id,
                       'ShipTo' =>array('CountryKey'=>$country, 'Region'=>$region,'City'=>$city, 'Street'=>$street, 'Name'=>$name, 'PostalCode'=>$zip, 'PartnerID'=>$ibasePartnerID));  //London
      }
      else {
        $req_data = array('ShipTo' =>array('CountryKey'=>$country, 'Region'=>$region,'City'=>$city, 'Street'=>$street, 'Name'=>$name, 'PostalCode'=>$zip, 'PartnerID'=>$ibasePartnerID));
      }

      //2013.04.01 scott harris: end changes for internal user

      if($ibasePartnerID != '') {
        $req_data['ShipTo']['PartnerFunction'] = '00000002';
      }

      //if($sapID != "")
       // $ret_data = $client->call('IBaseList', array('IBaseList_Input'=>$req_partner));
      //else
 
      $ret_data = $client->call('IBaseList', array('IBaseList_Input'=>$req_data));

      //logmessage("partner id = $ibasePartnerID");
      //logmessage("call::bcq.kodak.com:443/ws/Z_IC02792_IBaseList:IBaseList REQUEST -". $client->request);
      //logmessage("call::bcq.kodak.com:443/ws/Z_IC02792_IBaseList:IBaseList RESPONSE -". $client->response);

      $loc_arr = array();

      $error = null;
      if(array_key_exists('Log',$ret_data[IBaseList_Output])) {

        if(strpos($ret_data[IBaseList_Output][Log][Item][Note], 'maximum') > 0) {
          $error = "Maximum Results Exceeded - Add Search Criteria";
        }
        else {
          $error = $ret_data[IBaseList_Output][Log][Item][Note];
        } 
      }

      //only one result at top level
      if(array_key_exists('IBaseID',$ret_data[IBaseList_Output][IBaseList])) {
          $loc = $ret_data[IBaseList_Output][IBaseList];

          $site = $this->getSite(ltrim($loc[PartnerID], "0"));
          $site['ibaseID'] = ltrim($loc[IBaseID], "0");
          $site['name'] = $loc[Description];
          $site['partnerfunction'] = ltrim($loc[PartnerFunction], "0");
          $site['customerID'] = ltrim($loc[PartnerID], "0");  //actual owner of the equipment
          $site['partnerID'] = $sapPartnerID;
          $site['manage'] = "Manage Contacts";
          $loc_arr[] = $site;                 
      }
      else {
        foreach($ret_data[IBaseList_Output][IBaseList] as $loc) {
  
          $site = $this->getSite(ltrim($loc[PartnerID], "0"));
          $site['ibaseID'] = ltrim($loc[IBaseID], "0");
          $site['name'] = $loc[Description];
          $site['partnerfunction'] = ltrim($loc[PartnerFunction], "0");
          $site['partnerID'] = $sapPartnerID;
          $site['customerID'] = ltrim($loc[PartnerID], "0");  //actual owner of the equipment
          $site['manage'] = "Manage Contacts";
          $loc_arr[] = $site;                 
        }
      }
      $results_array[] = array('ibase_list'=>$loc_arr);

      if($error)
        $results_array['error'] = $error;

      return $results_array;

    }


    public function getIbaseResult($partnerFunction, $partnerID, $ibaseID) {

      $results_array = array();
      $product_array = array();

      $kodak_integ = $this->get_kodak_info();

      //todo: add config param for this
      $url = $kodak_integ['Z_IC02792_IBaseParentList'].'?wsdl';
      //$url = 'https://bcq.kodak.com:443/ws/Z_IC02792_IBaseParentList:IBaseParentList?wsdl';  //this url requires the wsdl, from the add-in we do not
      //setup the wsdl client
      $client = new soapclient($url, true, false, false, false, false, 0, 120);
      $client->certRequest['sslcertfile'] = "";
      $client->certRequest['passphrase'] = '';
      $client->certRequest['sslkeyfile'] = '';
      $client->certRequest['verifypeer'] = 0;
      $client->certRequest['verifyhost'] = 0;
      $client->soap_defencoding = "UTF-8";
      $client->setCredentials($kodak_integ['user'], $kodak_integ['pw'], "basic");

      $client->setDebugLevel( 3 );

      //2013.04.01 scott harris: begin changes for internal user
      $CI =& get_instance();
      $CI->load->model("custom/custom_contact_model");
      $internal_contact = $CI->custom_contact_model->checkInternalContact($CI->session->getProfileData('c_id'));

      if($internal_contact == 'Y') {
        $req_data = array('PartnerFunction' =>'00000002', 'PartnerID' =>$partnerID, 'IBaseID'=>$ibaseID);
      }
      else
        $req_data = array('PartnerFunction' =>$partnerFunction,'PartnerID' =>$partnerID,'IBaseID'=>$ibaseID);

      $ret_data = $client->call('IBaseParentList', array('IBaseParentList_Input'=>$req_data));
      logmessage("call::bcq.kodak.com:443/ws/Z_IC02792_IBaseList:IBaseParentList REQUEST -". $client->request);
      logmessage("call::bcq.kodak.com:443/ws/Z_IC02792_IBaseList:IBaseParentList RESPONSE -". $client->response);

      if(is_array($ret_data[IBaseParentList_Output][IBaseParentList][0])) {

        foreach($ret_data[IBaseParentList_Output][IBaseParentList] as $product) {
 
 
          $product_array[] = array('componentID' => ltrim($product[IBaseCompID],"0"),
                                   'description' => $product[IBaseDescription],
                                   'knum' => $product[Component][KNumber],
                                   'sn' => $product[Component][SerialNumber],
                                   'material' => $product[Component][MaterialDescription],
                                   'contract' => $product[Contract][Description],
                                   'startDate' => $product[Contract][StartDate] == "" ? "" : $product[Contract][StartDate],
                                   'endDate' => $product[Contract][EndDate] == "" ? "" : $product[Contract][EndDate],
                                   'repair' => "repair",
                                   'hasActiveContract' => $this->get_active_flag($product[Contract][StartDate], $product[Contract][EndDate]),
                                   'partnerType' => $partnerFunction,
                                   'requestingPartner' => $partnerID);
      logmessage("parent list return componentID -". ltrim($product[IBaseCompID]. " start date = " . $product[Contract][StartDate],"0"));

        }
      }
      else if($ret_data[IBaseParentList_Output][IBaseParentList]['IBaseCompID']) {


        $product_array[] = array('componentID' => ltrim($ret_data[IBaseParentList_Output][IBaseParentList]['IBaseCompID'],"0"),
                                 'description' => $ret_data[IBaseParentList_Output][IBaseParentList]['IBaseDescription'],
                                 'knum' => $ret_data[IBaseParentList_Output][IBaseParentList][Component][KNumber],
                                 'sn' => $ret_data[IBaseParentList_Output][IBaseParentList][Component][SerialNumber],
                                 'material' => $ret_data[IBaseParentList_Output][IBaseParentList][Component][MaterialDescription],
                                 'contract' => $ret_data[IBaseParentList_Output][IBaseParentList][Contract][Description],
                                 'startDate' => $ret_data[IBaseParentList_Output][IBaseParentList][Contract][StartDate] == "" ? "" :
                                   $ret_data[IBaseParentList_Output][IBaseParentList][Contract][StartDate], 
                                 'endDate' => $ret_data[IBaseParentList_Output][IBaseParentList][Contract][EndDate] == "" ? "":
                                   $ret_data[IBaseParentList_Output][IBaseParentList][Contract][EndDate], 
                                 'repair' => "repair",
                                 'hasActiveContract' => $this->get_active_flag($ret_data[IBaseParentList_Output][IBaseParentList][Contract][StartDate], $ret_data[IBaseParentList_Output][IBaseParentList][Contract][EndDate]),
                                 'partnerType' => $partnerFunction,
                                 'requestingPartner' => $partnerID);
     
      logmessage("parent list return componentID -". ltrim($ret_data[IBaseParentList_Output][IBaseParentList]['IBaseCompID'],"0"));

     }
  
     if(count($product_array) > 0)
       $results_array[] = array('products'=>$product_array);
    
     return $results_array;

    }

    public function getProducts($id, $searchby, $requestingPartner, $partnerType) {

      $support_plan_types = array("ZWRC", "ZSPC", "ZSPP", "ZPWI", "ZEOC");
      $unsupported_types = array("ZSPQ", "ZOSQ", "ZRMQ", "ZPCQ", "ZPMQ", "ZDNQ", "ZDRQ", "ZWPQ", "ZRPQ", "ZWFQ");

      $enabl = "";
      $mfg = "";
      $distr = "";
      $resell = "";
      $direct = "";
      $corp = "";

      $results_array = array();
      $products_array = array();
      $components_array = array();
      $counters_array = array();
      $counter_idx = array();
      $hist_counters_array = array();

      $equipment_site_array = array();
      $plan_array = array();
      $ent_array = array();
      $def_ent_array = array();

      $sapID = 0;

      $kodak_integ = $this->get_kodak_info();

      //$url = 'https://bcq.kodak.com:443/ws/Z_IC02528_QueryInstallPointCustom:QueryInstallPointCustom?wsdl';  //this url requires the wsdl, from the add-in we do not
      $url = $kodak_integ['Z_IC02528_QueryInstallPointCustom'].'?wsdl';  //this url requires the wsdl, from the add-in we do not
      //setup the wsdl client
      $client = new soapclient($url, true, false, false, false, false, 0, 120);
      $client->certRequest['sslcertfile'] = "";
      $client->certRequest['passphrase'] = '';
      $client->certRequest['sslkeyfile'] = '';
      $client->certRequest['verifypeer'] = 0;
      $client->certRequest['verifyhost'] = 0;
      $client->soap_defencoding = "UTF-8";
      $client->setCredentials($kodak_integ['user'], $kodak_integ['pw'], "basic");

      $client->setDebugLevel( 3 );
      //$req_data = array('ZZ_K_Nbr' =>$id,'ZZ_DateRequest' => date('mdY'));

  
      if($searchby == 'compid')
        $req_data = array('ZZ_MF_CompID' =>$id,'ZZ_DateRequest' => date('mdY'));

      if($searchby == 'knum')
        $req_data = array('ZZ_K_Nbr' =>$id,'ZZ_DateRequest' => date('mdY'));

      if($searchby == 'sn')
 	$req_data = array('ZZ_SerialNbr' =>$id,'ZZ_DateRequest' => date('mdY'));
        
      if($searchby == 'mtl')
        $req_data = array('ZZ_K_Nbr' =>$id,'ZZ_DateRequest' => date('mdY'));

      //2013.04.01 scott harris: begin changes for internal user
      $CI =& get_instance();
      $CI->load->model("custom/custom_contact_model");
      $internal_contact = $CI->custom_contact_model->checkInternalContact($CI->session->getProfileData('c_id'));

      if($internal_contact == 'N') {

        if($partnerType == "00000002")
          $req_data['ZZ_PartnerID'] = $requestingPartner;
        else
          $req_data['ZZ_RequestingPartnerID'] = $requestingPartner;

        $req_data['ZZ_RequestingPartnerRole'] = $partnerType;

      }
      //else
        //$req_data['ZZ_RequestingPartnerRole'] = "00000002";
      //2013.04.01 scott harris: end changes for internal user

      logmessage("call to ::IbaseGetDetail");
      $ret_data = $client->call('IBaseGetDetail', array('IBaseGetDetail_Input'=>$req_data));
      logmessage("call::IbaseGetDetail -". $client->request);

      $all_components = array();

      if(!is_array($ret_data[IBaseGetDetail_Output][AllComponents])) {
        return null;
      }

      if(is_array($ret_data[IBaseGetDetail_Output][AllComponents][0])) {
        $all_components = $ret_data[IBaseGetDetail_Output][AllComponents];
      }
      else {

        $all_components = array($ret_data[IBaseGetDetail_Output][AllComponents]);
      }


      $product_count = 0;

      foreach($all_components as $product) {
        logmessage("all_components has at least one");

        for($i=0; $i<count($product[ComponentPartnerSet][IBPartnerAll]); $i++) {

          if($product[ComponentPartnerSet][IBPartnerAll][$i][PartyRoleCode] == '00000002') {
            $sapID = $product[ComponentPartnerSet][IBPartnerAll][$i][PartyInternalID];
          }


          //set up partner values
          switch($product[ComponentPartnerSet][IBPartnerAll][$i][PartyRoleCode]) {

            case "ZENABPRN":
              $enable = $product[ComponentPartnerSet][IBPartnerAll][$i][PartyInternalID];
              break;

            case "ZMVSMFG":
              $mfg = $product[ComponentPartnerSet][IBPartnerAll][$i][PartyInternalID];
              break;

            case "ZSVCDIST":
              $distr = $product[ComponentPartnerSet][IBPartnerAll][$i][PartyInternalID];
              break;

            case "ZSVCRESL":
              $resell = $product[ComponentPartnerSet][IBPartnerAll][$i][PartyInternalID];
              break;

            case "ZCORPACC":
              $corp = $product[ComponentPartnerSet][IBPartnerAll][$i][PartyInternalID];
              break;

            case "00000002":
              $direct = $product[ComponentPartnerSet][IBPartnerAll][$i][PartyInternalID];
              break;

            //case CORP
          }
          //end set up partners
        }


        $product_array[] = array('ID'=> $product[InstallationPointIdentificationID],
                                 'Name' => $product[IBCompObj][ProductDescription],
                                 'SN' => $product[IBCompObj][ZZ_SerialNbr],
                                 'material' => $product[IBCompObj][ZZ_SerialNbr],
                                 'SAPID' =>$sapID,
                                 'repair' => "repair",
                                 'svcDelivery'=>$product[ZZ_ServDelvStrg],
                                 'compID' => $product[InstallationPointID],
                                 'sapProdID'=>$product[ZZ_SAPMatNbr],
                                 'productHier'=> $this->get_product_hier(ltrim($product[ZZ_RNTProdID],"0")),
                                 'mf' => $product[IBCompObj][ZZ_IBParentID] == "" ? "Y" : "N",  //'mf' => $product[ZZ_MF_ind],
                                 'floorBldg' => $product[IBCompObj][ZZ_FloorBldg],
                                 'addlAddress' => $product[IBCompObj][ZZ_AddlAddr],
                                 'door' => $product[IBCompObj][ZZ_EntranceDoor],
                                 'remoteEOSL' => $product[ZZ_EOSLRemoteDate],
                                 'onsiteEOSL' => $product[ZZ_EOSLOnsiteDate],
                                 'enabling_partner' => $enable,
                                 'mfg_partner' => $mfg,
                                 'distr_partner' => $distr,
                                 'resell_partner' => $resell,
                                 'direct_partner' => $direct,
                                 'corporate_partner' => $corp);
                   
        if(is_array($product[IBCompObj][CounterInfo][0])) {


          for($i=0; $i<count($product[IBCompObj][CounterInfo]); $i++) {

            if(!in_array(ltrim($product[IBCompObj][CounterInfo][$i][ZZ_CounterID],"0"), $counter_idx)) {

              $counter_idx[] = ltrim($product[IBCompObj][CounterInfo][$i][ZZ_CounterID],"0");

              $counters_array[] = array('id'=> ltrim($product[IBCompObj][CounterInfo][$i][ZZ_CounterID],"0"),
                                      'descr' => $product[IBCompObj][CounterInfo][$i][ZZ_CounterDesc],
                                      'reading' => ltrim($product[IBCompObj][CounterInfo][$i][ZZ_CounterReading], " "),
                                      'unit' => $product[IBCompObj][CounterInfo][$i][ZZ_CounterUnit],
                                      'date' => substr($product[IBCompObj][CounterInfo][$i][ZZ_ReadDate], 0,4) . "-" .
                                                substr($product[IBCompObj][CounterInfo][$i][ZZ_ReadDate], 4,2) . "-" .
                                                substr($product[IBCompObj][CounterInfo][$i][ZZ_ReadDate], 6,2),
                                      'main_counter' => $product[IBCompObj][CounterInfo][$i][ZZ_MainCntrInd],
                                      'source' => $product[IBCompObj][CounterInfo][$i][ZZ_ReadOrigin],
                                      'new_reading' => '<Enter Value>');
             }
             else { //add as history

               $hist_counters_array[] = array('id'=> ltrim($product[IBCompObj][CounterInfo][$i][ZZ_CounterID],"0"),
                                      'descr' => $product[IBCompObj][CounterInfo][$i][ZZ_CounterDesc],
                                      'reading' => ltrim($product[IBCompObj][CounterInfo][$i][ZZ_CounterReading], " "),
                                      'unit' => $product[IBCompObj][CounterInfo][$i][ZZ_CounterUnit],
                                      'date' => substr($product[IBCompObj][CounterInfo][$i][ZZ_ReadDate], 0,4) . "-" .
                                                substr($product[IBCompObj][CounterInfo][$i][ZZ_ReadDate], 4,2) . "-" .
                                                substr($product[IBCompObj][CounterInfo][$i][ZZ_ReadDate], 6,2),
                                      'main_counter' => $product[IBCompObj][CounterInfo][$i][ZZ_MainCntrInd],
                                      'source' => $product[IBCompObj][CounterInfo][$i][ZZ_ReadOrigin],
                                      'new_reading' => '<Enter Value>');


             }

          }


        }  //end yes counters
        elseif(is_array($product[IBCompObj][CounterInfo])) {

            $counters_array[] = array('id'=> ltrim($product[IBCompObj][CounterInfo][ZZ_CounterID],"0"),
                                    'descr' => $product[IBCompObj][CounterInfo][ZZ_CounterDesc],
                                    'reading' => ltrim($product[IBCompObj][CounterInfo][ZZ_CounterReading], " "),
                                    'unit' => $product[IBCompObj][CounterInfo][ZZ_CounterUnit],
                                    'date' => substr($product[IBCompObj][CounterInfo][ZZ_ReadDate], 0,4) . "-" .
                                              substr($product[IBCompObj][CounterInfo][ZZ_ReadDate], 4,2) . "-" .
                                              substr($product[IBCompObj][CounterInfo][ZZ_ReadDate], 6,2),
                                    'main_counter' => $product[IBCompObj][CounterInfo][ZZ_MainCntrInd],
                                    'source' => $product[IBCompObj][CounterInfo][ZZ_ReadOrigin],
                                    'new_reading' => '<Enter Value>');

        }


        foreach($product[ComponentContract] as $contract) {

          if(in_array($contract[ZZ_ProcTypeCode], $support_plan_types) && !in_array($contract[ZZ_ProcTypeCode], $unsupported_types)) {

            $dt_now = new DateTime(date('Y-m-d H:i:s',time()));
            $dt_start = new DateTime($contract[DatePeriodStartDate]);
            $dt_end = new DateTime($contract[DatePeriodEndDate]);

            $diff_to_start = date_diff($dt_now,$dt_start);
            $diff_to_end = date_diff($dt_now,$dt_end);

            //if( intval($diff_to_start->format('%R%a')) && intval($diff_to_end->format('%R%a')) ) {
            if( intval($diff_to_start->format('%R%a')) <= 0 && intval($diff_to_end->format('%R%a')) >= 0 ) {


              $supportplans_array[] = array('description'=>$contract[BusinessTransactionDocumentItemProductDescription],
                                              'startDate'=>$contract[DatePeriodStartDate] == "" ? "" : $contract[DatePeriodStartDate],
                                                'endDate'=>$contract[DatePeriodEndDate] == "" ? "" : $contract[DatePeriodEndDate],
                                                   'type'=>$contract[BusinessTransactionDocumentProcessingTypeName],
                                     'serviceProfileDesc'=>$contract[ZZ_ServProfDesc],
                                       'serviceProfileID'=>$contract[ZZ_ServProfileID],
                                    'responseProfileDesc'=>$contract[ZZ_RespProfDesc],
                                      'responseProfileID'=>$contract[ZZ_ResposeProfile],
                                                'payerID'=>$contract[ZZ_ContPayerID],
                                                 'status'=>'Active',
                                        'zz_proctypecode'=>$contract[ZZ_ProcTypeCode]); 

            }


          }  //end process of support plans

          else if(!in_array($contract[ZZ_ProcTypeCode], $unsupported_types)) {


            $dt_now = new DateTime(date('Y-m-d H:i:s',time()));
            $dt_start = new DateTime($contract[DatePeriodStartDate]);
            $dt_end = new DateTime($contract[DatePeriodEndDate]);

            $diff_to_start = date_diff($dt_now,$dt_start);
            $diff_to_end = date_diff($dt_now,$dt_end);

            //if( intval($diff_to_start->format('%R%a')) && intval($diff_to_end->format('%R%a')) ) {
            if( intval($diff_to_start->format('%R%a')) <= 0 && intval($diff_to_end->format('%R%a')) >= 0 ) {

              $contracts_array[] =  array('description'=>$contract[BusinessTransactionDocumentItemProductDescription],
                                            'startDate'=>$contract[DatePeriodStartDate] == "" ? "" : $contract[DatePeriodStartDate],
                                              'endDate'=>$contract[DatePeriodEndDate] == "" ? "" : $contract[DatePeriodEndDate],
                                                 'type'=>$contract[BusinessTransactionDocumentProcessingTypeName],
                                           'contractID'=>$contract[BusinessTransactionDocumentID],
                                   'serviceProfileDesc'=>$contract[ZZ_ServProfDesc],
                                     'serviceProfileID'=>$contract[ZZ_ServProfileID],
                                  'responseProfileDesc'=>$contract[ZZ_RespProfDesc],
                                    'responseProfileID'=>$contract[ZZ_ResposeProfile],
                                              'payerID'=>$contract[ZZ_ContPayerID],
                                               'status'=>'Active',
                                      'zz_proctypecode'=>$contract[ZZ_ProcTypeCode]); 


 
            } //contract is current

          }

        }  //end for loop of contracts

        $product_array[$product_count]['support_plans'] = $supportplans_array;
        $product_array[$product_count]['contracts'] = $contracts_array;

        $product_array[$product_count]['plan'] = $supportplans_array[0]['description'];
        $product_array[$product_count]['planStart'] = $supportplans_array[0]['startDate'] == "" ? "" : $supportplans_array[0]['startDate'];  // $supportplans_array[0]['startDate'];
        $product_array[$product_count]['planEnd'] = $supportplans_array[0]['endDate'] == "" ? "" : $supportplans_array[0]['endDate']; // $supportplans_array[0]['endDate'];
        $product_array[$product_count]['sds'] = $this->get_SDS($contracts_array, $product_array[0]['svcDelivery']); 

        if($product_array[$product_count]['sds'] == "Remote") {
          $codes = $this->get_remote_profile_codes($contracts_array);
          $product_array[$product_count]['sp'] = $codes['sp'] != '' ? $codes['sp']  : $product[ZZ_MatDefServProfID];
          $product_array[$product_count]['rp'] = $codes['rp'] != '' ? $codes['rp']  : $product[ZZ_MatDefRespProfID];

        }

//        $product_array[$product_count]['sp'] = $supportplans_array[0]['serviceProfileID'] != '' ? $supportplans_array[0]['serviceProfileID']  : $product[ZZ_MatDefServProfID];
        $product_array[$product_count]['hasActiveContract'] = $this->get_active_flag($supportplans_array[0]['startDate'], $supportplans_array[0]['endDate']);

        if(count($counters_array) > 0) {
          $product_array[$product_count]['meters'] = $counters_array;
        }

        if(count($hist_counters_array) > 0) {
          $product_array[$product_count]['meter_history'] = $hist_counters_array;
        }

        $product_count++;
        unset($contracts_array);  // $contracts_array = null;
        unset($supportplans_array); //$supportplans_array = null;
        unset($counters_array);
        unset($counter_idx);
        unset($hist_counters_array);

      }  //end loop of products


      //2013.04.30 scott harris: error processing for mulitiple results

      if(is_array($ret_data[IBaseGetDetail_Output][Log])) {

        if($ret_data[IBaseGetDetail_Output][Log][BusinessDocumentProcessingResultCode] == '5') {

          if($ret_data[IBaseGetDetail_Output][Log][Item][1][TypeID] == '441(ZY)' || $ret_data[IBaseGetDetail_Output][Log][Item][1][TypeID] == '441(ZY)') {
            unset($product_array);
            $results_array[0]['error'] = array('message'=>'MULTIPLES FOUND');
          }

        }
      }

      //end error processing

      $results_array[0]['SAPID'] = $product_array[0]['SAPID'];
      $results_array[0]['PAYERID'] = $product_array[0]['support_plans'][0]['payerID'];

      $results_array[0]['products'] = $product_array;
      
      return $results_array; 

    }  //end of function

    //logic to determine if permission for repairs
    function get_repair_link() {
      return "Repair Request";
    }

    function get_product_hier($prodID) {

      $prodHier = "";

      if($prodID > 0) {
        $prod = new RNCPHP\ServiceProduct();
        $queryResult = RNCPHP\ROQL::queryObject("select serviceproduct from serviceproduct where Descriptions.Language.ID = 1 and id = $prodID")->next();
        $prod = $queryResult->next();


        foreach($prod->ProductHierarchy as $hier) {
          $prodHier .=  $hier->ID . ",";
        }
        $prodHier = rtrim($prodHier, ",") . "," . $prod->ID;
      }

      //return "1479,2892,3135,2310";

      return $prodHier;


    }


    function get_SDS(&$contract_arr, $svc_delivery) {

      $sds = "Blank";

      foreach($contract_arr as $contract) {

        if($svc_delivery == "R" && ($contract['zz_proctypecode'] == "ZRMW" || $contract['zz_proctypecode'] == "ZRMC")) {  //remote
          $sds = "Remote";
          break;
        }
        if($svc_delivery == "O" && ($contract['zz_proctypecode'] == "ZOSW" || $contract['zz_proctypecode'] == "ZOSC" || $contract['zz_proctypecode'] == "ZWRC")) {  //onsite
          $sds = "Onsite";
          break;
        }

      }

      return $sds;
    }


    function get_remote_profile_codes(&$contract_arr) {

      $code_arr = array('sp'=>"", 'rp'=>"");

      foreach($contract_arr as $contract) {

        if($contract['zz_proctypecode'] == "ZRMW" || $contract['zz_proctypecode'] == "ZRMC") {

          $code_arr['sp'] = $contract['serviceProfileID'];
          $code_arr['rp'] = $contract['responseProfileID'];
          break;
        }

      }

      return $code_arr;
    }


    function getCountryList() {
      $arr = array();

/*
      $countries = RNCPHP\ConnectAPI::getNamedValues("RightNow\\Connect\\v1_2\\Organization.Addresses","Country");

      foreach($countries as $values) {
        $arr[] = array('ID'=>$values->LookupName,'LookupName'=>$values->LookupName);
      }
*/

      $res = RNCPHP\ROQL::queryObject("SELECT country from country")->next();

      while($country = $res->next()) {
        $arr[] = array('ID'=>$country->LookupName,'LookupName'=>$country->Name);
      }

      return $arr;
    }

    function getStateList($country_lookup) {

      $states = array();
            
      if(strlen($country_lookup) == 2) {
        $where = sprintf("LookupName = '%s'", $country_lookup);

        $country = RNCPHP\Country::first($where);

        foreach($country->Provinces as $province) {
          $states[] = array('ID'=>substr($province->Name, -2),'LookupName'=>$province->Name);      
        }
      }
      return $states;
    }

    function getSite($id) {
	
        $org_arr = array();
	
	$query = sprintf("select organization from organization where organization.c\$ek_customer_sapid = '%s'",$id);
	  
	$result = RNCPHP\ROQL::queryObject($query)->next();
	$org = $result->next();
				
	$org_arr = array('OrgName'=>$org->Name, 'street'=>$org->Addresses[0]->Street, 
			   'city'=>$org->Addresses[0]->City, 'zip'=>$org->Addresses[0]->PostalCode,
			   'province'=>$org->Addresses[0]->StateOrProvince->LookupName,
			   'country'=>$org->Addresses[0]->Country->LookupName,
                           'custSAPId'=>$org->CustomFields->ek_customer_sapid,
                           'orgID'=>$org->ID,
                           'manage'=>"Manage Contacts");
	
	return $org_arr;
	  
    }
  
 
    public function getIbaseListByProduct($searchby, $id) {

      $results_array = array();

      $kodak_integ = $this->get_kodak_info();

      //todo: add config param for this
      $url = $kodak_integ['Z_IC02792_IBaseList'].'?wsdl';
      //$url = 'https://bcq.kodak.com:443/ws/Z_IC02792_IBaseList:IBaseList?wsdl';
      //setup the wsdl client
      $client = new soapclient($url, true, false, false, false, false, 0, 120);
      $client->certRequest['sslcertfile'] = "";
      $client->certRequest['passphrase'] = '';
      $client->certRequest['sslkeyfile'] = '';
      $client->certRequest['verifypeer'] = 0;
      $client->certRequest['verifyhost'] = 0;
      $client->soap_defencoding = "UTF-8";
      $client->setCredentials($kodak_integ['user'], $kodak_integ['pw'], "basic");

      $client->setDebugLevel( 3 );

      $CI =& get_instance();


      if($searchby == 'knum')
        $req_data = array('K_Nbr' =>$id);

      if($searchby == 'sn')
        $req_data = array('SerialNbr' =>$id);

      if($searchby == 'mtl')
        $req_data = array('MaterialNbr' =>$id);

      $ret_data = $client->call('IBaseList', array('IBaseList_Input'=>$req_data));

      logmessage("call::bcq.kodak.com:443/ws/Z_IC02792_IBaseList:IBaseList REQUEST -". $client->request);
      logmessage("call::bcq.kodak.com:443/ws/Z_IC02792_IBaseList:IBaseList RESPONSE -". $client->response);

      $loc_arr = array();

      $error = null;
      if(array_key_exists('Log',$ret_data[IBaseList_Output])) {

        if(strpos($ret_data[IBaseList_Output][Log][Item][Note], 'maximum') > 0) {
          $error = "Maximum Results Exceeded - Add Search Criteria";
        }
        else {
          $error = $ret_data[IBaseList_Output][Log][Item][Note];
        }
      }

      //only one result at top level
      if(array_key_exists('IBaseID',$ret_data[IBaseList_Output][IBaseList])) {
          $loc = $ret_data[IBaseList_Output][IBaseList];

          $site = $this->getSite(ltrim($loc[PartnerID], "0"));
          $site['ibaseID'] = ltrim($loc[IBaseID], "0");
          $site['name'] = $loc[Description];
          $site['partnerfunction'] = ltrim($loc[PartnerFunction], "0");
          $site['customerID'] = ltrim($loc[PartnerID], "0");  //actual owner of the equipment
          $site['partnerID'] = $sapPartnerID;
          $site['manage'] = "Manage Contacts";
          $loc_arr[] = $site;
      }
      else {
        foreach($ret_data[IBaseList_Output][IBaseList] as $loc) {

          $site = $this->getSite(ltrim($loc[PartnerID], "0"));
          $site['ibaseID'] = ltrim($loc[IBaseID], "0");
          $site['name'] = $loc[Description];
          $site['partnerfunction'] = ltrim($loc[PartnerFunction], "0");
          $site['partnerID'] = $sapPartnerID;
          $site['customerID'] = ltrim($loc[PartnerID], "0");  //actual owner of the equipment
          $site['manage'] = "Manage Contacts";
          $loc_arr[] = $site;
        }
      }
      $results_array[] = array('ibase_list'=>$loc_arr);

      if($error)
        $results_array['error'] = $error;

      return $results_array;

    }


    function sendMeterUpdate($counterID, $counterValue, $readDate) {

      $kodak_integ = $this->get_kodak_info();
      $results_array = array();

      $url = $kodak_integ['Z_IC02871_MeterReadCreate'].'?wsdl';  //this url requires the wsdl, from the add-in we do not

      //setup the wsdl client
      $client = new soapclient($url, true, false, false, false, false, 0, 120);
      $client->certRequest['sslcertfile'] = "";
      $client->certRequest['passphrase'] = '';
      $client->certRequest['sslkeyfile'] = '';
      $client->certRequest['verifypeer'] = 0;
      $client->certRequest['verifyhost'] = 0;
      $client->soap_defencoding = "UTF-8";
      $client->setCredentials($kodak_integ['user'], $kodak_integ['pw'], "basic");

      $client->setDebugLevel( 3 );

      $req_data = array('Z_MeterReadCreate_IN'=> array('IvCounter'=>$counterID, 'IvDate'=>$readDate, 'IvReading'=>$counterValue, 'IvDescr'=>"INTERNET CUSTOMER"));

      //$ret_data = $client->call('zbapiMeterReadCreate', array('zbapiMeterReadCreate'=>$req_data));
      $ret_data = $client->call('zbapiMeterReadCreate', $req_data);

      logmessage("call:: meter read create -");
      logmessage($ret_data);


      if($ret_data['Z_MeterReadCreate_OUT']['EvSuccess']) {
        $results_array['error'] = 0;
      }
      else {
        $results_array['error'] = 1;

        if($ret_data['detail']['exception']['message']) {
          $results_array['message'] = implode(":",$ret_data['detail']['exception']['message']);
        }
        else
          $results_array['message'] = $ret_data['Z_MeterReadCreate_OUT']['Return']['Message'];
      }

      return $results_array;
    }

 
    function get_kodak_info() {
	
	$arr = array();
	
	$result = RNCPHP\ROQL::query("Select ek_ws_user,
					     ek_ws_password,
   				             ek_contact_create_ws,
					     ek_contact_update_ws,
					     ek_custmaster_create_ws,
					     ek_svcorder_confirm_ws,
					     ek_svcorder_update_ws,
					     ek_svcorder_create_ws,
					     ek_sla_dates_ws,
					     ek_ibase_ws,
                                             ek_ibase_parent_list_ws,
                                             ek_ibase_site_list_ws,
                                             ek_meter_read_create
					from CIH.ek_configuration where ID = 1")->next();
	
	 $my_config = $result->next();
	
	 $arr['user'] = $my_config['ek_ws_user'];
	
	 $arr['pw'] = $my_config[ek_ws_password];
	 $arr['Z_IC02522_ContactCreate'] = $my_config[ek_contact_create_ws];
	 $arr['Z_IC02523ContactUpdate'] = $my_config[ek_contact_update_ws];
	 $arr['Z_IC02536_CreateCustMaster'] = $my_config[ek_custmaster_create_ws];
	 $arr['Z_IC02526_ServiceOrderConfirm'] = $my_config[ek_svcorder_confirm_ws];
	 $arr['Z_IC02519_UpdateServiceOrder'] = $my_config[ek_svcorder_update_ws];
	 $arr['Z_IC02518_CreateServiceOrder'] = $my_config[ek_svcorder_create_ws];
	 $arr['Z_IC02535_GetSLADates'] = $my_config[ek_sla_dates_ws];
	 $arr['Z_IC02528_QueryInstallPointCustom'] = $my_config[ek_ibase_ws];
         $arr['Z_IC02792_IBaseParentList'] = $my_config[ek_ibase_parent_list_ws];
         $arr['Z_IC02792_IBaseList'] = $my_config[ek_ibase_site_list_ws];
         $arr['Z_IC02871_MeterReadCreate'] = $my_config[ek_meter_read_create];
	
      return $arr;
    }	

    function get_active_flag($start, $end) {

      $flag = "N";
   
      if($start != "") {
      
        $dt_now = new DateTime(date('Y-m-d H:i:s',time()));
        $dt_start = new DateTime($start);
        $dt_end = new DateTime($end);

        $diff_to_start = date_diff($dt_now,$dt_start);
        $diff_to_end = date_diff($dt_now,$dt_end);

          //if( intval($diff_to_start->format('%R%a')) && intval($diff_to_end->format('%R%a')) ) {
          if( intval($diff_to_start->format('%R%a')) <= 0 && intval($diff_to_end->format('%R%a')) >= 0 ) {
            $flag = "Y";
          }
      }

      return $flag;
    }
}
