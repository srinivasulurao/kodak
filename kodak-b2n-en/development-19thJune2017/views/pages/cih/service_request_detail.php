<?
	$this->CI = & get_instance();
	$sesslang = $this->CI->session->getSessionData("lang");
	$sesslang="en";
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
	$this->CI->load->model('custom/custom_contact_model');
    $internal =  $this->CI->custom_contact_model->checkInternalContact($this->CI->session->getProfileData('c_id'));
	$this->CI->load->model('custom/custom_incident_model');
	
	// if(getUrlParm('corp_id')) {
	// 		$verify = $this->CI->custom_incident_model->verifyCorpAccess(getUrlParm('obj_id'), getUrlParm('corp_id'));
	// 	}
    //     else {
	// 		if (getUrlParm('i_id') || ($internal == "Y")) {
	// 		} else {
	// 			$reqUri = $_SERVER['REQUEST_URI'];
	// 			header("Location: $reqUri"."/i_id/".getUrlParm('obj_id')); exit;
	// 		} 
    //       $verify = $this->CI->custom_incident_model->verifyAccess(getUrlParm('obj_id'));
	// 	}

        //if($verify == "0") //Giving Access to all users as of now.
          //header('Location: /app/error/error_id/6');

	$srd = $this->CI->custom_incident_model->getServiceRequestDetails(getUrlParm('obj_id'));

	$page_title = 	$srd['incident']->Subject;
?>
<rn:meta title="#rn:php:$cih_lang_msg_base_array['srd_srda']#" template="newkodak_b2b_template.php" login_required="true" clickstream="incident_view"/>

<rn:widget path="CIHFunction/SwapReportController" />
<div id="rn_PageTitle" class="rn_AskQuestion">
    <h1><? echo $cih_lang_msg_base_array['srd_srda']; ?></h1>
</div>
<div id="rn_PageContent" class="rn_QuestionDetail">
    <div class="rn_Padding">
		<div class="rn_Accordion_container">
			<rn:widget path="CIHFunction/Accordion"  item_to_toggle="pnlDetails" label_header="#rn:php:$cih_lang_msg_base_array['srd_servicerequest']#" name="servicerequest" expanded="true"/>
			<div id="pnlDetails" style="padding:10px;">
				<div id="rn_AdditionalInfo">
				
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_contactname']; ?></span> <div class="rn_DataValue"><?=$srd['contact']->Name->First?> <?=$srd['contact']->Name->Last?></div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_contactphonenum']; ?></span> <div class="rn_DataValue"><?=$srd['contact']->Phones[0]->Number?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_email']; ?></span> <div class="rn_DataValue"><?=$srd['contact']->Emails[0]->Address?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_createdatetime']; ?></span> <div class="rn_DataValue"><?=$srd['incident']->CustomFields->ek_inc_create_local_time ? date('d-M-Y H:i',$srd['incident']->CustomFields->ek_inc_create_local_time) : '' ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_closedatetime']; ?></span> <div class="rn_DataValue"><?= $srd['close_date']? date('d-M-Y H:i',$srd['close_date']):'' ?>&nbsp;</div></div>
					
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_status']; ?></span> <div class="rn_DataValue"><?=($srd['incident']->StatusWithType->Status->LookupName =='Credit Hold' ? 'Admin Hold':$srd['incident']->StatusWithType->Status->LookupName)?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_referencenum']; ?></span> <div class="rn_DataValue"><?=$srd['incident']->ReferenceNumber ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_partnertrackingnum']; ?></span> <div class="rn_DataValue"><?=$srd['incident']->CustomFields->ek_ext_ref_no ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_typeofsvc']; ?></span> <div class="rn_DataValue"><?=$srd['incident']->CustomFields->ek_type->LookupName ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_reasonforcall']; ?></span> <div class="rn_DataValue"><?=$srd['incident']->Subject ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_errorcode']; ?></span> <div class="rn_DataValue"><?=$srd['incident']->CustomFields->ek_error_code ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_serviceordernum']; ?></span> <div class="rn_DataValue"><?=$srd['incident']->CustomFields->ek_sap_svc_order_id ?>&nbsp;</div></div>
				</div>
		
				<h2 class="rn_HeadingBar"><? echo $cih_lang_msg_base_array['srd_siteinformation']; ?></h2>
				<div id="rn_AdditionalInfo">
				
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_sitecustomerid']; ?></span> <div class="rn_DataValue"><?=$srd['contact']->Organization->CustomFields->ek_customer_sapid ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_sitename']; ?></span> <div class="rn_DataValue"><?=$srd['contact']->Organization->Name ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_siteaddress']; ?></span> <div class="rn_DataValue"><?=$srd['address']?>&nbsp;</div></div>
					
				</div>

				<h2 class="rn_HeadingBar"><?php echo $cih_lang_msg_base_array['srd_installedprodinfo']; ?></h2>
				<div id="rn_AdditionalInfo">
					<rn:widget path="custom/reports/Grid2JSSort"  report_id="#rn:php:$cih_lang_msg_base_array['repid_ekinstalledprodinformation']#"/>
				</div>
			</div>
		</div>
		
		<? if ($internal == "Y") : ?>
		<div class="rn_Accordion_container">
			<rn:widget path="CIHFunction/Accordion" item_to_toggle="pnlEvents" label_header="#rn:php:$cih_lang_msg_base_array['srd_servicerequestevents']#" name="serviceevents" expanded="true"/>
			<div id="pnlEvents" style="padding:10px;">
				<div id="rn_AdditionalInfo">
					<!-- <rn:widget path="custom/reports/Grid"  report_id="100903" url_column="2" url_text="View Details"/> -->
					<rn:widget path="reports/Grid"  report_id="#rn:php:$cih_lang_msg_base_array['repid_ekinstalledprodinformation']#"/>
 				</div>
			</div>
		</div>
		<? endif;?>
		
		
		<div class="rn_Accordion_container">
			<rn:widget path="CIHFunction/Accordion" item_to_toggle="pnlCommunication" label_header="#rn:php:$cih_lang_msg_base_array['srd_communicationactivity']#" name="communicationactivity" expanded="true"/>
			<div id="pnlCommunication">
				<div id="rn_AdditionalInfo" style="padding:10px;">
					<!-- <rn:widget path="custom/output/IncidentThreadDisplay" /> -->
					<rn:widget path="output/IncidentThreadDisplay" name="Incident.Threads" />
					</div>
			</div>
		</div>
				
		<div class="rn_Accordion_container">
			<rn:widget path="CIHFunction/Accordion" item_to_toggle="pnlUpdateRequest" label_header="#rn:php:$cih_lang_msg_base_array['srd_updateservicereqinfo']#" name="updateservicerequest" expanded="true"/>
			<div id="pnlCommunication">
				<div id="pnlUpdateRequest" style="padding:10px;">

				<? if( $srd['incident']->StatusWithType->StatusType->LookupName == 'Closed' ): ?>
					<h2 class="rn_HeadingBar"><? echo $cih_lang_msg_base_array['srd_inc_reopened_upd_further']; ?></h2>
						
				<? else:?>
						<div id="rn_ErrorLocation"></div>
						<div id="dispatched" class="rn_ErrorMessage <?= $srd['incident']->StatusWithType->Status->LookupName == 'Dispatched' ? '':'rn_Hidden' ?>" ><? echo $cih_lang_msg_base_array['srd_alert']; ?></div>
						<form id="rn_UpdateQuestion" method="post" action="" onsubmit="return false;">
						<? /*
						if(getUrlParm('view_log')==1) { ?>
						<rn:widget path="input/FormInput" name="incidents.thread" label_input="#rn:msg:ADD_ADDTL_INFORMATION_QUESTION_CMD#" />
										<? } else { ?>
						<rn:widget path="input/FormInput" name="incidents.thread" label_input="#rn:msg:ADD_ADDTL_INFORMATION_QUESTION_CMD#" initial_focus="false"/>
								<? }	*/ ?>

						<!--<rn:widget path="custom/CIHFunction/IncidentThreadUpdate"  label_input="#rn:msg:ADD_ADDTL_INFORMATION_QUESTION_CMD#" name="thread" />-->
						<span class="rn_DataLabel"><? echo $cih_lang_msg_base_array['srd_addinfo']; ?></span><br/>
						<? 

						if (($internal == "N")  && !getUrlParm('corp_id'))  {
							$obj_id = getUrlParm('obj_id'); 
							echo '<a href="/app/cih/attachfile/i_id/'.$obj_id.'">'.$cih_lang_msg_base_array['upload_file_attachment'].'</a>'; 
							}
						?>
				<? if (($internal == "N")  && !getUrlParm('corp_id')) : ?>
            		 <rn:widget path="output/DataDisplay" name="incidents.fattach" label="#rn:php:$cih_lang_msg_base_array['file_attachment']#"/>  
				<? endif;?>
						<rn:widget path="Input/TextInput" name="Incident.Threads" value="" width="100%" height="150px"  required="true" label_input="Additional information" />
