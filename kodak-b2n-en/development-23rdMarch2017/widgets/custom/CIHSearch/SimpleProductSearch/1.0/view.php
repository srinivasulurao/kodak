<?php
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
<!-- <div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">

</div> -->

<div id="rn_<?=$this->instanceID;?>_container" class="rn_FormPanel99">
<div id="rn_<?=$this->instanceID;?>" class="<?= $this->classList ?>">
     <form id="rn_<?=$this->instanceID;?>_SearchForm" onsubmit="return false;">

<div class="rn_Accordion_container">

  <rn:widget path="custom/CIHFunction/Accordion" name="accordionSearch" visible="false" expanded="true" item_to_toggle="panelSearch" label_header="#rn:php:$cih_lang_msg_base_array['searchfilters']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSearch" class="rn_FormPanel">

    <div id="panelSearch" class="rn_Accordion_content">


<table width="100%">
<tr>
  <td><rn:widget path="custom/CIHFunction/PartnerTypeList" name="partnerTypeSelect1" /></td>
  <td><select name="selProdSearchBy" id="selProdSearchBy">
       <option value="knum"><? echo $cih_lang_msg_base_array['pic_prodidentifier']; ?></option>
       <option value="sn"><? echo $cih_lang_msg_base_array['serialnumber']; ?></option>
      </select>
  </td>
  <td><input type="text" id="rn_<?=$this->instanceID;?>_SearchField" name="rn_<?=$this->instanceID;?>_SearchField"  class="rn_SearchField" maxlength="30" placeholder="<?=$this->data['attrs']['label_hint'];?>" title="<?=$this->data['attrs']['label_hint'];?>" <?=tabIndex($this->data['attrs']['tabindex'], 1);?>/>
  </td>
  <td><input type="submit" id="rn_<?=$this->instanceID;?>_Submit" class="Button" name="rn_<?=$this->instanceID;?>_Submit" value="<? echo $cih_lang_msg_base_array['search'];?>" /></td>
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

  <rn:widget path="CIHFunction/Accordion" name="accordionSites" visible="true" expanded="false" item_to_toggle="panelSites" label_header="#rn:php:$cih_lang_msg_base_array['mysites']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelSites" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_sitetable"></div>
    </div>
  </div>
</div>

<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionProducts" visible="true" expanded="false" item_to_toggle="panelProducts" label_header="#rn:php:$cih_lang_msg_base_array['prodidentifier']#" />

  <div id="rn_<?=$this->instanceID;?>_containerProducts" class="rn_FormPanel">

    <div id="panelProducts" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_producttable"></div>

     </div>
  </div>
</div>


<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionComponents" visible="false" expanded="false" item_to_toggle="panelComponent" label_header="#rn:php:$cih_lang_msg_base_array['component']#" />

  <div id="rn_<?=$this->instanceID;?>_containerComponent" class="rn_FormPanel">

    <div id="panelComponent" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <div id="div_subtable"></div>

    </div>
  </div>
</div>

