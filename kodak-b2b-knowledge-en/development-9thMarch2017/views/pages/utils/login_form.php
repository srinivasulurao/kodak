<rn:meta title="#rn:msg:SUPPORT_LOGIN_HDG#" template="kodak_b2b_template_nologin.php" login_required="false" redirect_if_logged_in="account/overview" />

<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:LOG_IN_UC_LBL#</h1>
</div>

<div id="rn_PageContent" class="rn_Account">
    <div class="rn_Padding" >
        <div class="rn_Column rn_LeftColumn">
            <p><!--#rn:msg:ACCOUNT_ENTER_USERNAME_PASSWORD_MSG# --></p>
            <!--<rn:widget path="custom/login/LoginForm2" redirect_url="/app/account/overview" initial_focus="true"/> -->
            <rn:widget path="login/LoginForm" redirect_url="/app/account/overview" initial_focus="true"/><br/>
            <br/>
            <a href="/app/utils/account_assistance#rn:session#">#rn:msg:FORGOT_YOUR_USERNAME_OR_PASSWORD_MSG#</a>
        </div>
        <div class="rn_Column rn_RightColumn rn_CreateAccountInfo">
		<ul><li>
Services Knowledge Vault  contact accounts and passwords are set independently from your Kodak NetPass account.</li>

<li>If you do not have a Services Knowledge Vault account established, the "Forgot your password?" link will not work.</li>

<li>If you require a Services Knowledge Vault account or continue to experience difficulties logging in, please refer to this article on  <a href="https://kodak.service-now.com/nav_to.do?uri=kb_view.do%3Fsysparm_article=KB0023599">HelpIT</a>.</li></ul>
<!--
            <h2>#rn:msg:NOT_REGISTERED_YET_MSG#</h2>
            <ul>
                <li class="rn_Communicate">#rn:msg:SERVE_ENABLING_FASTER_LINE_COMM_MSG#</li>
                <li class="rn_Feedback">#rn:msg:NOTIFICATIONS_INFO_CARE_UPDATED_MSG#</li>
                <li class="rn_Customize">#rn:msg:CUSTOMIZE_YOUR_SUPPORT_INTERESTS_MSG#</li>
            </ul>
            <button type="button" onclick="window.location='/app/utils/create_account/redirect/<?=urlencode(getUrlParm('redirect'));?>#rn:session#';">#rn:msg:CREATE_NEW_ACCT_CMD#</button>
        </div> -->
		</div>
    </div>
</div>