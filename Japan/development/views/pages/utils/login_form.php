<rn:meta title="#rn:msg:SUPPORT_LOGIN_HDG#" template="kodak_b2b_template_nologin.php" login_required="false" redirect_if_logged_in="account/overview" />

<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:LOG_IN_UC_LBL#</h1>
</div>

<div id="rn_PageContent" class="rn_Account">
    <div class="rn_Padding" >
        <div class="rn_Column rn_LeftColumn">
            <p>#rn:msg:ACCOUNT_ENTER_USERNAME_PASSWORD_MSG#</p>
            <rn:widget path="login/LoginForm" redirect_url="/app/account/overview" initial_focus="true"/>
            <br/>
            <a href="/app/utils/account_assistance#rn:session#">#rn:msg:FORGOT_YOUR_USERNAME_OR_PASSWORD_MSG#</a>
        </div>
<?/*       <div class="rn_Column rn_RightColumn rn_CreateAccountInfo" style="height:210px;">
		<ul><li>
<? echo $templ_msg_base_array['SKVlogin_msg1']; ?></li>

<li><? echo $templ_msg_base_array['SKVlogin_msg2']; ?></li>

<li><? echo $templ_msg_base_array['SKVlogin_msg3']; ?></li></ul>
<!--
            <h2>#rn:msg:NOT_REGISTERED_YET_MSG#</h2>
            <ul>
                <li class="rn_Communicate">#rn:msg:SERVE_ENABLING_FASTER_LINE_COMM_MSG#</li>
                <li class="rn_Feedback">#rn:msg:NOTIFICATIONS_INFO_CARE_UPDATED_MSG#</li>
                <li class="rn_Customize">#rn:msg:CUSTOMIZE_YOUR_SUPPORT_INTERESTS_MSG#</li>
            </ul>
            <button type="button" onclick="window.location='/app/utils/create_account/redirect/<?=urlencode(getUrlParm('redirect'));?>#rn:session#';">#rn:msg:CREATE_NEW_ACCT_CMD#</button>
        </div> -->
		</div> */?>
    </div>
</div>