<!--								<div id="rn_FileAttach">
		                      
								</div>
-->								
							  <!--<rn:widget path="custom/input/ROSelectionInput" name="incidents.status" label_input="#rn:msg:DO_YOU_WANT_A_RESPONSE_MSG#" />-->
							<rn:widget path="custom/CIHFunction/HiddenInput" name="obj_id" value="#rn:php:getUrlParm('obj_id')#" />
							<rn:widget path="custom/CIHFunction/HiddenInput" name="sesslang" value="#rn:php:$sesslang#" />
						    <rn:widget path="custom/CIHFunction/AjaxFormSubmit" error_location="rn_ErrorLocation" ajax_method="incident_custom/add_thread" challenge_required="false" disable_result_handler="false"/>
							  <!--<rn:widget path="input/FormSubmit" on_success_url="/app/cih/service_request_detail/i_id/#rn:php:getUrlParm('obj_id')#" error_location="rn_ErrorLocation"/>-->
						</form>
			
				<? endif;?>
				
					</div>
			</div>
		</div>
		
        
        <div id="rn_DetailTools">
            <rn:widget path="utils/PrintPageLink" />
        </div>
    </div>
	
</div>


<script>
thread_length=document.querySelectorAll('.rn_ThreadAuthor').length;

for(i=0;i<thread_length;i++){
	inner_text=document.querySelectorAll('.rn_ThreadAuthor')[i].innerText;
	if(inner_text.indexOf('Customer Proxy') == -1 && inner_text.indexOf('Customer') > -1)
		document.querySelectorAll('.rn_ThreadAuthor')[i].innerText="Customer";
	if(inner_text.indexOf('Customer Proxy') > -1 && inner_text.indexOf('Phone') > -1)
		document.querySelectorAll('.rn_ThreadAuthor')[i].innerText="Customer Proxy Via Phone";
}
</script>