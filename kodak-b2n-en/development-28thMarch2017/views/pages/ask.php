<rn:meta title="#rn:msg:ASK_QUESTION_HDG#" template="kodak_b2b_template.php" clickstream="incident_create" login_required="true"/>

<div id="rn_PageTitle" class="rn_AskQuestion">
    <h1>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</h1>
</div>
<div id="rn_PageContent" class="rn_AskQuestion">
    <div class="rn_Padding">
        <form id="rn_QuestionSubmit" method="post" action="" onsubmit="return false;">
            <div id="rn_ErrorLocation"></div>
            <rn:condition logged_in="false">
                <rn:widget path="input/FormInput" name="contacts.email" required="true" initial_focus="true"/>
                <rn:widget path="input/FormInput" name="incidents.subject" required="true" />
            </rn:condition>
            
			<rn:widget path="custom/input/ProductCategoryInput" name="Incident.Category" label_input="Problem-Fault" label_required="You must select two levels" data_type="categories" label_nothing_selected="Select problem-fault" table="incidents" required_lvl="2" readonly_off="true" cust_interface_id="17" />

            <rn:widget path="custom/input/ProductCategoryInput" table="incidents" data_type="products" name="Incident.Product"/>
            
            <rn:widget path="input/FormInput" name="incidents.c$ek_k_number" label_input="K#" />
            <rn:widget path="input/FormInput" name="incidents.c$ek_serial_number" label_input="Serial Number" />
           
            <rn:widget path="input/FormInput" name="incidents.thread" required="true" label_input="#rn:msg:QUESTION_LBL#"/>
            <rn:widget path="input/FormInput" name="incidents.c$ek_error_code" label="Error Code" />
            <rn:widget path="input/FormInput" name="incidents.c$ek_severity" label="Severity" required="true" />
            <rn:widget path="input/FormInput" name="incidents.c$ek_repeatability" label="Repeatability" required="true" />

            <rn:widget path="input/FormInput" name="incidents.c$ek_service_profile" hide="true"/>
            <rn:widget path="input/FormInput" name="incidents.c$ek_response_profile" hide="true"/>

            <rn:widget path="input/FileAttachmentUpload"/>

            <rn:widget path="input/FormSubmit" label_button="#rn:msg:CONTINUE_ELLIPSIS_CMD#" on_success_url="/app/ask_confirm" error_location="rn_ErrorLocation" />
<!--
            <rn:widget path="input/FormInput" name="incidents.c$ek_equip_component_id" hide="true"/>
            <rn:widget path="input/FormInput" name="incidents.c$ek_sap_product_id" hide="true"/>

            <rn:widget path="input/FormInput" name="incidents.c$ek_sap_soldto_custid" hide="true"/>

          <rn:widget path="input/SelectionInput" name="incidents.c$ek_sds" label_input="SDS" />
 -->             
        </form>
    </div>
</div>

