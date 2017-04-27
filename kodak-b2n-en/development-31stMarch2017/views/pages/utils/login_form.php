<rn:meta title="#rn:msg:SUPPORT_LOGIN_HDG#" template="kodak_b2b_template.php" login_required="false" />

<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:LOG_IN_UC_LBL#</h1>
</div>

<div id="rn_PageContent" class="rn_Account">
    <div class="rn_Padding" >
        <div class="rn_Column rn_LeftColumn">

<rn:condition logged_in = "false" >
<rn:condition url_parameter_check="redirect != null">
<rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button=""  openid="true"
                                                openid_placeholder="true"
                                                label_process_explanation="#rn:msg:YOULL_OPENID_PROVIDER_LOG_PROVIDER_MSG#" label_login_button="#rn:msg:LOG_IN_USING_THIS_OPENID_PROVIDER_LBL#"
redirect_url="app/home" />
<rn:condition_else>
<rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button=""  openid="true"
                                                openid_placeholder="true"
                                                label_process_explanation="#rn:msg:YOULL_OPENID_PROVIDER_LOG_PROVIDER_MSG#" label_login_button="#rn:msg:LOG_IN_USING_THIS_OPENID_PROVIDER_LBL#"
redirect_url="#rn:url_param:redirect#"  />

</rn:condition>
</rn:condition>

            <p>#rn:msg:ACCOUNT_ENTER_USERNAME_PASSWORD_MSG#</p>
            <rn:widget path="login/LoginForm2" redirect_url="/app/account/overview" initial_focus="true"/>
        </div>
    </div>
</div>
