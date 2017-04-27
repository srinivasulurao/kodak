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
  <rn:widget path="CIHFunction/Accordion" name="accordionManageContacts2" visible="false" expanded="true" item_to_toggle="panelManageContacts2" label_header="#rn:php:$cih_lang_msg_base_array['managecontacts']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelManageContacts2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/ManageContacts" panel_name="accordionManageContacts2" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionRepairRequest2" visible="false" expanded="true" item_to_toggle="panelRepairRequest2" label_header="#rn:php:$cih_lang_msg_base_array['repairrequest']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelRepairRequest2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/RepairRequest" panel_name="accordionRepairRequest2" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionIbaseUpdate2" visible="false" expanded="true" item_to_toggle="panelIbaseUpdate2" label_header="#rn:php:$cih_lang_msg_base_array['ibaseupdate']#" />

  <div id="rn_<?=$this->instanceID;?>_containerIbaseUpdate" class="rn_FormPanel">

    <div id="panelIbaseUpdate2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/IBaseUpdate" widget_index="0" panel_name="accordionIbaseUpdate2" />  
    </div>
  </div>
</div>

<input type='hidden' id='sample_json2' value='{"0":{"ibase_list":[{"OrgName":"BOOKLET BINDING INC","street":"710 KIMBERLY DR","city":"CAROL STREAM","zip":"60188","province":"Illinois-IL","country":"US","custSAPId":"600000","orgID":347075,"manage":"Manage Contacts","ibaseID":"1881722","name":"BOOKLET BINDING INC","partnerfunction":"2","customerID":"600000","partnerID":"824492"}]},"status":1}'>
<input type="hidden" id="product_json" value='{"0":{"products":[{"componentID":"1881723","description":"S200 CTLR","knum":"","sn":"60","material":"COI0191382"
,"contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002"
,"requestingPartner":"600000"},{"componentID":"1881725","description":"MULTIPLE PRNTR INTF","knum":"0000001G"
,"sn":"1","material":"8682189","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881727","description":"MULTIPLE
 PRNTR INTF","knum":"0000002D","sn":"2","material":"8682189","contract":"","startDate":"","endDate":""
,"repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881729","description":"MULTIPLE PRNTR INTF","knum":"0000003E","sn":"3","material":"8682189","contract"
:"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881731","description":"MULTIPLE PRNTR INTF","knum":"0000004B","sn":"4","material"
:"8682189","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType"
:"00000002","requestingPartner":"600000"},{"componentID":"1881741","description":"5120\/12 FT PRINTING
 SYSTEM","knum":"00000476","sn":"476","material":"IPS-1006-03","contract":"","startDate":"","endDate"
:"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"}
,{"componentID":"1881733","description":"MULTIPLE PRNTR INTF","knum":"0000005B","sn":"5","material":"8682189"
,"contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002"
,"requestingPartner":"600000"},{"componentID":"1881735","description":"MULTIPLE PRNTR INTF","knum":"0000006A"
,"sn":"6","material":"8682189","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881737","description":"MULTIPLE
 PRNTR INTF","knum":"0000007A","sn":"7","material":"8682189","contract":"","startDate":"","endDate":""
,"repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881739","description":"CD120","knum":"00000399","sn":"399","material":"8717688","contract":"","startDate"
:"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881747","description":"5120\/12 FT PRINTING SYSTEM","knum":"00000591","sn"
:"591","material":"IPS-1006-03","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881743","description":"S100
 CTLR","knum":"00000492","sn":"492","material":"COI0191376","contract":"","startDate":"","endDate":""
,"repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881745","description":"S100 CTLR","knum":"00000576","sn":"576","material":"COI0191376","contract"
:"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881749","description":"S100 CTLR","knum":"00000768","sn":"768","material"
:"COI0191376","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType"
:"00000002","requestingPartner":"600000"},{"componentID":"1881751","description":"S100 CTLR","knum":"00000802"
,"sn":"802","material":"COI0191376","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881753","description":"5240
\/24 FT PRINTING SYSTEM","knum":"00000986","sn":"986","material":"IPS-1006-12","contract":"","startDate"
:"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881755","description":"5240\/24 FT PRINTING SYSTEM","knum":"00001332","sn"
:"1332","material":"IPS-1006-12","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881759","description":"5240
\/24 FT PRINTING SYSTEM","knum":"00001386","sn":"1386","material":"IPS-1006-12","contract":"","startDate"
:"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881761","description":"5240\/24 FT PRINTING SYSTEM","knum":"00001399","sn"
:"1399","material":"IPS-1006-12","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881757","description":"S200
 CTLR","knum":"00001347","sn":"1347","material":"COI0191382","contract":"","startDate":"","endDate":""
,"repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881771","description":"5240\/24 FT PRINTING SYSTEM","knum":"00003067","sn":"3067","material":"IPS-1006-12"
,"contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002"
,"requestingPartner":"600000"},{"componentID":"1881765","description":"5120\/12 FT PRINTING SYSTEM","knum"
:"00001975","sn":"1975","material":"IPS-1006-03","contract":"","startDate":"","endDate":"","repair":"repair"
,"hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881767"
,"description":"5120\/12 FT PRINTING SYSTEM","knum":"00001976","sn":"1976","material":"IPS-1006-03","contract"
:"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881769","description":"S200 CTLR","knum":"0000244B","sn":"244","material"
:"COI0191382","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType"
:"00000002","requestingPartner":"600000"},{"componentID":"1881763","description":"S200 CTLR","knum":"0000195A"
,"sn":"195","material":"COI0191382","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881775","description":"5240
\/24 FT PRINTING SYSTEM","knum":"00003119","sn":"3119","material":"IPS-1006-12","contract":"","startDate"
:"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881773","description":"5240\/24 FT PRINTING SYSTEM","knum":"00003071","sn"
:"3071","material":"IPS-1006-12","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881779","description":"S100
 CTLR","knum":"0000367B","sn":"367","material":"COI0191376","contract":"","startDate":"","endDate":""
,"repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881777","description":"STCKR CTRLR","knum":"0000366A","sn":"366","material":"8115719","contract":""
,"startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881781","description":"CS400","knum":"00004012","sn":"4012","material":"1087881"
,"contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002"
,"requestingPartner":"600000"},{"componentID":"1881789","description":"CS410 SYSTEM CONTROLLER","knum"
:"00005013","sn":"5013","material":"IPS-1001-01","contract":"","startDate":"","endDate":"","repair":"repair"
,"hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881791"
,"description":"CS410 SYSTEM CONTROLLER","knum":"00005015","sn":"5015","material":"IPS-1001-01","contract"
:"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881785","description":"S100 CTLR","knum":"0000442A","sn":"442","material"
:"COI0191376","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType"
:"00000002","requestingPartner":"600000"},{"componentID":"1881783","description":"CS400","knum":"00004226"
,"sn":"4226","material":"1087881","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881787","description":"CD130
 4IN","knum":"00004516","sn":"4516","material":"8268567","contract":"","startDate":"","endDate":"","repair"
:"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881797","description":"5240\/24 FT PRINTING SYSTEM","knum":"0000968A","sn":"968","material":"IPS-1006-12"
,"contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002"
,"requestingPartner":"600000"},{"componentID":"1881793","description":"S100 CTLR","knum":"0000539A","sn"
:"539","material":"COI0191376","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881795","description":"DP7122"
,"knum":"00007008","sn":"7008","material":"1636992","contract":"","startDate":"","endDate":"","repair"
:"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881799","description":"CS220","knum":"0001021B","sn":"1021","material":"1419860","contract":"","startDate"
:"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881801","description":"CD120","knum":"0001021C","sn":"1021","material":"8717688"
,"contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002"
,"requestingPartner":"600000"},{"componentID":"1881811","description":"S200 CTLR","knum":"0001316A","sn"
:"1316","material":"COI0191382","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881803","description":"CS220"
,"knum":"0001037A","sn":"1037","material":"1419860","contract":"","startDate":"","endDate":"","repair"
:"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881805","description":"CD120","knum":"0001037C","sn":"1037","material":"8717688","contract":"","startDate"
:"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881807","description":"CS220","knum":"0001108A","sn":"1108","material":"1419860"
,"contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002"
,"requestingPartner":"600000"},{"componentID":"1881809","description":"CD120","knum":"0001108B","sn"
:"1108","material":"8717688","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881819","description":"5120
\/12 FT PRINTER","knum":"0001553A","sn":"1553","material":"IPS-1006-01","contract":"","startDate":""
,"endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881823","description":"5240\/24 FT PRINTING SYSTEM","knum":"0003076C","sn"
:"3076","material":"IPS-1006-12","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881821","description":"5120
\/12 FT PRINTING SYSTEM","knum":"0001973A","sn":"1973","material":"IPS-1006-03","contract":"","startDate"
:"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881815","description":"S200 CTLR","knum":"0001391A","sn":"1391","material"
:"COI0191382","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType"
:"00000002","requestingPartner":"600000"},{"componentID":"1881817","description":"S200 CTLR","knum":"0001401A"
,"sn":"1401","material":"COI0191382","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881833","description":"6240-S300
 SINGLE CHANNEL","knum":"00320044","sn":"320044","material":"IPS-1002-07","contract":"","startDate":""
,"endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881835","description":"6240-S300 SINGLE CHANNEL","knum":"00320155","sn":"320155"
,"material":"IPS-1002-07","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881827","description":"6240-S500
 SINGLE CHANNEL","knum":"00052184","sn":"52184","material":"IPS-1002-08","contract":"","startDate":""
,"endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881829","description":"6240-S500 SINGLE CHANNEL","knum":"00052185","sn":"52185"
,"material":"IPS-1002-08","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881831","description":"6240-S500
 SINGLE CHANNEL","knum":"00052328","sn":"52328","material":"IPS-1002-08","contract":"","startDate":""
,"endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner"
:"600000"},{"componentID":"1881839","description":"MULTIPLE PRNTR INTF","knum":"01332MPI","sn":"1332MPI"
,"material":"8682189","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881841","description":"MULTIPLE
 PRNTR INTF","knum":"01973MPI","sn":"1973MPI","material":"8682189","contract":"","startDate":"","endDate"
:"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"}
,{"componentID":"1881843","description":"MULTIPLE PRNTR INTF","knum":"01976MPI","sn":"1976MPI","material"
:"8682189","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType"
:"00000002","requestingPartner":"600000"},{"componentID":"1881845","description":"MULTIPLE PRNTR INTF"
,"knum":"03067MPI","sn":"3067MPI","material":"8682189","contract":"","startDate":"","endDate":"","repair"
:"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881837","description":"6240-S500 SINGLE CHANNEL","knum":"0052598A","sn":"52598","material":"IPS-1002-08"
,"contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002"
,"requestingPartner":"600000"},{"componentID":"1881847","description":"MULTIPLE PRNTR INTF","knum":"03076MPI"
,"sn":"3076MPI","material":"8682189","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract"
:"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID":"1881849","description":"MULTIPLE
 PRNTR INTF","knum":"03099MPI","sn":"3099MPI","material":"8682189","contract":"","startDate":"","endDate"
:"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"}
,{"componentID":"1881851","description":"MULTIPLE PRNTR INTF","knum":"03233MPI","sn":"3233MPI","material"
:"8682189","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType"
:"00000002","requestingPartner":"600000"},{"componentID":"1881853","description":"6240-S500 SINGLE CHANNEL"
,"knum":"05530008","sn":"5530008","material":"IPS-1002-08","contract":"","startDate":"","endDate":""
,"repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881855","description":"6240-S500 SINGLE CHANNEL","knum":"05530009","sn":"5530009","material":"IPS-1002-08"
,"contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002"
,"requestingPartner":"600000"},{"componentID":"1881863","description":"5240\/24 FT PRINTING SYSTEM","knum"
:"10603233","sn":"219990700210603233","material":"IPS-1006-12","contract":"","startDate":"","endDate"
:"","repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"}
,{"componentID":"1881857","description":"6240-S500 SINGLE CHANNEL","knum":"05530016","sn":"5530016","material"
:"IPS-1002-08","contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType"
:"00000002","requestingPartner":"600000"},{"componentID":"1881859","description":"6240-S500 SINGLE CHANNEL"
,"knum":"05530045","sn":"5530045","material":"IPS-1002-08","contract":"","startDate":"","endDate":""
,"repair":"repair","hasActiveContract":"N","partnerType":"00000002","requestingPartner":"600000"},{"componentID"
:"1881861","description":"6240-S500 SINGLE CHANNEL","knum":"05530046","sn":"5530046","material":"IPS-1002-08"
,"contract":"","startDate":"","endDate":"","repair":"repair","hasActiveContract":"N","partnerType":"00000002"
,"requestingPartner":"600000"}]},"status":1}' >

