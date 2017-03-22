<rn:meta title="#rn:msg:LIVE_CHAT_LBL#" template="standard.php" clickstream="chat_request"/>

<div id="rn_PageTitle" class="rn_LiveHelp">
    <h1>#rn:msg:LIVE_HELP_HDG#</h1>
</div>

<div id="rn_PageContent" class="rn_Live">
    <div class="rn_Padding" >
        <rn:condition chat_available="true">
        <div id="rn_ChatLaunchFormDiv" class="rn_ChatForm">
            <span class="rn_ChatLaunchFormHeader">#rn:msg:CHAT_MEMBER_OUR_SUPPORT_TEAM_LBL#</span>
            <form id="rn_ChatLaunchForm" method="post" action="/app/chat/chat_landing">
                <div id="rn_ErrorLocation"></div>
                <rn:widget path="input/FormInput" name="Incident.Subject" initial_focus="true" label_input="#rn:msg:SUBJECT_LBL#"/>
                <rn:condition config_check="COMMON:intl_nameorder == 1">
                    <rn:widget path="input/FormInput" name="Contact.Name.Last" label_input="#rn:msg:LAST_NAME_LBL#" required="true"/>
                    <rn:widget path="input/FormInput" name="Contact.Name.First" label_input="#rn:msg:FIRST_NAME_LBL#" required="true"/>
                <rn:condition_else/>
                    <rn:widget path="input/FormInput" name="Contact.Name.First" label_input="#rn:msg:FIRST_NAME_LBL#" required="true"/>
                    <rn:widget path="input/FormInput" name="Contact.Name.Last" label_input="#rn:msg:LAST_NAME_LBL#" required="true"/>
                </rn:condition>
                <rn:widget path="input/FormInput" name="Contact.Emails.PRIMARY.Address" required="true" label_input="#rn:msg:EMAIL_ADDR_LBL#"/>
                <!-- optional fields -->
                <rn:widget path="input/CustomAllInput" table="Incident" chat_visible_only="true" always_show_mask="false" />
                <br />
                <rn:condition config_check="MOD_VA_ENABLED == 0">
                    <rn:widget path="chat/ChatLaunchButton" 
                        error_location="rn_ErrorLocation" 
                        add_params_to_url="q_id,pac,request_source,p,c,survey_send_id,survey_send_delay,survey_comp_id,survey_term_id,chat_data,survey_term_auth,survey_comp_auth,survey_send_auth,i_id"/>
                <rn:condition_else/>
                    <rn:widget path="chat/ChatLaunchButton" 
                        launch_height="700"
                        error_location="rn_ErrorLocation" 
                        add_params_to_url="q_id,pac,request_source,p,c,survey_send_id,survey_send_delay,survey_comp_id,survey_term_id,chat_data,survey_term_auth,survey_comp_auth,survey_send_auth,i_id"/>
                </rn:condition>
                <br /><br />
            </form>
       </div>
       </rn:condition>
       <rn:widget path="chat/ChatStatus"/>
       <rn:widget path="chat/ChatHours"/>
    </div>
</div>


