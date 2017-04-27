<?
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
?>

<form id="rn_<?=$this->instanceID;?>_SearchForm" onsubmit="return false;"><div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?> rn_KeywordText2">
<? echo $cih_lang_msg_base_array['partnertype']; ?>&nbsp;<? if($this->data['js']['internal_user']=='Y') print($cih_lang_msg_base_array['loggedinasinternal']); ?></br>
<table style="width:100%">
<tr>
  <td><rn:widget path="custom/CIHFunction/PartnerTypeList" name="partnerTypeSelect2" /></td>

<? if($this->data['js']['internal_user']=='N'): ?>
  <td nowrap="nowrap" valign="bottom"><a id="rn_<?=$this->instanceID;?>_advSearchPanelTrigger" style="cursor:pointer"><? echo $cih_lang_msg_base_array['showhideadv']; ?></a>
  
  </td>
<? endif;?>

<? if($this->data['js']['internal_user']=='Y'): ?>
  <td>&nbsp;</td>
  </td>
<? endif;?>

  <td valign="bottom" align="right" width="850px" style="margin-right:200px;">
    <input type="submit" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> name="rn_<?=$this->instanceID;?>_CustSubmit" id="rn_<?=$this->instanceID;?>_CustSubmit" class="Button" value="<? echo $cih_lang_msg_base_array['search']; ?>" />
  </td>
</tr>
</table>
<? if($this->data['js']['internal_user']=='Y'): ?>
<table>
 <tr>
    <td>&nbsp;</td>
    <td><label for="rn_<?=$this->instanceID;?>_Text"><? echo $cih_lang_msg_base_array['sitecustid']; ?></label></td>
 </tr>
 <tr>
    <td>&nbsp;</td>
    <td><input id="rn_<?=$this->instanceID;?>_CustIDField" name="rn_<?=$this->instanceID;?>_CustIDField" type="text" maxlength="255" value="" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> />
 </tr>
</table>
<? endif;?>

<? if($this->data['js']['internal_user']=='N'): ?>
<div id="rn_<?=$this->instanceID;?>_advSearchPanel" style="display:none" >
<table>
 <tr>
    <td>&nbsp;</td>
    <td><label for="rn_<?=$this->instanceID;?>_Text"><? echo $cih_lang_msg_base_array['sitecustid']; ?></label></td>
    <td><label for="rn_<?=$this->instanceID;?>_Text"><? echo $cih_lang_msg_base_array['mysites_name']; ?></label></td>
    <td colspan="2"><label for="rn_<?=$this->instanceID;?>_Text"><? echo $cih_lang_msg_base_array['mysites_materialid']; ?></label></td>
 </tr>
 <tr>
    <td>&nbsp;</td>
    <td><input id="rn_<?=$this->instanceID;?>_CustIDField" name="rn_<?=$this->instanceID;?>_CustIDField" type="text" maxlength="255" value="" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> />
    <td><input id="rn_<?=$this->instanceID;?>_CustNameField" name="rn_<?=$this->instanceID;?>_CustNameField" type="text" maxlength="255" value="" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> /></td>
    <td><input id="rn_<?=$this->instanceID;?>_CustMaterial" name="rn_<?=$this->instanceID;?>_CustMaterial" type="text" maxlength="255" value="" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> /></td>
    <td>&nbsp;</td>
 </tr>
 <tr>
   <td><label for="rn_<?=$this->instanceID;?>_Text"><? echo $cih_lang_msg_base_array['mysites_street']; ?></label></td>
   <td><label for="rn_<?=$this->instanceID;?>_Text"><? echo $cih_lang_msg_base_array['mysites_city']; ?></label></td>
   <td><label for="rn_<?=$this->instanceID;?>_Text"><? echo $cih_lang_msg_base_array['mysites_postalcode']; ?></label></td>
   <td><label for="rn_<?=$this->instanceID;?>_Text"><? echo $cih_lang_msg_base_array['mysites_country']; ?></label></td>
   <td><label for="rn_<?=$this->instanceID;?>_Text"><? echo $cih_lang_msg_base_array['mysites_regprovstate']; ?></label></td>
 </tr>
 <tr>
  <td><input id="rn_<?=$this->instanceID;?>_CustStreetField" name="rn_<?=$this->instanceID;?>_CustStreetField" type="text" maxlength="255" value="" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> /></td>
  <td><input id="rn_<?=$this->instanceID;?>_CustCityField" name="rn_<?=$this->instanceID;?>_CustCityField" type="text" maxlength="255" value="" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> /></td>
  <td><input id="rn_<?=$this->instanceID;?>_CustPostalCodeField" name="rn_<?=$this->instanceID;?>_CustPostalCodeField" type="text" maxlength="255" value="" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> /></td>
  <td><rn:widget path="CIHFunction/CountryRegionSelector"/></td>
  <td><rn:widget path="CIHFunction/ProvinceSelector"/></td>
 </tr>
