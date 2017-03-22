<rn:meta title="#rn:msg:LIVE_CHAT_LBL#" template="mobile.php" clickstream="chat_request"/>

<section id="rn_PageTitle" class="rn_LiveHelp">
    <h1>#rn:msg:CHAT_WITH_OUR_SUPPORT_TEAM_LBL#</h1>
</section>
<section id="rn_PageContent" class="rn_LiveHelp">
    <div class="rn_Padding" >
        <rn:condition chat_available="true">
            <div class="rn_ChatForm">
                <form id="rn_chatLaunchForm" method="post" action="/app/chat/chat_landing">
                    <div id="rn_ErrorLocation"></div>
                    <fieldset>
                        <rn:condition config_check="COMMON:intl_nameorder == 1">
                            <rn:widget path="input/FormInput" name="Contact.Name.Last" label_input="#rn:msg:LAST_NAME_LBL#" required="true"/>
                            <rn:widget path="input/FormInput" name="Contact.Name.First" label_input="#rn:msg:FIRST_NAME_LBL#" required="true"/>
                        <rn:condition_else/>
                            <rn:widget path="input/FormInput" name="Contact.Name.First" label_input="#rn:msg:FIRST_NAME_LBL#" required="true"/>
                            <rn:widget path="input/FormInput" name="Contact.Name.Last" label_input="#rn:msg:LAST_NAME_LBL#" required="true"/>
                        </rn:condition>
                        <rn:widget path="input/FormInput" name="Contact.Emails.PRIMARY.Address" required="true" label_input="#rn:msg:EMAIL_ADDR_LBL#"/>
                    </fieldset>
                    <br />
                    <rn:widget path="chat/ChatLaunchButton" 
                            error_location="rn_ErrorLocation" 
                            open_in_new_window="false"
                            label_form_header="" 
                            add_params_to_url="q_id,pac,request_source,p,c,survey_send_id,survey_send_delay,survey_comp_id,survey_term_id"/>
                    <br /><br />
                </form>
           </div>
       </rn:condition>
       <rn:widget path="chat/ChatStatus"/>
       <rn:widget path="chat/ChatHours"/>
    </div>
</section>


