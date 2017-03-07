<rn:meta title="#rn:msg:CREATE_NEW_ACCT_HDG#" template="standard.php" login_required="false" redirect_if_logged_in="account/overview" force_https="true" />
<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:CREATE_AN_ACCOUNT_CMD#</h1>
</div>
<div id="rn_PageContent" class="rn_CreateAccount">
    <div class="rn_Padding">
        <p><strong>#rn:msg:SERVICES_MSG# #rn:msg:LOG_IN_OR_REGISTER_USING_ELLIPSIS_MSG#</strong></p>
        <div class="rn_ThirdPartyLogin">
            <rn:widget path="login/OpenLogin"/>
            <rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/oauth/authorize/twitter" label_service_button="Twitter" label_process_explanation="#rn:msg:CLICK_BTN_TWITTER_LOG_TWITTER_MSG#" label_login_button="#rn:msg:LOG_IN_USING_TWITTER_LBL#"/>
            <rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize/google" label_service_button="Google" label_process_explanation="#rn:msg:CLICK_BTN_GOOGLE_LOG_GOOGLE_VERIFY_MSG#" label_login_button="#rn:msg:LOG_IN_USING_GOOGLE_LBL#"/>
            <rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize/yahoo" label_service_button="Yahoo" label_process_explanation="#rn:msg:CLICK_BTN_YAHOO_LOG_YAHOO_VERIFY_MSG#" label_login_button="#rn:msg:LOG_IN_USING_YAHOO_LBL#"/>
            <rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button="AOL" openid="true" preset_openid_url="http://openid.aol.com/[username]" openid_placeholder="[#rn:msg:YOUR_AOL_USERNAME_LBL#]" label_process_explanation="#rn:msg:YOULL_AOL_LOG_AOL_VERIFY_SEND_YOULL_MSG#" label_login_button="#rn:msg:LOG_IN_USING_YOUR_AOL_ACCOUNT_LBL#"/>
            <rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button="MyOpenID" openid="true" preset_openid_url="http://[username].myopenid.com" openid_placeholder="[#rn:msg:YOUR_MYOPENID_USERNAME_LBL#]" label_process_explanation="#rn:msg:YOULL_MYOPENID_LOG_MYOPENID_VERIFY_MSG#" label_login_button="#rn:msg:LOG_IN_USING_MYOPENID_LBL#"/>
            <rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button="Wordpress" openid="true" preset_openid_url="http://[username].wordpress.com" openid_placeholder="[#rn:msg:YOUR_WORDPRESS_USERNAME_LBL#]" label_process_explanation="#rn:msg:YOULL_LOG_ACCT_WORDPRESS_TAB_ENTER_MSG#" label_login_button="#rn:msg:LOG_USING_YOUR_WORDPRESS_ACCOUNT_LBL#"/>
            <rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button="OpenID" openid="true" openid_placeholder="http://[provider]" label_process_explanation="#rn:msg:YOULL_OPENID_PROVIDER_LOG_PROVIDER_MSG#" label_login_button="#rn:msg:LOG_IN_USING_THIS_OPENID_PROVIDER_LBL#"/>    
        </div>
        <p><strong>#rn:msg:CONTINUE_CREATING_ACCOUNT_ELLIPSIS_CMD#</strong></p>
        <form id="rn_CreateAccount" onsubmit="return false;">
            <div id="rn_ErrorLocation"></div>
            <rn:widget path="input/FormInput" name="Contact.Emails.PRIMARY.Address" required="true" validate_on_blur="true" initial_focus="true" label_input="#rn:msg:EMAIL_ADDR_LBL#"/>
            <rn:widget path="input/FormInput" name="Contact.Login" required="true" validate_on_blur="true" label_input="#rn:msg:USERNAME_LBL#"/>
            <rn:condition config_check="EU_CUST_PASSWD_ENABLED == true">
                <rn:widget path="input/FormInput" name="Contact.NewPassword" require_validation="true" label_input="#rn:msg:PASSWORD_LBL#" label_validation="#rn:msg:VERIFY_PASSWD_LBL#"/>
            </rn:condition>
            <rn:condition config_check="intl_nameorder == 1">
                <rn:widget path="input/FormInput" name="Contact.Name.Last" label_input="#rn:msg:LAST_NAME_LBL#" required="true"/>
                <rn:widget path="input/FormInput" name="Contact.Name.First" label_input="#rn:msg:FIRST_NAME_LBL#" required="true"/>
            <rn:condition_else/>
                <rn:widget path="input/FormInput" name="Contact.Name.First" label_input="#rn:msg:FIRST_NAME_LBL#" required="true"/>
                <rn:widget path="input/FormInput" name="Contact.Name.Last" label_input="#rn:msg:LAST_NAME_LBL#" required="true"/>
            </rn:condition>
            <rn:widget path="input/CustomAllInput" table="Contact"/>
            <rn:widget path="input/FormSubmit" label_button="#rn:msg:CREATE_ACCT_CMD#" on_success_url="/app/account/overview" error_location="rn_ErrorLocation"/>
        </form>
    </div>
</div>
