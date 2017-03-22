<rn:meta title="#rn:msg:SUPPORT_LOGIN_HDG#" template="standard.php" login_required="false" redirect_if_logged_in="account/overview" force_https="true" />

<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:LOG_IN_UC_LBL#</h1>
</div>

<div id="rn_PageContent" class="rn_Account rn_LoginForm">
    <div class="rn_Padding">
        <br/>
        <div class="rn_Column rn_LeftColumn rn_ThirdPartyLogin">
            <h2>#rn:msg:SERVICES_MSG#</h2>
            <br/>#rn:msg:LOG_IN_OR_REGISTER_USING_ELLIPSIS_MSG#<br/>
            <rn:widget path="login/OpenLogin" display_in_dialog="false"/> <? /* Attributes Default to Facebook */ ?>
            <rn:widget path="login/OpenLogin" display_in_dialog="false" controller_endpoint="/ci/openlogin/oauth/authorize/twitter" label_service_button="Twitter" label_process_explanation="#rn:msg:CLICK_BTN_TWITTER_LOG_TWITTER_MSG#" label_login_button="#rn:msg:LOG_IN_USING_TWITTER_LBL#"/>
            <rn:widget path="login/OpenLogin" display_in_dialog="false" controller_endpoint="/ci/openlogin/openid/authorize/google" label_service_button="Google" label_process_explanation="#rn:msg:CLICK_BTN_GOOGLE_LOG_GOOGLE_VERIFY_MSG#" label_login_button="#rn:msg:LOG_IN_USING_GOOGLE_LBL#"/>
            <rn:widget path="login/OpenLogin" display_in_dialog="false" controller_endpoint="/ci/openlogin/openid/authorize/yahoo" label_service_button="Yahoo" label_process_explanation="#rn:msg:CLICK_BTN_YAHOO_LOG_YAHOO_VERIFY_MSG#" label_login_button="#rn:msg:LOG_IN_USING_YAHOO_LBL#"/>
            <rn:widget path="login/OpenLogin" display_in_dialog="false" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button="AOL" openid="true" preset_openid_url="http://openid.aol.com/[username]" openid_placeholder="[#rn:msg:YOUR_AOL_USERNAME_LBL#]" label_process_explanation="#rn:msg:YOULL_AOL_LOG_AOL_VERIFY_SEND_YOULL_MSG#" label_login_button="#rn:msg:LOG_IN_USING_YOUR_AOL_ACCOUNT_LBL#"/>
            <rn:widget path="login/OpenLogin" display_in_dialog="false" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button="MyOpenID" openid="true" preset_openid_url="http://[username].myopenid.com" openid_placeholder="[#rn:msg:YOUR_MYOPENID_USERNAME_LBL#]" label_process_explanation="#rn:msg:YOULL_MYOPENID_LOG_MYOPENID_VERIFY_MSG#" label_login_button="#rn:msg:LOG_IN_USING_MYOPENID_LBL#"/>
            <rn:widget path="login/OpenLogin" display_in_dialog="false" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button="Wordpress" openid="true" preset_openid_url="http://[username].wordpress.com" openid_placeholder="[#rn:msg:YOUR_WORDPRESS_USERNAME_LBL#]" label_process_explanation="#rn:msg:YOULL_LOG_ACCT_WORDPRESS_TAB_ENTER_MSG#" label_login_button="#rn:msg:LOG_USING_YOUR_WORDPRESS_ACCOUNT_LBL#"/>
            <rn:widget path="login/OpenLogin" display_in_dialog="false" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button="OpenID" openid="true" openid_placeholder="http://[provider]" label_process_explanation="#rn:msg:YOULL_OPENID_PROVIDER_LOG_PROVIDER_MSG#" label_login_button="#rn:msg:LOG_IN_USING_THIS_OPENID_PROVIDER_LBL#"/>
        </div>
        <span class="rn_MiddleBuffer">#rn:msg:OR_CAPS_LBL#</span>
        <div class="rn_Column rn_RightColumn">
            <h2>#rn:msg:LOG_IN_WITH_AN_EXISTING_ACCOUNT_LBL#</h2><br/>
            <rn:widget path="login/LoginForm" redirect_url="/app/account/overview" initial_focus="true"/>
            <br/>
            <a href="/app/#rn:config:CP_ACCOUNT_ASSIST_URL##rn:session#">#rn:msg:FORGOT_YOUR_USERNAME_OR_PASSWORD_MSG#</a>
            <br/><br/>
            #rn:msg:NOT_REGISTERED_YET_MSG#
            <a href="/app/utils/create_account/redirect/<?=urlencode(\RightNow\Utils\Url::getParameter('redirect'));?>#rn:session#">#rn:msg:SIGN_UP_LBL#</a>
        </div>
    </div>
</div>