</table>
</div>
<? endif;?>

</form>
</div>
<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionSites2" visible="true" expanded="false" item_to_toggle="panelSites2" label_header="#rn:php:$cih_lang_msg_base_array['mysites']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelSites2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_sitetable2"></div>

    </div>
  </div>
</div>

<div class="rn_Accordion_container">
<style>
.rn_Accordion_header_label
{
	width:350px;
}
</style>
  <rn:widget path="CIHFunction/Accordion" name="accordionProducts2" visible="true" expanded="false" item_to_toggle="panelProducts2" label_header="#rn:php:$cih_lang_msg_base_array['prodidentifier']#" />

  <div id="rn_<?=$this->instanceID;?>_containerProducts" class="rn_FormPanel">

    <div id="panelProducts2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="rn_<?=$this->instanceID;?>_div_producttable"></div>

     </div>
  </div>
</div>


<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionComponents2" visible="false" expanded="false" item_to_toggle="panelComponent2" label_header="#rn:php:$cih_lang_msg_base_array['component']#" />

  <div id="rn_<?=$this->instanceID;?>_containerComponent" class="rn_FormPanel">

    <div id="panelComponent2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_componentstable"></div>

    </div>
  </div>
</div>

<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionComponentDetails2" visible="false" expanded="false" item_to_toggle="panelDetails2" label_header="#rn:php:$cih_lang_msg_base_array['componentdetails']#" />

  <div id="rn_<?=$this->instanceID;?>_containerDetails" class="rn_FormPanel99">

    <div id="panelDetails2" style="width=100%;">
      <!-- Panel content goes here -->
      <div id="tvcontainer2" class="yui-navset">
      <ul class="yui-nav">
          <li class="selected pid_tabs" display_content='div_contracts2'><a  href="javascript:void(0)"><em><? echo $cih_lang_msg_base_array['pidtab_entitlement']; ?></em></a></li>
          <li class="pid_tabs" display_content='equipment_site_info2'><a href="javascript:void(0)"><em><? echo $cih_lang_msg_base_array['pidtab_siteinformation']; ?></em></a></li>
          <li class="pid_tabs" display_content='contract_payer_info2'><a href="javascript:void(0)"><em><? echo $cih_lang_msg_base_array['pidtab_servicecontractpayerinfo']; ?></em></a></li>
          <li class="pid_tabs" display_content='meters' ><a href="javascript:void(0)"><em><? echo $cih_lang_msg_base_array['pidtab_meterview']; ?></em></a></li>
      </ul>            
      <div class="yui-content">
        <div id="div_contracts2"></div>
        <div><p><table id="equipment_site_info2" name="equipment_site_info2"></table></p></div>
        <div><p><table id="contract_payer_info2" name="contract_payer_info2"></table></p></div>
        <div>
          <table id="meters" name="meters">
           <tr><td>
                <div id="div_meters2">
                <rn:widget path="output/CurrentMeter" elementID="2" />
           </td></tr>
           <tr><td>
             <div class="rn_Accordion_container">
               <rn:widget path="CIHFunction/Accordion" name="accordionMeterHistory2" visible="true" expanded="true" item_to_toggle="panelMeterHistory2" label_header="#rn:php:$cih_lang_msg_base_array['pidmeterhistory']#" />

                <div id="rn_<?=$this->instanceID;?>_containerMeterHistory" class="rn_FormPanel">


                  <div id="panelMeterHistory2" class="rn_Accordion_content">
                    <!-- Panel content goes here -->
                    <rn:widget path="output/MeterHistory" />
                  </div>
                </div>
              </div>
           </td></tr>
         </table>

      </div>

      </div>
      </div>

    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionManageContacts2" visible="false" expanded="false" item_to_toggle="panelManageContacts2" label_header="#rn:php:$cih_lang_msg_base_array['managecontacts']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelManageContacts2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/ManageContacts" panel_name="accordionManageContacts2" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionRepairRequest2" visible="false" expanded="false" item_to_toggle="panelRepairRequest2" label_header="#rn:php:$cih_lang_msg_base_array['repairrequest']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelRepairRequest2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/RepairRequest" panel_name="accordionRepairRequest2" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionIbaseUpdate2" visible="false" expanded="false" item_to_toggle="panelIbaseUpdate2" label_header="#rn:php:$cih_lang_msg_base_array['ibaseupdate']#" />

  <div id="rn_<?=$this->instanceID;?>_containerIbaseUpdate" class="rn_FormPanel">

    <div id="panelIbaseUpdate2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/IBaseUpdate" panel_name="accordionIbaseUpdate2" widget_index="0" /> 
    </div>
  </div>
