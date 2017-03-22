<rn:meta title="#rn:msg:CHANGE_YOUR_PASSWORD_CMD#" template="standard.php" login_required="true" force_https="true"/>

<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:CHANGE_YOUR_PASSWORD_CMD#</h1>
</div>
<div id="rn_PageContent" class="rn_Account">
    <div class="rn_Padding">
        <div class="rn_Required rn_LargeText">#rn:url_param_value:msg#</div>
        <div id="rn_ErrorLocation"></div>
        <form id="rn_ChangePassword" onsubmit="return false;">
            <rn:widget path="input/PasswordInput" name="Contact.NewPassword" require_validation="true" require_current_password="true" label_input="#rn:msg:PASSWORD_LBL#" label_validation="#rn:msg:VERIFY_PASSWD_LBL#" initial_focus="true"/>
            <rn:widget path="input/FormSubmit" on_success_url="/app/utils/submit/password_changed" error_location="rn_ErrorLocation"/>
        </form>
    </div>
</div>
