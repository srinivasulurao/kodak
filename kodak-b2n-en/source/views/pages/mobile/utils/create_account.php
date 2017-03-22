<rn:meta title="#rn:msg:CREATE_NEW_ACCT_HDG#" template="mobile.php" login_required="false" redirect_if_logged_in="account/questions/list" force_https="true" />
<section id="rn_PageTitle" class="rn_CreateAccount">
    <h1>#rn:msg:CREATE_AN_ACCOUNT_CMD#</h1>
</section>
<section id="rn_PageContent" class="rn_CreateAccount">
    <div class="rn_Padding">
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
            <br/><br/>
            <rn:widget path="input/FormSubmit" label_button="#rn:msg:CREATE_ACCT_CMD#" on_success_url="/app/account/questions/list" error_location="rn_ErrorLocation"/>
        </form>
    </div>
</section>