</div>
<input type='hidden' id='site_json_1' value='' >

<input type='hidden' id='ibase_list' value='{"0":{"ibase_list":[{"OrgName":"DAILY NEWS PUBLISHING CO (THE)","street":"193 JEFFERSON AVE","city":"MEMPHIS","zip":"38103-2322","province":"Tennessee-TN","country":"US","custSAPId":"814720","orgID":405606,"manage":"Manage Contacts","ibaseID":"1790415","name":"DAILY NEWS PUBLISHING CO (THE)","partnerfunction":"2","partnerID":"773957","customerID":"814720"},{"OrgName":"DAILY NEWS RECORD","street":"231 S LIBERTY ST","city":"HARRISONBURG","zip":"22801","province":"Virginia-VA","country":"US","custSAPId":"773957","orgID":396402,"manage":"Manage Contacts","ibaseID":"1789267","name":"DAILY NEWS RECORD","partnerfunction":"2","partnerID":"773957","customerID":"773957"}]},"status":1}'>

<input type='hidden' id='contacts_json' value='{"0":{"c_id":15356,"first_name":"Eric","last_name":"Barnes","phone":"","last_contact_id":32012},"1":
{"c_id":30961,"first_name":"John","last_name":"Bubscher","phone":"(901) 528-8115","last_contact_id":32012
},"2":{"c_id":28881,"first_name":"Tom","last_name":"Clark","phone":"(901) 849-2303","last_contact_id"
:32012},"3":{"c_id":32012,"first_name":"Cedric","last_name":"Walsh","phone":"9015231561147","last_contact_id"
:32012},"status":1}'>

<input type='hidden' id='ibase_json' value='{"0":{"products":[{"componentID":"1790424","description":"KM-FG,PRINERGY SOFTWARE (EVO)","knum":"V0784445"
,"sn":"EV07844-45","material":"015-01039A","contract":"","startDate":"","endDate":"","repair":"repair"
,"hasActiveContract":"N","partnerType":"ZCORPACC","requestingPartner":"773957"},{"componentID":"1790416"
,"description":"PLATES PF-N","knum":"0CM01032","sn":"CM01032","material":"8297988","contract":"WARRANTY
 SUPPORT PLAN","startDate":"2012-10-01","endDate":"2021-01-15","repair":"repair","hasActiveContract"
:"Y","partnerType":"ZCORPACC","requestingPartner":"773957"},{"componentID":"3741655","description":"PLATES
 SONORA NEWS (074)","knum":"814720SN","sn":"","material":"7433741","contract":"WARRANTY SUPPORT PLAN"
,"startDate":"2014-12-03","endDate":"9999-12-31","repair":"repair","hasActiveContract":"Y","partnerType"
:"ZCORPACC","requestingPartner":"773957"}]},"status":1}' >

