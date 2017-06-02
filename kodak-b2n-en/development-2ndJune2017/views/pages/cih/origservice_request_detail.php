<?
	$this->CI = & get_instance();
	$this->CI->load->model('custom/custom_contact_model');
    $internal =  $this->CI->custom_contact_model->checkInternalContact($this->CI->session->getProfileData('c_id'));
	$this->CI->load->model('custom/custom_incident_model');
	
	if(getUrlParm('corp_id')) {
			$verify = $this->CI->custom_incident_model->verifyCorpAccess(getUrlParm('obj_id'), getUrlParm('corp_id'));
		}
       else {
			if (getUrlParm('i_id') || ($internal == "Y")) {
			} else {
				$reqUri = $_SERVER['REQUEST_URI'];
				header("Location: $reqUri"."/i_id/".getUrlParm('obj_id')); exit;
			} 
          $verify = $this->CI->custom_incident_model->verifyAccess(getUrlParm('obj_id'));

		}
        if($verify == "0")
          header('Location: /app/error/error_id/6');

	$srd = $this->CI->custom_incident_model->getServiceRequestDetails(getUrlParm('obj_id'));

	$page_title = 	$srd['incident']->Subject;
?>
<rn:meta title="Service Request Activity Details" template="kodak_b2b_template.php" login_required="true" clickstream="incident_view"/>

<rn:widget path="CIHFunction/SwapReportController" />
<div id="rn_PageTitle" class="rn_AskQuestion">
    <h1>Service Request Activity Details</h1>