<div class="rn_Accordion_container">

  <rn:widget path="CIHFunction/Accordion" name="accordionComponentDetails" visible="false" expanded="false" item_to_toggle="panelDetails" label_header="#rn:php:$cih_lang_msg_base_array['componentdetails']#" />

  <div id="rn_<?=$this->instanceID;?>_containerDetails" class="rn_FormPanel99">

    <div id="panelDetails" style="width=100%;">

      <!-- Panel content goes here -->
      <div id="tvcontainer" class="yui-navset"> 
      <ul class="yui-nav">
          <li class="selected"><a href="#tab1"><em><? echo $cih_lang_msg_base_array['pidtab_entitlement']; ?></em></a></li>
          <li><a href="#tab2"><em><? echo $cih_lang_msg_base_array['pidtab_siteinformation']; ?></em></a></li>
          <li><a href="#tab3"><em><? echo $cih_lang_msg_base_array['pidtab_servicecontractpayerinfo']; ?></em></a></li>
          <li><a href="#tab4"><em><? echo $cih_lang_msg_base_array['pidtab_meterview']; ?></em></a></li>
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
               <rn:widget path="CIHFunction/Accordion" name="accordionMeterHistory" visible="true" expanded="true" item_to_toggle="panelMeterHistory" label_header="#rn:php:$cih_lang_msg_base_array['pidmeterhistory']#" />

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
  <rn:widget path="CIHFunction/Accordion" name="accordionManageContacts" visible="false" expanded="true" item_to_toggle="panelManageContacts" label_header="#rn:php:$cih_lang_msg_base_array['managecontacts']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelManageContacts" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/ManageContacts" panel_name="accordionManageContacts" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionRepairRequest" visible="false" expanded="true" item_to_toggle="panelRepairRequest" label_header="#rn:php:$cih_lang_msg_base_array['repairrequest']#" />

  <div id="rn_<?=$this->instanceID;?>_containerSites" class="rn_FormPanel">

    <div id="panelRepairRequest" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/RepairRequest" panel_name="accordionRepairRequest" />
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" name="accordionIbaseUpdate" visible="false" expanded="true" item_to_toggle="panelIbaseUpdate" label_header="#rn:php:$cih_lang_msg_base_array['ibaseupdate']#" />

  <div id="rn_<?=$this->instanceID;?>_containerIbaseUpdate" class="rn_FormPanel">

    <div id="panelIbaseUpdate" class="rn_Accordion_content">

      <!-- Panel content goes here -->
      <rn:widget path="custom/CIHFunction/IBaseUpdate" widget_index="1" panel_name="accordionIbaseUpdate" />
    </div>
  </div>
</div>
</div>

<style>
.rn_Hidden{
  display: block !important;

}

</style>

<input type="hidden" id="sample_json" value='{"0":{"ibase_list":[{"OrgName":"BOOKLET BINDING INC","street":"710 KIMBERLY DR","city":"CAROL STREAM"
,"zip":"60188","province":"Illinois-IL","country":"US","custSAPId":"600000","orgID":347075,"manage":"Manage
 Contacts","ibaseID":"1881722","name":"BOOKLET BINDING INC","partnerfunction":"2","partnerID":null,"customerID"
