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
<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
<div id="rn_search_ErrorLocation" class="rn_ErrorField"></div>
	<table id="advancedSearch" class="rn_serviceRequestAdvancedSearch_table">
		<tr>
			<td nowrap="nowrap" valign="top" colspan="2" id="searchDateRange" style="width:270px">
				<span><? echo $cih_lang_msg_base_array['sra_daterange']; ?></span><br/>
				<rn:widget path="custom/CIHFunction/DateInputCalendar" search_filter_id="12" search_operator_id="9" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
			</td>
			<td nowrap="nowrap" valign="top">
				<span><? echo $cih_lang_msg_base_array['sra_incstatustype']; ?></span><br/>
				<rn:widget path="custom/CIHFunction/MenuSelect" remove_options="3" name="status_type" table="Incident" custom_field="StatusWithType.StatusType" required="false" core_field="true" is_search_filter="true" search_filter_id="10" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" selected_value="1" />
			</td>
			<td valign="top">
				<span><? echo $cih_lang_msg_base_array['sra_refnum']; ?></span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ref_no" is_search_filter="true" search_filter_id="2" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />	
			</td>
			<td valign="top">
				<span><? echo $cih_lang_msg_base_array['sra_partnertrackingnum']; ?></span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_ext_ref_no" is_search_filter="true" search_filter_id="7" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
			</td>
		</tr>
		<tr>
			<td nowrap="nowrap" valign="top">
				<span><? echo $cih_lang_msg_base_array['sra_productidentifier']; ?></span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_k_number" is_search_filter="true" search_filter_id="6" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
			</td>
			<td nowrap="nowrap" valign="top">
				<span><? echo $cih_lang_msg_base_array['sra_serialnum']; ?></span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_serial_number" is_search_filter="true" search_filter_id="8" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
			</td>
<? if($this->data['js']['internal_user']=='N'): ?>
			<td nowrap="nowrap" valign="top">
				<span><? echo $cih_lang_msg_base_array['sra_sitecustid']; ?></span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_customer_sapid" is_search_filter="true" search_filter_id="19" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
			</td>
			<td valign="top" class='country_cell'>
				<?
					$options_json = json_encode($this->data['country_list']);	
				?>
				<span><? echo $cih_lang_msg_base_array['sra_sr_country']; ?></span><br/>
				<rn:widget path="custom/CIHFunction/MenuSelect" data="#rn:php:$options_json#" name="country" is_search_filter="true" search_filter_id="11" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#"/>	
			</td>
<? endif; ?>
<? if($this->data['js']['internal_user']=='Y'): ?>
			<td nowrap="nowrap" valign="top" colspan="2">
				<span><? echo $cih_lang_msg_base_array['sra_sitecustid']; ?></span><br/>
				<rn:widget path="custom/CIHFunction/CustomTextInput" name="ek_customer_sapid" is_search_filter="true" search_filter_id="19" label_required="#rn:php:$cih_lang_msg_base_array['sra_custidisrequired']#" required="true" search_operator_id="1" search_report_id="#rn:php:$this->data['attrs']['report_id']#" />
				
                        </td>
<? endif; ?>
			<td>
								
			</td>

		</tr>
	</table>

</div>