</div>
<div id="rn_PageContent" class="rn_QuestionDetail">
    <div class="rn_Padding">
		<div class="rn_Accordion_container">
			<rn:widget path="CIHFunction/Accordion" item_to_toggle="pnlDetails" label_header="Service Request" name="servicerequest" expanded="true"/>
			<div id="pnlDetails" style="padding:10px;">
				<div id="rn_AdditionalInfo">
				
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Contact Name</span> <div class="rn_DataValue"><?=$srd['contact']->Name->First?> <?=$srd['contact']->Name->Last?></div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Contact Phone Num</span> <div class="rn_DataValue"><?=$srd['contact']->Phones[0]->Number?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Email</span> <div class="rn_DataValue"><?=$srd['contact']->Emails[0]->Address?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Create Date/Time</span> <div class="rn_DataValue"><?=$srd['incident']->CustomFields->ek_inc_create_local_time ? date('d-M-Y H:i',$srd['incident']->CustomFields->ek_inc_create_local_time) : '' ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Close Date/Time</span> <div class="rn_DataValue"><?= $srd['close_date']? date('d-M-Y H:i',$srd['close_date']):'' ?>&nbsp;</div></div>
					
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Status</span> <div class="rn_DataValue"><?=($srd['incident']->StatusWithType->Status->LookupName =='Credit Hold' ? 'Admin Hold':$srd['incident']->StatusWithType->Status->LookupName)?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Reference #</span> <div class="rn_DataValue"><?=$srd['incident']->ReferenceNumber ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Partner Tracking #</span> <div class="rn_DataValue"><?=$srd['incident']->CustomFields->ek_ext_ref_no ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Type of Svc</span> <div class="rn_DataValue"><?=$srd['incident']->CustomFields->ek_type->LookupName ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Reason for call</span> <div class="rn_DataValue"><?=$srd['incident']->Subject ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Error Code</span> <div class="rn_DataValue"><?=$srd['incident']->CustomFields->ek_error_code ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Service Order #</span> <div class="rn_DataValue"><?=$srd['incident']->CustomFields->ek_sap_svc_order_id ?>&nbsp;</div></div>
				</div>
		
				<h2 class="rn_HeadingBar">Site Information</h2>
				<div id="rn_AdditionalInfo">
				
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Site Customer ID</span> <div class="rn_DataValue"><?=$srd['contact']->Organization->CustomFields->ek_customer_sapid ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Site Name</span> <div class="rn_DataValue"><?=$srd['contact']->Organization->Name ?>&nbsp;</div></div>
					<div class="rn_FieldDisplay"><span class="rn_DataLabel">Site Address</span> <div class="rn_DataValue"><?=$srd['address']?>&nbsp;</div></div>
					
				</div>

				<h2 class="rn_HeadingBar">Installed Product Information</h2>
				<div id="rn_AdditionalInfo">
					<rn:widget path="reports/Grid" name="Incidents.Threads" report_id="100819"/>
				</div>
			</div>
		</div>
		
		<? if ($internal == "Y") : ?>
		<div class="rn_Accordion_container">
			<rn:widget path="CIHFunction/Accordion" item_to_toggle="pnlEvents" label_header="Service Request Events" name="serviceevents" expanded="true"/>
			<div id="pnlEvents" style="padding:10px;">
				<div id="rn_AdditionalInfo">
					<rn:widget path="reports/Grid" name="Incidents.Threads" report_id="100903" url_column="2" url_text="View Details"/>
 				</div>
			</div>
		</div>
		<? endif;?>
		
		
		<div class="rn_Accordion_container">
			<rn:widget path="CIHFunction/Accordion" item_to_toggle="pnlCommunication" label_header="Communication Activity" name="communicationactivity" expanded="true"/>
			<div id="pnlCommunication">
				<div id="rn_AdditionalInfo" style="padding:10px;">
					<rn:widget path="output/IncidentThreadDisplay" name="Incidents.Threads" />
					</div>
			</div>
		</div>
				
		<div class="rn_Accordion_container">
			<rn:widget path="CIHFunction/Accordion" item_to_toggle="pnlUpdateRequest" label_header="Update Service Request Info" name="updateservicerequest" expanded="true"/>
			<div id="pnlCommunication">
				<div id="pnlUpdateRequest" style="padding:10px;">

				<? if( $srd['incident']->StatusWithType->StatusType->LookupName == 'Closed' ): ?>
					<h2 class="rn_HeadingBar">#rn:msg:INC_REOPENED_UPD_FURTHER_ASST_PLS_MSG#</h2>
						
				<? else:?>
						<div id="rn_ErrorLocation"></div>
						<div id="dispatched" class="rn_ErrorMessage <?= $srd['incident']->StatusWithType->Status->LookupName == 'Dispatched' ? '':'rn_Hidden' ?>" >ALERT!  All updates to incidents that have been dispatched will be noted in the incident, but no action will be taken.  If your update is critical to your incident, please call.</div>
						<form id="rn_UpdateQuestion" method="post" action="" onsubmit="return false;">
						<? /*
						if(getUrlParm('view_log')==1) { ?>
						<rn:widget path="input/FormInput" name="incidents.thread" label_input="#rn:msg:ADD_ADDTL_INFORMATION_QUESTION_CMD#" />
										<? } else { ?>
						<rn:widget path="input/FormInput" name="incidents.thread" label_input="#rn:msg:ADD_ADDTL_INFORMATION_QUESTION_CMD#" initial_focus="false"/>
								<? }	*/ ?>

						<!--<rn:widget path="custom/CIHFunction/IncidentThreadUpdate"  label_input="#rn:msg:ADD_ADDTL_INFORMATION_QUESTION_CMD#" name="thread" />-->
						<span class="rn_DataLabel">#rn:msg:ADD_ADDTL_INFORMATION_QUESTION_CMD#</span><br/>
						<? 

						if (($internal == "N")  && !getUrlParm('corp_id'))  {
							$obj_id = getUrlParm('obj_id'); 
							echo '<a href="/app/cih/attachfile/i_id/'.$obj_id.'">Upload File Attachment</a>'; 
							}
						?>
				<? if (($internal == "N")  && !getUrlParm('corp_id')) : ?>
            		 <rn:widget path="output/DataDisplay" name="incidents.fattach" label_input="#rn:msg:FILE_ATTACHMENTS_LBL#"/>  
				<? endif;?>
						
						<rn:widget path="custom/CIHFunction/TextAreaInput" name="thread" value="" width="100%" height="150px"  required="true" label_input="Additional information" />
							  <!--<rn:widget path="custom/input/ROSelectionInput" name="incidents.status" label_input="#rn:msg:DO_YOU_WANT_A_RESPONSE_MSG#" />-->
							<rn:widget path="custom/CIHFunction/HiddenInput" name="obj_id" value="#rn:php:getUrlParm('obj_id')#" />
								<rn:widget path="custom/CIHFunction/AjaxFormSubmit" error_location="rn_ErrorLocation" ajax_method="incident_custom/add_thread" challenge_required="false" disable_result_handler="true"/>
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