<input type='hidden' id='product_jsonGG' value='{"0":{"SAPID":"814720","PAYERID":"814720","OUTSIDEENTID":"814720","products":[{"ID":"814720SN","Name":"PLATES SONORA NEWS (074)","SN":null,"material":null,"SAPID":"814720","repair":"repair","svcDelivery":"R","compID":"3741655","sapProdID":"7433741","productHier":"1482,9998,2409,10006","mf":"Y","floorBldg":null,"addlAddress":null,"door":null,"remoteEOSL":"00000000","onsiteEOSL":"00000000","enabling_partner":null,"mfg_partner":"","distr_partner":"","resell_partner":"","direct_partner":"814720","corporate_partner":"773957","support_plans":[{"description":"WARRANTY SUPPORT PLAN","startDate":"2014-12-03","endDate":"9999-12-31","type":"Warranty Contract","serviceProfileDesc":null,"serviceProfileID":null,"responseProfileDesc":null,"responseProfileID":null,"payerID":"814720","outsideEntId":"814720","status":"Active","zz_proctypecode":"ZWRC"}],"contracts":[{"description":"TELEPHONE SUPPORT ENTITLEMENT","startDate":"2014-12-03","endDate":"9999-12-31","type":"Warranty Contract","contractID":"8000077000","serviceProfileDesc":"M-F, 08:00-21:00, US","serviceProfileID":"USR13X5","responseProfileDesc":"Within 1 Hr of Call Receipt","responseProfileID":"WWR1HR","payerID":"814720","outsideEntId":"814720","status":"Active","zz_proctypecode":"ZRMW"},{"description":"ONSITE FIELD SERVICE ENTITLEMENT","startDate":"2014-12-03","endDate":"9999-12-31","type":"Warranty Contract","contractID":"8000077000","serviceProfileDesc":"M-F, 08:00-17:00 US","serviceProfileID":"USO9X5","responseProfileDesc":"Next Business Day","responseProfileID":"WWONBD","payerID":"814720","outsideEntId":"814720","status":"Active","zz_proctypecode":"ZOSW"},{"description":"PARTS COVERAGE ENTITLEMENT","startDate":"2014-12-03","endDate":"9999-12-31","type":"Warranty Contract","contractID":"8000077000","serviceProfileDesc":null,"serviceProfileID":null,"responseProfileDesc":null,"responseProfileID":null,"payerID":"814720","outsideEntId":"814720","status":"Active","zz_proctypecode":"ZPCW"},{"description":"SOFTWARE UPDATES AND PATCHES","startDate":"2014-12-03","endDate":"9999-12-31","type":"Warranty Contract","contractID":"8000077000","serviceProfileDesc":null,"serviceProfileID":null,"responseProfileDesc":null,"responseProfileID":null,"payerID":"814720","outsideEntId":"814720","status":"Active","zz_proctypecode":"ZWPW"}],"plan":"WARRANTY SUPPORT PLAN","planStart":"2014-12-03","planEnd":"9999-12-31","sds":"Remote","sp":"USR13X5","rp":"WWR1HR","hasActiveContract":"Y"}]},"status":1,"Site":[{"OrgName":"DAILY NEWS PUBLISHING CO (THE)","street":"193 JEFFERSON AVE","city":"MEMPHIS","zip":"38103-2322","province":"Tennessee-TN","country":"US","custSAPId":"814720","orgID":405606,"manage":"Manage Contacts"}],"Payer":{"OrgName":"DAILY NEWS PUBLISHING CO (THE)","street":"193 JEFFERSON AVE","city":"MEMPHIS","zip":"38103-2322","province":"Tennessee-TN","country":"US","custSAPId":"814720","orgID":405606,"manage":"Manage Contacts","OUTSIDEENTID":"814720","SAPID":"814720"}}' >
<input type='hidden' id='product_json' value='{"0":{"SAPID":"741387","PAYERID":"741387","OUTSIDEENTID":"741387","products":[{"ID":"8406545","Name":"NXP SE3600 PHOTO PRESS 230V","SN":"3073-SC","material":"3073-SC","SAPID":"741387","repair":"repair","svcDelivery":"O","compID":"4343623","sapProdID":"KCPHSE3600230V","productHier":"1481,2232,2750,9301","mf":"Y","floorBldg":"BJ REN MING RI BAO MING YI YIN","addlAddress":null,"door":null,"remoteEOSL":"00000000","onsiteEOSL":"00000000","enabling_partner":null,"mfg_partner":"","distr_partner":"","resell_partner":"","direct_partner":"741387","corporate_partner":"","support_plans":[{"description":"ADVANCED SUPPORT PLAN","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","serviceProfileDesc":null,"serviceProfileID":null,"responseProfileDesc":null,"responseProfileID":null,"payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZSPC"}],"contracts":[{"description":"ONSITE FIELD SERVICE ENTITLEMENT","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":"M-F, 09:00-18:00, China","serviceProfileID":"CNO9X5","responseProfileDesc":"Next Business Day","responseProfileID":"WWONBD","payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZOSC"},{"description":"TELEPHONE SUPPORT ENTITLEMENT","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":"M-F, 09:00-18:00, China","serviceProfileID":"CNR13X7","responseProfileDesc":"WITHIN 2HRS OF CALL RECEIPT","responseProfileID":"WWR2HR","payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZRMC"},{"description":"PARTS COVERAGE ENTITLEMENT","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":null,"serviceProfileID":null,"responseProfileDesc":null,"responseProfileID":null,"payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZPCC"},{"description":"SOFTWARE UPDATES UPGRADES AND PATCHES","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":null,"serviceProfileID":null,"responseProfileDesc":null,"responseProfileID":null,"payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZWPC"}],"plan":"ADVANCED SUPPORT PLAN","planStart":"2016-04-16","planEnd":"2017-04-15","sds":"Onsite","hasActiveContract":"Y","meters":[{"id":"2100042216","descr":"NX COLOR SE - A4 METER","reading":"3786461","unit":"EA","date":"2017-03-24","main_counter":null,"source":"BACK OFFICE","new_reading":""}],"meter_history":[{"id":"2100042216","descr":"NX COLOR SE - A4 METER","reading":"3771195","unit":"EA","date":"2017-03-08","main_counter":null,"source":"BACK OFFICE","new_reading":""},{"id":"2100042216","descr":"NX COLOR SE - A4 METER","reading":"4","unit":"EA","date":"2017-01-17","main_counter":null,"source":"BACK OFFICE","new_reading":""},{"id":"2100042216","descr":"NX COLOR SE - A4 METER","reading":"3","unit":"EA","date":"2017-01-11","main_counter":null,"source":"BACK OFFICE","new_reading":""}]},{"ID":"8406545","Name":"GLOSSER","SN":"1518-FG","material":"1518-FG","SAPID":"741387","repair":"repair","svcDelivery":"O","compID":"4343625","sapProdID":"KH2226600","productHier":"1481,2232,2403","mf":"N","floorBldg":"BJ REN MING RI BAO MING YI YIN","addlAddress":null,"door":null,"remoteEOSL":"00000000","onsiteEOSL":"00000000","enabling_partner":null,"mfg_partner":"","distr_partner":"","resell_partner":"","direct_partner":"741387","corporate_partner":"","support_plans":[{"description":"ADVANCED SUPPORT PLAN","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","serviceProfileDesc":"M-F, 09:00-18:00, China","serviceProfileID":null,"responseProfileDesc":"3 Day Response","responseProfileID":null,"payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZSPC"}],"contracts":[{"description":"ONSITE FIELD SERVICE ENTITLEMENT","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":"M-F, 09:00-18:00, China","serviceProfileID":"CNO9X5","responseProfileDesc":"Next Business Day","responseProfileID":"WWONBD","payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZOSC"},{"description":"TELEPHONE SUPPORT ENTITLEMENT","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":"M-F, 09:00-18:00, China","serviceProfileID":"CNR13X7","responseProfileDesc":"WITHIN 2HRS OF CALL RECEIPT","responseProfileID":"WWR2HR","payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZRMC"},{"description":"PARTS COVERAGE ENTITLEMENT","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":null,"serviceProfileID":null,"responseProfileDesc":null,"responseProfileID":null,"payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZPCC"},{"description":"SOFTWARE UPDATES UPGRADES AND PATCHES","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":null,"serviceProfileID":null,"responseProfileDesc":null,"responseProfileID":null,"payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZWPC"}],"plan":"ADVANCED SUPPORT PLAN","planStart":"2016-04-16","planEnd":"2017-04-15","sds":"Onsite","hasActiveContract":"Y"},{"ID":"8406545","Name":"INTELLIGENT CALIBRATION SYSTEM","SN":"43946575","material":"43946575","SAPID":"741387","repair":"repair","svcDelivery":"O","compID":"4343627","sapProdID":"KH2245500","productHier":"1481,2231,9995","mf":"N","floorBldg":"BJ REN MING RI BAO MING YI YIN","addlAddress":null,"door":null,"remoteEOSL":"00000000","onsiteEOSL":"00000000","enabling_partner":null,"mfg_partner":"","distr_partner":"","resell_partner":"","direct_partner":"741387","corporate_partner":"","support_plans":[{"description":"ADVANCED SUPPORT PLAN","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","serviceProfileDesc":"M-F, 09:00-18:00, China","serviceProfileID":null,"responseProfileDesc":"3 Day Response","responseProfileID":null,"payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZSPC"}],"contracts":[{"description":"ONSITE FIELD SERVICE ENTITLEMENT","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":"M-F, 09:00-18:00, China","serviceProfileID":"CNO9X5","responseProfileDesc":"Next Business Day","responseProfileID":"WWONBD","payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZOSC"},{"description":"TELEPHONE SUPPORT ENTITLEMENT","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":"M-F, 09:00-18:00, China","serviceProfileID":"CNR13X7","responseProfileDesc":"WITHIN 2HRS OF CALL RECEIPT","responseProfileID":"WWR2HR","payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZRMC"},{"description":"PARTS COVERAGE ENTITLEMENT","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":null,"serviceProfileID":null,"responseProfileDesc":null,"responseProfileID":null,"payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZPCC"},{"description":"SOFTWARE UPDATES UPGRADES AND PATCHES","startDate":"2016-04-16","endDate":"2017-04-15","type":"Standard Contract","contractID":"8000102646","serviceProfileDesc":null,"serviceProfileID":null,"responseProfileDesc":null,"responseProfileID":null,"payerID":"741387","outsideEntId":"741387","status":"Active","zz_proctypecode":"ZWPC"}],"plan":"ADVANCED SUPPORT PLAN","planStart":"2016-04-16","planEnd":"2017-04-15","sds":"Onsite","hasActiveContract":"Y"}]},"status":1,"Site":[{"OrgName":"PEOPLE DAILY PRESS","street":"NO.2, JIN TAI W. RD, CHAOYANG DIST","city":"BEIJING","zip":"100733","province":"Beijing-010","country":"CN","custSAPId":"741387","orgID":583681,"manage":"Manage Contacts"}],"Payer":{"OrgName":"PEOPLE DAILY PRESS","street":"NO.2, JIN TAI W. RD, CHAOYANG DIST","city":"BEIJING","zip":"100733","province":"Beijing-010","country":"CN","custSAPId":"741387","orgID":583681,"manage":"Manage Contacts","OUTSIDEENTID":"741387","SAPID":"741387"}}' >
<input type='hidden' id='ibase_product_json' value='[[{"value":0,"label":"No Value"},{"value":4306,"label":"General Information","parentID":0,"selected"
:false,"hasChildren":0},{"value":2237,"label":"Color &amp; Screening","parentID":0,"selected":false,"hasChildren"
:1},{"value":1487,"label":"Computer-to-Plate (CTP)","parentID":0,"selected":false,"hasChildren":1},{"value"
:10010,"label":"Consumer Products","parentID":0,"selected":false,"hasChildren":1},{"value":2754,"label"
:"Controllers &amp; Data Stations","parentID":0,"selected":false,"hasChildren":1},{"value":2240,"label"
:"Data Preparation Software","parentID":0,"selected":false,"hasChildren":1},{"value":9153,"label":"Photofinishing
 Supplies","parentID":0,"selected":false,"hasChildren":1},{"value":1478,"label":"Plate Line Equipment"
,"parentID":0,"selected":false,"hasChildren":1},{"value":1482,"label":"Plates &amp; Consumables","parentID"
:0,"selected":true,"hasChildren":1},{"value":1481,"label":"Printers &amp; Presses","parentID":0,"selected"
:false,"hasChildren":1},{"value":1489,"label":"Proofing","parentID":0,"selected":false,"hasChildren"
:1},{"value":9289,"label":"Pro Lab Software","parentID":0,"selected":false,"hasChildren":0},{"value"
:1494,"label":"Retail Products","parentID":0,"selected":false,"hasChildren":1},{"value":4275,"label"
:"Remote Support Tools","parentID":0,"selected":false,"hasChildren":1},{"value":1479,"label":"Scanners"
,"parentID":0,"selected":false,"hasChildren":1},{"value":10540,"label":"Touch Screen Sensors","parentID"
:0,"selected":false,"hasChildren":1},{"value":4036,"label":"Unified Workflow","parentID":0,"selected"
:false,"hasChildren":1},{"value":10585,"label":"Cloud Services","parentID":0,"selected":false,"hasChildren"
:1},{"value":1493,"label":"Third-Party Products","parentID":0,"selected":false,"hasChildren":1}],[{"value"
:2235,"label":"Flexographic","parentID":"1482","selected":false,"hasChildren":1},{"value":9997,"label"
:"Letterpress","parentID":"1482","selected":false,"hasChildren":1},{"value":9998,"label":"Offset","parentID"
:"1482","selected":true,"hasChildren":1}],[{"value":2404,"label":"Analog Offset","parentID":"9998","selected"
:false,"hasChildren":0},{"value":2409,"label":"Thermal Offset","parentID":"9998","selected":true,"hasChildren"
:1},{"value":4114,"label":"Violet Offset","parentID":"9998","selected":false,"hasChildren":0}],[{"value"
:2410,"label":"CAPRICORN GT","parentID":"2409","selected":false,"hasChildren":0},{"value":2411,"label"
:"DITP GOLD","parentID":"2409","selected":false,"hasChildren":0},{"value":2412,"label":"ELECTRA EXCEL
 HRO\/HRL","parentID":"2409","selected":false,"hasChildren":0},{"value":10564,"label":"ELECTRAMAX (T-68
)","parentID":"2409","selected":false,"hasChildren":0},{"value":2416,"label":"ELECTRA XD","parentID"
:"2409","selected":false,"hasChildren":0},{"value":2413,"label":"EXTHERMO TP-R","parentID":"2409","selected"
:false,"hasChildren":0},{"value":4108,"label":"EXTHERMO TP-U","parentID":"2409","selected":false,"hasChildren"
:0},{"value":4109,"label":"EXTHERMO TP-W","parentID":"2409","selected":false,"hasChildren":0},{"value"
:4110,"label":"EXTHERMO TP-Z","parentID":"2409","selected":false,"hasChildren":0},{"value":4032,"label"
:"EXTHERMO TN-R","parentID":"2409","selected":false,"hasChildren":0},{"value":4025,"label":"NS Digital
 Newspaper","parentID":"2409","selected":false,"hasChildren":0},{"value":4023,"label":"P0072","parentID"
:"2409","selected":false,"hasChildren":0},{"value":4031,"label":"PF-N","parentID":"2409","selected":true
,"hasChildren":0},{"value":4030,"label":"PF-N2 (T53)","parentID":"2409","selected":false,"hasChildren"
:0},{"value":10006,"label":"SONORA NEWS","parentID":"2409","selected":false,"hasChildren":0},{"value"
:4104,"label":"SONORA XP (T57\/T60)","parentID":"2409","selected":false,"hasChildren":0},{"value":4026
,"label":"SWORD EXCEL\/with Ultra Grain\/P0045","parentID":"2409","selected":false,"hasChildren":0},
{"value":4029,"label":"SWORD J (TCC-730)","parentID":"2409","selected":false,"hasChildren":0},{"value"
:4028,"label":"SWORD JL","parentID":"2409","selected":false,"hasChildren":0},{"value":4024,"label":"SWORD
 ULTRA","parentID":"2409","selected":false,"hasChildren":0},{"value":4022,"label":"TCC-605","parentID"
:"2409","selected":false,"hasChildren":0},{"value":4102,"label":"TCC-607","parentID":"2409","selected"
:false,"hasChildren":0},{"value":4103,"label":"THERMAL DIRECT","parentID":"2409","selected":false,"hasChildren"
:0},{"value":4105,"label":"THERMAL GOLD","parentID":"2409","selected":false,"hasChildren":0},{"value"
:4106,"label":"THERMAL News GOLD","parentID":"2409","selected":false,"hasChildren":0},{"value":9083,"label"
:"THERMAL News PT","parentID":"2409","selected":false,"hasChildren":0},{"value":4107,"label":"THERMAL
 PLATINUM","parentID":"2409","selected":false,"hasChildren":0},{"value":4111,"label":"TRILLIAN SP","parentID"
:"2409","selected":false,"hasChildren":0}],[]]' >

