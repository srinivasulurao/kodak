<rn:meta title="#rn:msg:ACCOUNT_SETTINGS_LBL#" template="kodak_b2b_template.php" login_required="true" />
<?php
$ci=get_instance();
$contact=$ci->model('custom/profile_manager_model')->getContact();
$prod_id=$contact->CustomFields->c->prod_id;
$cat_id=$contact->CustomFields->c->cat_id;
?>
<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:ACCOUNT_SETTINGS_LBL#</h1>
</div>
<div id="rn_PageContent" class="rn_Profile">
    <div class="rn_Padding">
        <h2 class="rn_Required">#rn:url_param_value:msg#</h2>
        <form id="rn_CreateAccount" method="post" action="/cc/CustomProfileManager/saveProfile" onsubmit="return false;">
            <div id="rn_ErrorLocation"></div>
            <h2>#rn:msg:ACCT_HDG#</h2>
            <fieldset>
                <legend>#rn:msg:ACCT_HDG#</legend>
                <strong><rn:field name="contacts.login"/></strong><br/>
<!--                <rn:widget path="input/FormInput" name="contacts.login" required="true" validate_on_blur="true" initial_focus="true"/> -->
                <a href="/app/account/change_password#rn:session#">#rn:msg:CHG_YOUR_PASSWORD_CMD#</a>
            </fieldset>
            <h2>Search Preferences</h2>
            <fieldset>
            <legend>Search Preferences</legend>
<rn:widget path="input/ProductCategoryInput" linking_off="true" default_value="#rn:php:$prod_id#" name="Incident.Product"  label="#rn:msg:PROD_LBL#" />

<rn:widget path="input/ProductCategoryInput" linking_off="true" default_value="#rn:php:$cat_id#" name="Incident.Category"   label_input="#rn:msg:CATEGORY_LBL#" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#"/>

<rn:widget path="input/FormInput" name="contacts.c$search_text" table="contacts" />

<rn:widget path="input/FormInput" name="contacts.c$search_type" table="contacts" />

<rn:widget path="input/FormInput" name="contacts.c$lines_per_page" />
            </fieldset>
            <h2>#rn:msg:CONTACT_INFO_LBL#</h2>
            <fieldset>
                <legend>#rn:msg:CONTACT_INFO_LBL#</legend>
                <rn:widget path="input/ContactNameInput" table="contacts" required = "true"/>
                <rn:widget path="input/FormInput" name="contacts.email" required="true" validate_on_blur="true"/>
                <rn:widget path="input/FormInput" name="contacts.street" />
                <rn:widget path="input/FormInput" name="contacts.city" />
                <rn:widget path="input/FormInput" name="contacts.country_id" />
                <rn:widget path="input/FormInput" name="contacts.prov_id" />
                <rn:widget path="input/FormInput" name="contacts.postal_code" />
                <rn:widget path="input/FormInput" name="contacts.ph_home" label_input="Home Phone" />
                <rn:widget path="input/FormInput" name="contacts.ph_office" label_input="Office Phone"/>
                <rn:widget path="input/FormInput" name="contacts.ph_mobile" label_input="Mobile Phone"/>
                <rn:widget path="output/FieldDisplay" name="contacts.c$ek_super_sponsor_id" />
                <rn:widget path="output/FieldDisplay" name="contacts.c$ek_company" /><br/>
<!--                <rn:widget path="input/CustomAllInput" table="contacts" always_show_mask="true"/> -->
            </fieldset>
            <rn:widget path="input/FormSubmit" label_button="#rn:msg:SAVE_CHANGE_CMD#" on_success_url="/app/utils/submit/profile_updated" error_location="rn_ErrorLocation"/>
        </form>
    </div>
</div>

<style>
.yui3-widget-mask{
    display: none !important;
}
</style>