<?php /* Originating Release: February 2012 */ ?>
<rn:meta controller_path="custom/CIHFunction/ServiceRequestActivity" js_path="custom/CIHFunction/ServiceRequestActivity" compatibility_set="November '09+"/>

<form id="rn_<?=$this->instanceID;?>_form" method="post" action="" onsubmit="return false;">
<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" expanded="true" item_to_toggle="panelSearchFilters" label_header="Search Filters" />
  <div id="rn_<?=$this->instanceID;?>_containerCustomerSearch" class="rn_FormPanel">
    <div id="panelSearchFilters" class="rn_Accordion_content">
<? if($this->data['js']['internal_user']=='Y') print("(Logged in as Internal Kodak User)"); ?>
		<table style="width:100%">
			<tr>
				<td>
					<span>Partner Type:</span><br/>
					<rn:widget path="custom/CIHFunction/PartnerTypeListSearch" name="partnertype" search_operator_id="1" search_report_id="100902" />
				</td>			
				<td>
					<?
					$options = array();
					$options[] = array('ID'=>1,'LookupName'=>'Only my Incidents');
					$options[] = array('ID'=>2,'LookupName'=>'From anyone in my organization');
					$options_json = json_encode($options);	
					?>
					<div id="inc_selector" class="<?=$this->data['customer_type'] != 'direct' ? 'rn_Hidden': '' ?>">					
					<span>Show Incidents:</span><br/>
					<!--rn:widget path="custom/CIHFunction/MenuSelect" name="direct" data="#rn:php:$options_json#" required="false"/-->					
						<rn:widget path="custom/search/OrgList2" search_on_select="true" display_type="2" report_id="100902" label_title=""/>
					</div>
				</td>
				<td nowrap="nowrap" valign="bottom">
					<a id="rn_<?=$this->instanceID;?>_advSearchPanelTrigger" style="cursor:pointer">Show/Hide Advanced Search</a>
				</td>
				<td valign="bottom" align="right" width="850px" style="margin-right:200px;">
					<rn:widget path="custom/CIHSearch/SearchButton2" label="Search" report_id="100902" />
				</td>				
			</tr>
			<tr>
				<td colspan="4">
					<div id="rn_<?=$this->instanceID;?>_advSearchPanel" style="display:none" class="rn_serviceRequestAdvancedSearch">
						<rn:widget path="custom/search/ServiceRequestActivityAdvancedSearch" report_id="100902" />	
					</div>
				</td>				
			</tr>
		</table>
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="CIHFunction/Accordion" expanded="true" item_to_toggle="panelServiceRequests" label_header="Service Requests" />
  <div id="rn_<?=$this->instanceID;?>_containerServiceRequests" class="rn_FormPanel">
    <div id="panelServiceRequests" class="rn_Accordion_content" style="pading:10px;">
		<div id="searchResultsContainer" class="rn_Hidden">
		<rn:widget path="custom/reports/Grid2" report_id="100902" date_format="date_time"/>
		<rn:widget path="custom/reports/Paginator" report_id="100902"/>
		</div>
	</div>
  </div>	
</div>
<rn:widget path="custom/CIHFunction/AutoSearch" />
</form>


