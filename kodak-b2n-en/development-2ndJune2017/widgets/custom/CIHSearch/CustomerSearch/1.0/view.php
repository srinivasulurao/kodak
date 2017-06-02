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
<table style='border: 1px solid lightgrey;width: 99.2% !important;padding: 10px;background: lightblue;'>
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


<div class="rn_Accordion_container rn_Hidden">

  <rn:widget path="CIHFunction/Accordion" name="accordionComponents2" visible="false" expanded="false" item_to_toggle="panelComponent2" label_header="#rn:php:$cih_lang_msg_base_array['component']#" />

  <div id="rn_<?=$this->instanceID;?>_containerComponent" class="rn_FormPanel">

    <div id="panelComponent2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_componentstable"></div>

    </div>
  </div>
</div>

<div class="rn_Accordion_container rn_Hidden">

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
               <rn:widget path="CIHFunction/Accordion" name="accordionMeterHistory2" visible="false" expanded="true" item_to_toggle="panelMeterHistory2" label_header="#rn:php:$cih_lang_msg_base_array['pidmeterhistory']#" />

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

<div class="rn_Accordion_container rn_Hidden">
  <rn:widget path="CIHFunction/Accordion" name="accordionManageContacts2" visible="false" expanded="false" item_to_toggle="panelManageContacts2" label_header="#rn:php:$cih_lang_msg_base_array['managecontacts']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelManageContacts2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/ManageContacts" panel_name="accordionManageContacts2" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container rn_Hidden">
  <rn:widget path="CIHFunction/Accordion" name="accordionRepairRequest2" visible="false" expanded="false" item_to_toggle="panelRepairRequest2" label_header="#rn:php:$cih_lang_msg_base_array['repairrequest']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelRepairRequest2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/RepairRequest" panel_name="accordionRepairRequest2" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container rn_Hidden">
  <rn:widget path="CIHFunction/Accordion" name="accordionIbaseUpdate2" visible="false" expanded="false" item_to_toggle="panelIbaseUpdate2" label_header="#rn:php:$cih_lang_msg_base_array['ibaseupdate']#" />

  <div id="rn_<?=$this->instanceID;?>_containerIbaseUpdate" class="rn_FormPanel">

    <div id="panelIbaseUpdate2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/IBaseUpdate" panel_name="accordionIbaseUpdate2" widget_index="0" /> 
    </div>
  </div>
</div>
