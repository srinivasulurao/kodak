<rn:meta controller_path="custom/CIHSearch/SimpleProductSearch" js_path="custom/CIHSearch/SimpleProductSearch" base_css="custom/CIHSearch/SimpleProductSearch" presentation_css="widgetCss/SimpleSearch.css" compatibility_set="November '09+"/>
<? $this->addJavaScriptInclude(getYUICodePath('yahoo-dom-event/yahoo-dom-event.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('dragdrop/dragdrop-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('element/element-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('datasource/datasource-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('datatable/datatable-min.js'));?>
<? $this->addJavaScriptInclude(getYUICodePath('tabview/tabview-min.js'));?>



<div id="rn_<?=$this->instanceID;?>_container" class="rn_FormPanel99">
<div id="rn_<?=$this->instanceID;?>" >
     <form id="rn_<?=$this->instanceID;?>_SearchForm" onsubmit="return false;">

<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionSearch" visible="true" expanded="true" item_to_toggle="panelSearch" label_header="Search Filters" />

  <div id="rn_<?=$this->instanceID;?>_containerSearch" class="rn_FormPanel">

    <div id="panelSearch" class="rn_Accordion_content">


<table width="100%">
<tr>
  <td><rn:widget path="custom/CIHFunction/PartnerTypeList" name="partnerTypeSelect1" /></td>
  <td><select name="selProdSearchBy" id="selProdSearchBy">
       <option value="knum">Product Identifier</option>
       <option value="sn">Serial Number</option>
      </select>
  </td>
  <td><input type="text" id="rn_<?=$this->instanceID;?>_SearchField" name="rn_<?=$this->instanceID;?>_SearchField"  class="rn_SearchField" maxlength="30" value="<?=$this->data['attrs']['label_hint'];?>" title="<?=$this->data['attrs']['label_hint'];?>" <?=tabIndex($this->data['attrs']['tabindex'], 1);?>/>
  </td>
  <td><input type="submit" id="rn_<?=$this->instanceID;?>_Submit" class="Button" name="rn_<?=$this->instanceID;?>_Submit" value="Search" /></td>
</tr>
</table>

   </div>
  </div>
</div>

          <? /**IE needs extra input element for form submit on enter*/ ?>
          <? if($this->data['isIE']): ?>
           <label for="rn_<?=$this->instanceID;?>_HiddenInput" class="rn_Hidden">&nbsp;</label>
           <input id="rn_<?=$this->instanceID;?>_HiddenInput" type="text" class="rn_Hidden"/>
          <? endif;?>
          
      </form>
</div>

<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionSites" visible="true" expanded="false" item_to_toggle="panelSites" label_header="My Sites" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelSites" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_sitetable"></div>

    </div>
  </div>
</div>

<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionProducts" visible="true" expanded="false" item_to_toggle="panelProducts" label_header="Product Identifier" />

  <div id="rn_<?=$this->instanceID;?>_containerProducts" class="rn_FormPanel">

    <div id="panelProducts" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_producttable"></div>

     </div>
  </div>
</div>


<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionComponents" visible="false" expanded="false" item_to_toggle="panelComponent" label_header="Component" />

  <div id="rn_<?=$this->instanceID;?>_containerComponent" class="rn_FormPanel">

    <div id="panelComponent" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_subtable"></div>

    </div>
  </div>
</div>

<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionComponentDetails" visible="false" expanded="false" item_to_toggle="panelDetails" label_header="Component Details" />

  <div id="rn_<?=$this->instanceID;?>_containerDetails" class="rn_FormPanel99">

    <div id="panelDetails" style="width=100%;">

      <!-- Panel content goes here -->
      <div id="tvcontainer" class="yui-navset"> 
      <ul class="yui-nav">
          <li class="selected"><a href="#tab1"><em>Entitlements</em></a></li>
          <li><a href="#tab2"><em>Site Information</em></a></li>
          <li><a href="#tab3"><em>Service Contract Payer Information</em></a></li>
          <li><a href="#tab4"><em>Meter View</em></a></li>
      </ul>            
      <div class="yui-content">
        <div id="div_contracts"></div>
        <div><p><table id="equipment_site_info" name="equipment_site_info"></table></p></div>
        <div><p><table id="contract_payer_info" name="contract_payer_info"></table></p></div>

        <div>
          <table id="meters" name="meters">
           <tr><td>
               <rn:widget path="output/CurrentMeter" />
           </td></tr>
           <tr><td>
             <div class="rn_Accordion_container">
               <rn:widget path="CIHFunction/Accordion" name="accordionMeterHistory" visible="true" expanded="true" item_to_toggle="panelMeterHistory" label_header="Meter History" />

                <div id="rn_<?=$this->instanceID;?>_containerMeterHistory" class="rn_FormPanel99">

                  <div id="panelMeterHistory" class="rn_Accordion_content">
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
  <rn:widget path="CIHFunction/Accordion" name="accordionManageContacts" visible="false" expanded="true" item_to_toggle="panelManageContacts" label_header="Manage Contacts" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelManageContacts" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/ManageContacts" panel_name="accordionManageContacts" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionRepairRequest" visible="false" expanded="true" item_to_toggle="panelRepairRequest" label_header="Repair Request" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelRepairRequest" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/RepairRequest" panel_name="accordionRepairRequest" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionIbaseUpdate" visible="false" expanded="true" item_to_toggle="panelIbaseUpdate" label_header="Ibase Update" />

  <div id="rn_<?=$this->instanceID;?>_containerIbaseUpdate" class="rn_FormPanel">

    <div id="panelIbaseUpdate" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/IBaseUpdate" widget_index="1" panel_name="accordionIbaseUpdate" />
    </div>
  </div>
</div>
</div>