<input type='hidden' id='ibase_json' value='{"0":{"SAPID":"600000","PAYERID":null,"OUTSIDEENTID":null,"products":[{"ID":null,"Name":"S200 CTLR","SN":"60","material":"60","SAPID":"600000","repair":"repair","svcDelivery":"O","compID":"1881723","sapProdID":"COI0191382","productHier":"2754,2431,9484","mf":"Y","floorBldg":null,"addlAddress":null,"door":null,"remoteEOSL":"20080430","onsiteEOSL":"20080430","enabling_partner":null,"mfg_partner":"","distr_partner":"","resell_partner":"","direct_partner":"600000","corporate_partner":"","support_plans":null,"contracts":null,"plan":null,"planStart":"","planEnd":"","sds":"Blank","hasActiveContract":"N"}]},"status":1,"Site":[{"OrgName":"BOOKLET BINDING INC","street":"710 KIMBERLY DR","city":"CAROL STREAM","zip":"60188","province":"Illinois-IL","country":"US","custSAPId":"600000","orgID":347075,"manage":"Manage Contacts"}],"Payer":{"OrgName":null,"street":null,"city":null,"zip":null,"province":null,"country":null,"custSAPId":null,"orgID":null,"manage":"Manage Contacts","OUTSIDEENTID":null,"SAPID":null}}' >