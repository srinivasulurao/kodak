<rn:meta title="#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#" template="kodak_b2b_template.php" clickstream="incident_create"/>
<?    
$ask_msg_base_array=load_array("csv_ask.php");
?>
<div id="rn_PageTitle" class="rn_AskQuestion">
    <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
</div>
<style>
h1 {
    line-height: 1.3em;
	   font-size: 20px;
}

.yui3-widget-bd{
    display: none  !important;
}
</style>
<div id="rn_PageContent" class="rn_AskQuestion">
    <div class="rn_Padding"><? echo $templ_msg_base_array['lbl_incident_form_intro']; ?>
	</div>
    <div class="rn_Padding">
        <form id="rn_QuestionSubmit" method="post" action="" onsubmit="return false;">
            <div id="rn_ErrorLocation"></div>
            <rn:condition logged_in="false">
                <rn:widget path="input/FormInput" name="contacts.email" required="true" initial_focus="true"/>
                <rn:widget path="input/FormInput" name="incidents.subject" required="true" label_input="#rn:msg:SUBJECT_LBL#"  />
            </rn:condition>
            <rn:condition logged_in="true">
                <rn:widget  path="input/FormInput" name="incidents.subject" label_input="#rn:msg:SUBJECT_LBL#" required="true" initial_focus="true"/>
            </rn:condition>
                <rn:widget path="input/FormInput" name="incidents.thread" required="true" label_input="#rn:msg:DESCRIPTION_LBL#"/>
                <rn:widget path="input/ProductCategoryInput"  name="Incident.Product" required_lvl="1" DE_REQD_CAT_LVL="1" />
                <rn:widget path="input/ProductCategoryInput" table="incidents" name="Incident.Category" data_type="categories" label_input="#rn:msg:CATEGORY_LBL#" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#" required_lvl="1" label_required="is required" />
 
				<rn:widget path="input/FileAttachmentUpload"/>
<!-- <style>
input {
border-bottom:none;
border-left:none;
border-right:none;
border-top:none;
}
</style>
-->
				<br/><div style="font-size:14px; font-weight:bold;">Recommended Audience (Check all that apply)</div>
				<table>
				<tr>
				<td width="250">
				<rn:widget path="input/FormInput" name="incidents.c$ek_km_rec_aud_auth_cust" display_as_checkbox="true" />
				</td>
				<td width="250">
				<rn:widget path="input/FormInput" name="incidents.c$ek_km_rec_aud_dealer"  display_as_checkbox="true" />
				</td>
				<td width="250"><rn:widget path="input/FormInput" name="incidents.c$ek_km_rec_aud_everyone"  display_as_checkbox="true" />
				</td></tr>
				<tr>
				<td width="250">
				<rn:widget path="input/FormInput" name="incidents.c$ek_km_rec_aud_internal" display_as_checkbox="true" />
				</td>
				<td width="250" colspan="3">
				<rn:widget path="input/FormInput" name="incidents.c$ek_km_rec_aud_sme"  display_as_checkbox="true" />
				</td>
				</tr></table>
				

				<br/><div id="toggleitem" style="font-size:14px; font-weight:bold;"><? echo $ask_msg_base_array['morefieldslabel']; ?></div>
<style>
.rn_TextArea {
height:50px;

}
a.rn_Collapsed {
background:none; cursor:none;

}


</style>

				<div style="display:block;margin-left:15px;" id="titem">
                <rn:widget path="input/FormInput" name="incidents.c$ek_km_operating_environment"  />
                <rn:widget path="input/FormInput" name="incidents.c$ek_km_affected_products"  />
                <rn:widget path="input/FormInput" name="incidents.c$ek_km_symptoms"  />
                <rn:widget path="input/FormInput" name="incidents.c$ek_km_error_messages"  />
                <rn:widget path="input/FormInput" name="incidents.c$ek_km_cause"  />
                <rn:widget path="input/FormInput" name="incidents.c$ek_km_solution"  />
                <rn:widget path="input/FormInput" name="incidents.c$ek_km_related_information"  />
				</div>
<!--                <rn:widget path="input/CustomAllInput" table="incidents" always_show_mask="true"/>  -->
                <!-- <rn:widget path="input/FormSubmit" label_button="#rn:msg:CONTINUE_ELLIPSIS_CMD#" on_success_url="/app/ask_confirm" error_location="rn_ErrorLocation" /> -->
				 <rn:widget path="input/FormSubmit" label_button="#rn:msg:SUBMIT_CMD#" on_success_url="/app/ask_confirm" error_location="rn_ErrorLocation" /> 
<!--				<rn:widget path="navigation/Accordion"  item_to_toggle="titem" toggle="toggleitem"	/>
-->
        </form>
        <rn:condition answers_viewed="2" searches_done="1">
        <rn:condition_else/>
            <rn:widget path="input/SmartAssistantDialog"/>
        </rn:condition>
    </div>
</div>

