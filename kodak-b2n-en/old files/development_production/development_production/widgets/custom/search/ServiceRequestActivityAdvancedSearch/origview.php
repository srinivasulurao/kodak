<rn:meta controller_path="custom/search/ServiceRequestActivityAdvancedSearch" js_path="custom/search/ServiceRequestActivityAdvancedSearch" base_css="custom/search/ServiceRequestActivityAdvancedSearch" presentation_css="widgetCss/ServiceRequestActivityAdvancedSearch.css"/>
<div id="rn_search_ErrorLocation" class="rn_ErrorField"></div>
	<table id="advancedSearch" class="rn_serviceRequestAdvancedSearch_table">
		<tr>
			<td nowrap="nowrap" valign="top" colspan="2" id="searchDateRange" style="width:270px">
				<span>Date Range:</span><br/>
				<rn:widget path="custom/CIHFunction/DateInputCalendar" search_filter_id="12" search_operator_id="9" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
			</td>
			<td nowrap="nowrap" valign="top">
				<span>Incident Status Type:</span><br/>
				<rn:widget path="custom/CIHFunction/MenuSelect" remove_options="3" name="status_type" table="Incident" custom_field="StatusWithType.StatusType" required="false" core_field="true" is_search_filter="true" search_filter_id="10" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" selected_value="1" />
			</td>
			<td valign="top">
				<span>Reference #:</span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ref_no" is_search_filter="true" search_filter_id="2" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />	
			</td>
			<td valign="top">
				<span>Partner Tracking #:</span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_ext_ref_no" is_search_filter="true" search_filter_id="7" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
			</td>
		</tr>
		<tr>
			<td nowrap="nowrap" valign="top">
				<span>Product Identifier:</span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_k_number" is_search_filter="true" search_filter_id="6" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
			</td>
			<td nowrap="nowrap" valign="top">
				<span>Serial Num:</span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_serial_number" is_search_filter="true" search_filter_id="8" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
			</td>
<? if($this->data['js']['internal_user']=='N'): ?>
			<td nowrap="nowrap" valign="top">
				<span>Site Customer ID:</span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_customer_sapid" is_search_filter="true" search_filter_id="19" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
			</td>
			<td valign="top">
				<?
					$options_json = json_encode($this->data['country_list']);	
				?>
				<span>Country:</span><br/>
				<rn:widget path="custom/CIHFunction/MenuSelect" data="#rn:php:$options_json#" name="country" is_search_filter="true" search_filter_id="11" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#"/>	
			</td>
<? endif; ?>
<? if($this->data['js']['internal_user']=='Y'): ?>
			<td nowrap="nowrap" valign="top" colspan="2">
				<span>Site Customer ID:</span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_customer_sapid" is_search_filter="true" search_filter_id="19" label_required="SAP Customer ID is required." required="true" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />

                        </td>
<? endif; ?>
			<td>
								
			</td>

		</tr>
	</table>
