<?	$this->CI = & get_instance();	$sesslang = $this->CI->session->getSessionData("lang");	switch ($sesslang) {        case "en":			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 			break;        case "fr":			$cih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 			break;        case "es":			$cih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 			break;        case "pt":			$cih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 			break;        default:			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 			break;	}						?><rn:meta title="#rn:php:SEO::getDynamicTitle('incident', getUrlParm('i_id'))#" template="kodak_b2b_template_fullwidth.php" login_required="true" clickstream="incident_view"/>
<div id="rn_PageContent" class="rn_QuestionDetail">
    <div class="rn_Padding">
        <rn:condition incident_reopen_deadline_hours="0">
            <h2 class="rn_HeadingBar"><? echo $cih_lang_msg_base_array['update_service_req_info']; ?></h2>
            <div id="rn_ErrorLocation"></div>
            <form id="rn_UpdateQuestion" method="post" action="" onsubmit="return false;">
                <rn:widget path="custom/input/CustomFileAttachmentUpload2" label_input="#rn:php:$cih_lang_msg_base_array['attach_additional_documents']#"/>
                    <div id="rn_FileAttach">
                    </div>
                  <rn:widget path="input/FormSubmit" on_success_url="/app/cih/service_request_detail/obj_id/#rn:php:getUrlParm('i_id')#" error_location="rn_ErrorLocation" label_button="#rn:php:$cih_lang_msg_base_array['submit']#" />
            </form>
       <rn:condition_else/>
            <h2 class="rn_HeadingBar">#rn:msg:INC_REOPENED_UPD_FURTHER_ASST_PLS_MSG#</h2>
        </rn:condition> 

    </div>
</div>