<input type='hidden' id='hier_json' value='[[{"value":0,"label":"No Value"},{"value":10250,"label":"Existing Media\/Chemistry Flip","parentID":0
,"selected":false,"hasChildren":1},{"value":10095,"label":"Kodak Initiated","parentID":0,"selected":false
,"hasChildren":1},{"value":10252,"label":"Media New Installation","parentID":0,"selected":false,"hasChildren"
:1},{"value":10254,"label":"Media Non Reactive","parentID":0,"selected":false,"hasChildren":1},{"value"
:10256,"label":"Media Reactive - Coating","parentID":0,"selected":false,"hasChildren":1},{"value":10277
,"label":"Media Reactive - Delivery","parentID":0,"selected":false,"hasChildren":1},{"value":10291,"label"
:"Media Reactive - Output Device","parentID":0,"selected":false,"hasChildren":1},{"value":10292,"label"
:"Media Reactive - Packaging","parentID":0,"selected":false,"hasChildren":1},{"value":10293,"label":"Media
 Reactive - Physical","parentID":0,"selected":false,"hasChildren":1},{"value":10317,"label":"Media Reactive
 - Press","parentID":0,"selected":false,"hasChildren":1},{"value":10340,"label":"Media Reactive - Processing"
,"parentID":0,"selected":false,"hasChildren":1},{"value":10360,"label":"Media Sales Support","parentID"
:0,"selected":false,"hasChildren":1},{"value":10361,"label":"Media Training","parentID":0,"selected"
:false,"hasChildren":1},{"value":10380,"label":"Media Travel","parentID":0,"selected":false,"hasChildren"
:1},{"value":10372,"label":"Output Device Reactive","parentID":0,"selected":false,"hasChildren":1},{"value"
:10374,"label":"PLE Reactive","parentID":0,"selected":false,"hasChildren":1},{"value":10376,"label":"Press
 Reactive","parentID":0,"selected":false,"hasChildren":1},{"value":10378,"label":"SOP Variance Reactive"
,"parentID":0,"selected":false,"hasChildren":1}]]' > 