:"600000"},{"OrgName":"Softco Ltd","street":"South County Business Park","city":"LEOPARDSTOWN, IRELAND"
,"zip":"DUBLIN 18","province":null,"country":"IE","custSAPId":"591986","orgID":1415,"manage":"Manage
 Contacts","ibaseID":"46336","name":"Capture  Company","partnerfunction":"2","partnerID":null,"customerID"
:"591986"},{"OrgName":"Conran Design Group","street":"35 Inverness Street","city":"LONDON","zip":"NW1
 7HB","province":null,"country":"GB","custSAPId":"861275","orgID":44194,"manage":"Manage Contacts","ibaseID"
:"25594","name":"Conran Design Group","partnerfunction":"2","partnerID":null,"customerID":"861275"},
{"OrgName":"Ebiquity PLC","street":"1 Westmoreland Road","city":"BROMLEY","zip":"BR2 0TB","province"
:null,"country":"GB","custSAPId":"580621","orgID":30050,"manage":"Manage Contacts","ibaseID":"5222","name"
:"Ebiquity PLC","partnerfunction":"2","partnerID":null,"customerID":"580621"},{"OrgName":"HM Customs
 & Excise","street":"21 Victoria Avenue","city":"SOUTHEND ON SEA","zip":"SS99 1AA","province":null,"country"
:"GB","custSAPId":"252603","orgID":633584,"manage":"Manage Contacts","ibaseID":"52103","name":"HM Customs
 & Excise","partnerfunction":"2","partnerID":null,"customerID":"252603"},{"OrgName":"INKTEL DIRECT","street"
:"1269 NORTH WOOD DALE RD","city":"WOOD DALE","zip":"60191","province":"Illinois-IL","country":"US","custSAPId"
:"599071","orgID":346270,"manage":"Manage Contacts","ibaseID":"1899037","name":"INKTEL DIRECT","partnerfunction"
:"2","partnerID":null,"customerID":"599071"},{"OrgName":"INTEGRATED VOTING SOLUTIONS","street":"4105
 HOLLY ST UNIT 3","city":"DENVER","zip":"80216","province":"Colorado-CO","country":"US","custSAPId":"917207"
,"orgID":698024,"manage":"Manage Contacts","ibaseID":"3050968","name":"INTEGRATED VOTING SOLUTIONS","partnerfunction"
:"2","partnerID":null,"customerID":"917207"},{"OrgName":"QUAD\/GRAPHICS INC- CHALFONT","street":"4371
 COUNTY LINE RD","city":"CHALFONT","zip":"18914","province":"Pennsylvania-PA","country":"US","custSAPId"
:"599428","orgID":346619,"manage":"Manage Contacts","ibaseID":"1924186","name":"QUAD\/GRAPHICS INC- CHALFONT"
,"partnerfunction":"2","partnerID":null,"customerID":"599428"},{"OrgName":"RR DONNELLEY & SONS CO-LAS
 VEGAS","street":"6305 SUNSET CORPORATE DR","city":"LAS VEGAS","zip":"89120","province":"Nevada-NV","country"
:"US","custSAPId":"599439","orgID":346630,"manage":"Manage Contacts","ibaseID":"1837802","name":"RR DONNELLEY"
,"partnerfunction":"2","partnerID":null,"customerID":"599439"},{"OrgName":"SANDHILLS PUBLISHING COMPANY"
,"street":"120 W HARVEST DR","city":"LINCOLN","zip":"68521","province":"Nebraska-NE","country":"US","custSAPId"
:"599824","orgID":346955,"manage":"Manage Contacts","ibaseID":"1860767","name":"SANDHILLS PUBLISHING
 CO","partnerfunction":"2","partnerID":null,"customerID":"599824"},{"OrgName":"Siemens Business Services
 Ltd","street":"Sir William Siemens Hse Princess Rd","city":"MANCHESTER","zip":"M20 2UR","province":null
,"country":"GB","custSAPId":"550513","orgID":27936,"manage":"Manage Contacts","ibaseID":"32749","name"
:"Siemens Business Services Ltd","partnerfunction":"2","partnerID":null,"customerID":"550513"},{"OrgName"
:"VALID SOL SERV S M PAG IDEN SA","street":"1561AV. DR. RUDGE RAMOS","city":"SAO BERNARDO DO CAMPO","zip"
:"09639000","province":"S\u00e3o Paulo-SP","country":"BR","custSAPId":"905753","orgID":665729,"manage"
:"Manage Contacts","ibaseID":"3809332","name":"VALID SOL SERV S M PAG IDEN SA","partnerfunction":"2"
,"partnerID":null,"customerID":"905753"},{"OrgName":"Williams Lea","street":"Lomond View","city":"CRAIGFORTH
  STIRLING","zip":"FK9 4SH","province":null,"country":"GB","custSAPId":"573099","orgID":29291,"manage"
:"Manage Contacts","ibaseID":"55843","name":"Williams Lea","partnerfunction":"2","partnerID":null,"customerID"
:"573099"}]},"status":1,"Site":[{"OrgName":null,"street":null,"city":null,"zip":null,"province":null
,"country":null,"custSAPId":null,"orgID":null,"manage":"Manage Contacts"}],"Payer":{"OrgName":null,"street"
:null,"city":null,"zip":null,"province":null,"country":null,"custSAPId":null,"orgID":null,"manage":"Manage
 Contacts","OUTSIDEENTID":null,"SAPID":null}}'>


 