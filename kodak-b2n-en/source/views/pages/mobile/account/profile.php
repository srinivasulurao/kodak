<rn:meta title="#rn:msg:ACCOUNT_SETTINGS_LBL#" template="mobile.php" login_required="true" force_https="true" />

<section id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:ACCOUNT_SETTINGS_LBL#</h1>
</section>
<section id="rn_PageContent" class="rn_Profile">
    <div class="rn_Padding">
        <form id="rn_CreateAccount" onsubmit="return false;">
            <div class="rn_MessageBox rn_InfoMessage rn_Required rn_LargeText">#rn:url_param_value:msg#</div>
            <div id="rn_ErrorLocation"></div>
            <h2>#rn:msg:ACCT_HDG#</h2>
            <rn:widget path="input/FormInput" name="Contact.Login" required="true" validate_on_blur="true" initial_focus="true" label_input="#rn:msg:USERNAME_LBL#"/>
            <rn:condition external_login_used="false">
                <rn:condition config_check="EU_CUST_PASSWD_ENABLED == true">
                    <a href="/app/#rn:config:CP_CHANGE_PASSWORD_URL##rn:session#">#rn:msg:CHG_YOUR_PASSWORD_CMD#</a><br/><br/>
                </rn:condition>
            </rn:condition>
            <h2>#rn:msg:CONTACT_INFO_LBL#</h2>
            <rn:condition config_check="intl_nameorder == 1">
                <rn:widget path="input/FormInput" name="Contact.Name.Last" label_input="#rn:msg:LAST_NAME_LBL#" required="true"/>
                <rn:widget path="input/FormInput" name="Contact.Name.First" label_input="#rn:msg:FIRST_NAME_LBL#" required="true"/>
            <rn:condition_else/>
                <rn:widget path="input/FormInput" name="Contact.Name.First" label_input="#rn:msg:FIRST_NAME_LBL#" required="true"/>
                <rn:widget path="input/FormInput" name="Contact.Name.Last" label_input="#rn:msg:LAST_NAME_LBL#" required="true"/>
            </rn:condition>
            <rn:widget path="input/FormInput" name="Contact.Emails.PRIMARY.Address" required="true" validate_on_blur="true" label_input="#rn:msg:EMAIL_ADDR_LBL#"/>
            <rn:condition language_in="ja-JP,ko-KR,zh-CN,zh-HK,zh-TW">
                <rn:widget path="input/FormInput" name="Contact.Address.PostalCode" label_input="#rn:msg:POSTAL_CODE_LBL#" />
                <rn:widget path="input/FormInput" name="Contact.Address.Country" label_input="#rn:msg:COUNTRY_LBL#"/>
                <rn:widget path="input/FormInput" name="Contact.Address.StateOrProvince" label_input="#rn:msg:STATE_PROV_LBL#"/>
                <rn:widget path="input/FormInput" name="Contact.Address.City" label_input="#rn:msg:CITY_LBL#"/>
                <rn:widget path="input/FormInput" name="Contact.Address.Street" label_input="#rn:msg:STREET_LBL#"/>
            <rn:condition_else />
                <rn:widget path="input/FormInput" name="Contact.Address.Street" label_input="#rn:msg:STREET_LBL#"/>
                <rn:widget path="input/FormInput" name="Contact.Address.City" label_input="#rn:msg:CITY_LBL#"/>
                <rn:widget path="input/FormInput" name="Contact.Address.Country" label_input="#rn:msg:COUNTRY_LBL#"/>
                <rn:widget path="input/FormInput" name="Contact.Address.StateOrProvince" label_input="#rn:msg:STATE_PROV_LBL#"/>
                <rn:widget path="input/FormInput" name="Contact.Address.PostalCode" label_input="#rn:msg:POSTAL_CODE_LBL#" />
            </rn:condition>
            <rn:widget path="input/FormInput" name="Contact.Phones.HOME.Number" label_input="#rn:msg:HOME_PHONE_LBL#">
            <rn:widget path="input/FormInput" name="Contact.Phones.OFFICE.Number" label_input="#rn:msg:OFFICE_PHONE_LBL#"/>
            <rn:widget path="input/FormInput" name="Contact.Phones.MOBILE.Number" label_input="#rn:msg:MOBILE_PHONE_LBL#"/>
            <rn:widget path="input/CustomAllInput" table="Contact"/>
            <br/><br/><br/>
            <rn:condition external_login_used="false">
                <rn:widget path="input/FormSubmit" label_button="#rn:msg:SAVE_CHANGE_CMD#" on_success_url="/app/utils/submit/profile_updated" error_location="rn_ErrorLocation"/>
            </rn:condition>
        </form>
    </div>
</section>
