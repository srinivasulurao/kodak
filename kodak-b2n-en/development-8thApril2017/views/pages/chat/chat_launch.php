<rn:meta title="#rn:msg:LIVE_CHAT_LBL#" template="standard.php" clickstream="chat_request"/>

<div id="rn_PageTitle" class="rn_LiveHelp">
    <h1>#rn:msg:LIVE_HELP_HDG#</h1>
</div>

<div id="rn_PageContent" class="rn_Live">
    <div class="rn_Padding" >
        <div class="rn_ChatForm">
			<span class="rn_ChatLaunchFormHeader">#rn:msg:CHAT_MEMBER_OUR_SUPPORT_TEAM_LBL#</span>
			<form id="rn_ChatLaunchForm" method="post" action="/app/chat/chat_landing">
				<div id="rn_ErrorLocation"></div>
					<rn:condition config_check="intl_nameorder == 1">
						<rn:widget path="input/FormInput" name="Contact.Name.Last"
							label_input="#rn:msg:LAST_NAME_LBL#" required="true"/>
						<rn:widget path="input/FormInput" name="Contact.Name.First"
							label_input="#rn:msg:FIRST_NAME_LBL#" required="true"/>
					<rn:condition_else/>
						<rn:widget path="input/FormInput" name="Contact.Name.First"
							label_input="#rn:msg:FIRST_NAME_LBL#" required="true"/>
						<rn:widget path="input/FormInput" name="Contact.Name.Last"
							label_input="#rn:msg:LAST_NAME_LBL#" required="true"/>
					</rn:condition>
					<rn:widget path="input/FormInput" name="contacts.email" required="true" />
					<!-- optional fields -->
					<rn:widget path="input/CustomAllInput" table="incidents" chat_visible_only="true" />
				<br />
				<rn:widget path="chat/ChatLaunchButton" error_location="rn_ErrorLocation" />
				<br /><br />
            </form>
       </div>
       <rn:widget path="chat/ChatStatus2"/>
       <rn:widget path="chat/ChatHours2"/>
    </div>
</div>


