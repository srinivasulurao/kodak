<rn:meta controller_path="custom/CIHSearch/CustomerSearch" js_path="custom/CIHSearch/CustomerSearch" base_css="custom/CIHSearch/CustomerSearch" presentation_css="widgetCss/SimpleSearch.css" compatibility_set="November '09+"/>

<link rel="stylesheet" type="text/css" href="/euf/assets/css/yui/calendar.css" />

<? $this->addJavaScriptInclude(getYUICodePath('yahoo-dom-event/yahoo-dom-event.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('dragdrop/dragdrop-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('element/element-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('datasource/datasource-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('datatable/datatable-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('tabview/tabview-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('calendar/calendar-min.js'));?>

<form id="rn_<?=$this->instanceID;?>_SearchForm" onsubmit="return false;">


<div id="rn_<?=$this->instanceID;?>" class="rn_KeywordText2">
Partner Type&nbsp;<? if($this->data['js']['internal_user']=='Y') print("(Logged in as Internal Kodak User)"); ?></br>
<table style="width:100%">
<tr>
  <td><rn:widget path="custom/CIHFunction/PartnerTypeList" name="partnerTypeSelect2" /></td>

<? if($this->data['js']['internal_user']=='N'): ?>
  <td nowrap="nowrap" valign="bottom"><a id="rn_<?=$this->instanceID;?>_advSearchPanelTrigger" style="cursor:pointer">Show/Hide Advanced Search</a>
  </td>
<? endif;?>

<? if($this->data['js']['internal_user']=='Y'): ?>
  <td>&nbsp;</td>
  </td>
<? endif;?>

  <td valign="bottom" align="right" width="850px" style="margin-right:200px;">
    <input type="submit" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> name="rn_<?=$this->instanceID;?>_CustSubmit" id="rn_<?=$this->instanceID;?>_CustSubmit" class="Button" value="Search" />
  </td>
</tr>
</table>
<? if($this->data['js']['internal_user']=='Y'): ?>
<table>
 <tr>
    <td>&nbsp;</td>
    <td><label for="rn_<?=$this->instanceID;?>_Text">Site Customer ID</label></td>
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
    <td><label for="rn_<?=$this->instanceID;?>_Text">Site Customer ID</label></td>
    <td><label for="rn_<?=$this->instanceID;?>_Text">Customer Name</label></td>
    <td colspan="2"><label for="rn_<?=$this->instanceID;?>_Text">Material ID</label></td>
 </tr>
 <tr>
    <td>&nbsp;</td>
    <td><input id="rn_<?=$this->instanceID;?>_CustIDField" name="rn_<?=$this->instanceID;?>_CustIDField" type="text" maxlength="255" value="" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> />
    <td><input id="rn_<?=$this->instanceID;?>_CustNameField" name="rn_<?=$this->instanceID;?>_CustNameField" type="text" maxlength="255" value="" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> /></td>
    <td><input id="rn_<?=$this->instanceID;?>_CustMaterial" name="rn_<?=$this->instanceID;?>_CustMaterial" type="text" maxlength="255" value="" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> /></td>
    <td>&nbsp;</td>
 </tr>
 <tr>
   <td><label for="rn_<?=$this->instanceID;?>_Text">Street</label></td>
   <td><label for="rn_<?=$this->instanceID;?>_Text">City</label></td>
   <td><label for="rn_<?=$this->instanceID;?>_Text">Postal Code</label></td>
   <td><label for="rn_<?=$this->instanceID;?>_Text">Country</label></td>
   <td><label for="rn_<?=$this->instanceID;?>_Text">Region/Province/State</label></td>
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

  <rn:widget path="CIHFunction/Accordion" name="accordionSites2" visible="true" expanded="false" item_to_toggle="panelSites2" label_header="My Sites" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelSites2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_sitetable2"></div>

    </div>
  </div>
</div>

<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionProducts2" visible="true" expanded="false" item_to_toggle="panelProducts2" label_header="Product Identifier" />

  <div id="rn_<?=$this->instanceID;?>_containerProducts" class="rn_FormPanel">

    <div id="panelProducts2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="rn_<?=$this->instanceID;?>_div_producttable"></div>

     </div>
  </div>
</div>


<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionComponents2" visible="false" expanded="false" item_to_toggle="panelComponent2" label_header="Component" />

  <div id="rn_<?=$this->instanceID;?>_containerComponent" class="rn_FormPanel">

    <div id="panelComponent2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_componentstable"></div>

    </div>
  </div>
</div>

<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionComponentDetails2" visible="false" expanded="false" item_to_toggle="panelDetails2" label_header="Component Details" />

  <div id="rn_<?=$this->instanceID;?>_containerDetails" class="rn_FormPanel99">

    <div id="panelDetails2" style="width=100%;">
      <!-- Panel content goes here -->
      <div id="tvcontainer2" class="yui-navset">
      <ul class="yui-nav">
          <li class="selected"><a href="#tab1"><em>Entitlement</em></a></li>
          <li><a href="#tab2"><em>Site Information</em></a></li>
          <li><a href="#tab3"><em>Service Contract Payer Information</em></a></li>
          <li><a href="#tab4"><em>Meter View</em></a></li>
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
               <rn:widget path="CIHFunction/Accordion" name="accordionMeterHistory2" visible="true" expanded="true" item_to_toggle="panelMeterHistory2" label_header="Meter History" />

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
  <rn:widget path="CIHFunction/Accordion" name="accordionManageContacts2" visible="false" expanded="true" item_to_toggle="panelManageContacts2" label_header="Manage Contacts" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelManageContacts2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/ManageContacts" panel_name="accordionManageContacts2" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionRepairRequest2" visible="false" expanded="true" item_to_toggle="panelRepairRequest2" label_header="Repair Request" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelRepairRequest2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/RepairRequest" panel_name="accordionRepairRequest2" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionIbaseUpdate2" visible="false" expanded="true" item_to_toggle="panelIbaseUpdate2" label_header="Ibase Update" />

  <div id="rn_<?=$this->instanceID;?>_containerIbaseUpdate" class="rn_FormPanel">

    <div id="panelIbaseUpdate2" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/IBaseUpdate" widget_index="0" panel_name="accordionIbaseUpdate2" />  
    </div>
  </div>
</div>

<input id="rn_<?=$this->instanceID;?>_debug" name="rn_<?=$this->instanceID;?>_debug" type="text" size="120" maxlength="255" value="" />
