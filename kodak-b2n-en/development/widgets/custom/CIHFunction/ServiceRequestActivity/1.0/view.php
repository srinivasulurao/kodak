<?php /* Originating Release: February 2012 */ ?>

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
//echo $cih_lang_msg_base_array['sra_repideksupporthistory'];
//die;
?>

<form id="rn_<?=$this->instanceID;?>_form" method="post" action="" onsubmit="return false;">
<div class="rn_Accordion_container">
  <rn:widget path="custom/CIHFunction/Accordion" expanded="true" item_to_toggle="panelSearchFilters" label_header="#rn:php:$cih_lang_msg_base_array['searchfilters']#" />
  <div id="rn_<?=$this->instanceID;?>_containerCustomerSearch" class="rn_FormPanel">
    <div id="panelSearchFilters" class="rn_Accordion_content">
<? if($this->data['js']['internal_user']=='Y') print($cih_lang_msg_base_array['loggedinasinternal']); ?>
		<table style="width:100%">
			<tr>
				<td>
	                                <span><? echo $cih_lang_msg_base_array['sra_partnertype']; ?></span><br/>
					<rn:widget path="custom/CIHFunction/PartnerTypeListSearch" name="partnertype" search_operator_id="1" search_report_id="#rn:php:$cih_lang_msg_base_array['sra_repideksupporthistory']#" />
				</td>			
				<td>
					<?
					$options = array();
					$options[] = array('ID'=>1,'LookupName'=>$cih_lang_msg_base_array['sradd_onlymyinc']);
					$options[] = array('ID'=>2,'LookupName'=>$cih_lang_msg_base_array['sradd_fromanyone']);
					$options_json = json_encode($options);	
					?>
					<div id="inc_selector"  class="<?=$this->data['customer_type'] != 'direct' ? 'rn_Hidden': '' ?>">					
					<span><? echo $cih_lang_msg_base_array['sra_showincidents']; ?></span><br/>
					<!--rn:widget path="custom/CIHFunction/MenuSelect" name="direct" data="#rn:php:$options_json#" required="false"/-->					
						<rn:widget path="custom/search/OrgList2" search_on_select="true" display_type="2" report_id="#rn:php:$cih_lang_msg_base_array['sra_repideksupporthistory']#" label_title=""/> 
					</div>
				</td>
				<td nowrap="nowrap" valign="bottom">
					<a id="rn_<?=$this->instanceID;?>_advSearchPanelTrigger" style="cursor:pointer"><? echo $cih_lang_msg_base_array['sra_showhideadv']; ?></a>
				</td>
				<td valign="bottom" align="right" width="425px" style="margin-right:200px;">
					<rn:widget path="standard/search/SearchButton" label="Search" report_id="#rn:php:$cih_lang_msg_base_array['sra_repideksupporthistory']#" />
				</td>				
			</tr>
			<tr>
				<td colspan="4">
					<div id="rn_<?=$this->instanceID;?>_advSearchPanel" style="display:none" class="rn_serviceRequestAdvancedSearch">
						<rn:widget path="custom/search/ServiceRequestActivityAdvancedSearch" report_id="#rn:php:$cih_lang_msg_base_array['sra_repideksupporthistory']#" />	
					</div>
				</td>				
			</tr>
		</table>
    </div>
  </div>
</div>

<div class="rn_Accordion_container">
  <rn:widget path="custom/CIHFunction/Accordion" expanded="true" item_to_toggle="panelServiceRequests" label_header="#rn:php:$cih_lang_msg_base_array['sra_servicerequests']#" />
  <div id="rn_<?=$this->instanceID;?>_containerServiceRequests" class="rn_FormPanel">
    <div id="panelServiceRequests" class="rn_Accordion_content" style="pading:10px;">
		<div id="searchResultsContainer" class="rn_Hidden">
		<rn:widget path="standard/reports/Grid" report_id="#rn:php:$cih_lang_msg_base_array['sra_repideksupporthistory']#" date_format="date_time"/>
		<rn:widget path="standard/reports/Paginator" report_id="#rn:php:$cih_lang_msg_base_array['sra_repideksupporthistory']#" label_forward="#rn:php:$cih_lang_msg_base_array['next']#" label_back="#rn:php:$cih_lang_msg_base_array['previous']#" />  
		</div>
	</div>
  </div>	
</div>
<rn:widget path="custom/CIHFunction/AutoSearch" /> 
</form>


