<rn:meta title="#rn:php:SEO::getDynamicTitle('incident', getUrlParm('i_id'))#" template="kodak_b2b_template.php" login_required="true" clickstream="incident_view"/>
<div id="rn_PageTitle" class="rn_Account">
    <h1><rn:field name="incidents.subject" highlight="true"/></h1>
</div>
<div id="rn_PageContent" class="rn_QuestionDetail">
    <div class="rn_Padding">
        <rn:condition incident_reopen_deadline_hours="0">
            <h2 class="rn_HeadingBar">#rn:msg:UPDATE_THIS_QUESTION_CMD#</h2>
            <div id="rn_ErrorLocation"></div>
            <form id="rn_UpdateQuestion" method="post" action="" onsubmit="return false;">
            <? 
			if(getUrlParm('view_log')==1) { ?>
                    <rn:widget path="input/FormInput" name="incidents.thread" label_input="#rn:msg:ADD_ADDTL_INFORMATION_QUESTION_CMD#" />
            <? } else { ?>
                    <rn:widget path="input/FormInput" name="incidents.thread" label_input="#rn:msg:ADD_ADDTL_INFORMATION_QUESTION_CMD#" initial_focus="true"/>
					<? } ?>
                    <rn:widget path="input/FileAttachmentUpload" label_input="#rn:msg:ATTACH_ADDTL_DOCUMENTS_QUESTION_LBL#"/>
                    <div id="rn_FileAttach">
                        
                    </div>
                  <rn:widget path="input/SelectionInput" name="incidents.status" label_input="#rn:msg:DO_YOU_WANT_A_RESPONSE_MSG#" />
                  <rn:widget path="input/FormSubmit" on_success_url="/app/account/questions/list" error_location="rn_ErrorLocation"/>
            </form>
       <rn:condition_else/>
            <h2 class="rn_HeadingBar">#rn:msg:INC_REOPENED_UPD_FURTHER_ASST_PLS_MSG#</h2>
        </rn:condition> 
        <h2 class="rn_HeadingBar">#rn:msg:COMMUNICATION_HISTORY_LBL#</h2>
        <div id="rn_QuestionThread">
            <rn:widget path="output/DataDisplay" name="incidents.thread" label=""/>
        </div>

        <h2 class="rn_HeadingBar">#rn:msg:ADDITIONAL_DETAILS_LBL#</h2>
        <div id="rn_AdditionalInfo">
            <rn:widget path="output/DataDisplay" name="incidents.contact_email" label="#rn:msg:EMAIL_ADDR_LBL#" />
            <rn:widget path="output/DataDisplay" name="incidents.ref_no" />
            <rn:widget path="output/DataDisplay" name="incidents.status" />
            <rn:widget path="output/DataDisplay" name="incidents.created" label="#rn:msg:CREATED_LBL#" />
            <rn:widget path="output/DataDisplay" name="incidents.updated" />
            <rn:widget path="output/DataDisplay" name="incidents.prod"  />
            <rn:widget path="output/DataDisplay" name="incidents.cat" label="Problem Fault" />
            <rn:widget path="output/FieldDisplay" name="incidents.c$ek_severity" />
            <rn:widget path="output/FieldDisplay" name="incidents.c$ek_repeatability" />
			 <rn:widget path="output/DataDisplay" name="incidents.fattach" label_input="#rn:msg:FILE_ATTACHMENTS_LBL#"/>
        </div>

 		<h2 class="rn_HeadingBar"><a name="event_log">Event Log</a> </h2>
		<div id="rn_AdditionalInfo">
		<rn:widget path="reports/Grid2"  report_id="100563"/>
        <h2 class="rn_HeadingBar"><a>Notes</a> </h2>
        <rn:widget path="reports/Grid2"  report_id="100564"/>
 		</div>
        
        <div id="rn_DetailTools">
            <rn:widget path="utils/PrintPageLink" />
        </div>
    </div>
</div>
