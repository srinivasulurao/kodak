<rn:meta title="#rn:php:\RightNow\Libraries\SEO::getDynamicTitle('incident', \RightNow\Utils\Url::getParameter('i_id'))#" template="mobile.php" login_required="true" clickstream="incident_view" force_https="true"/>

<section id="rn_PageTitle" class="rn_QuestionDetail">
    <h1><rn:field name="Incident.Subject" highlight="true"/></h1>
</section>
<section id="rn_PageContent" class="rn_QuestionDetail">
    <rn:condition incident_reopen_deadline_hours="168">
        <div class="rn_Module">
            <rn:widget path="navigation/Accordion" toggle="rn_QuestionUpdate"/>
            <h2 id="rn_QuestionUpdate">#rn:msg:UPDATE_THIS_QUESTION_CMD#<span class="rn_Expand"></span></h2>
            <form id="rn_UpdateQuestion" onsubmit="return false;">
                <div id="rn_ErrorLocation"></div>
                <rn:widget path="input/FormInput" name="Incident.Threads" label_input="#rn:msg:ADD_ADDTL_INFORMATION_QUESTION_CMD#" initial_focus="true"/>
                <rn:widget path="input/FileAttachmentUpload" label_input="#rn:msg:ATTACH_ADDTL_DOCUMENTS_QUESTION_LBL#"/>
                <rn:widget path="input/FormInput" name="Incident.StatusWithType.Status" label_input="#rn:msg:DO_YOU_WANT_A_RESPONSE_MSG#"/>
                <rn:widget path="input/FormSubmit" label_button="#rn:msg:SUBMIT_CMD#" on_success_url="/app/account/questions/list" error_location="rn_ErrorLocation"/>
            </form>
        </div>
    <rn:condition_else/>
        <h2>#rn:msg:INC_REOPNED_UPD_FURTHER_ASST_PLS_MSG#</h2>
    </rn:condition>

    <div class="rn_Module">
        <rn:widget path="navigation/Accordion" toggle="rn_QuestionThread"/>
        <h2 id="rn_QuestionThread">#rn:msg:COMMUNICATION_HISTORY_LBL#<span class="rn_Expand"></span></h2>
        <div class="rn_Hidden rn_QuestionThreadContent">
            <rn:widget path="output/DataDisplay" name="Incident.Threads" label=""/>
        </div>
    </div>

    <div class="rn_Module">
        <rn:widget path="navigation/Accordion" toggle="rn_QuestionDetails"/>
        <h2 id="rn_QuestionDetails">#rn:msg:ADDITIONAL_DETAILS_LBL#<span class="rn_Expand"></span></h2>
        <div class="rn_Hidden rn_Padding">
            <rn:widget path="output/DataDisplay" name="Incident.PrimaryContact.Emails.PRIMARY.Address" left_justify="true" label="#rn:msg:EMAIL_ADDR_LBL#"/>
            <rn:widget path="output/DataDisplay" name="Incident.ReferenceNumber" left_justify="true" label="#rn:msg:REFERENCE_NUMBER_LBL#"/>
            <rn:widget path="output/DataDisplay" name="Incident.StatusWithType.Status" left_justify="true" label="#rn:msg:STATUS_LBL#"/>
            <rn:widget path="output/DataDisplay" name="Incident.CreatedTime" left_justify="true" label="#rn:msg:CREATED_LBL#"/>
            <rn:widget path="output/DataDisplay" name="Incident.UpdatedTime" left_justify="true" label="#rn:msg:UPDATED_LBL#"/>
            <rn:widget path="output/DataDisplay" name="Incident.Product" left_justify="true" label="#rn:msg:PRODUCT_LBL#"/>
            <rn:widget path="output/DataDisplay" name="Incident.Category" left_justify="true" label="#rn:msg:CATEGORY_LBL#"/>
            <rn:widget path="output/DataDisplay" name="Incident.FileAttachments" left_justify="true" label="#rn:msg:FILE_ATTACHMENTS_LBL#"/>
            <rn:widget path="output/CustomAllDisplay" table="Incident" left_justify="true"/>
        </div>
    </div>
</section>
