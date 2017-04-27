<rn:meta title="#rn:msg:ACCOUNT_SETTINGS_LBL#" template="kodak_b2b_template.php" login_required="true" />

<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:ACCOUNT_SETTINGS_LBL#</h1>
</div>
<div id="rn_PageContent" class="rn_Profile">
    <div class="rn_Padding">
        <h2 class="rn_Required">#rn:url_param_value:msg#</h2>
        <form id="rn_CreateAccount" method="post" action="" onsubmit="return false;">
            <div id="rn_ErrorLocation"></div>
            <h2>#rn:msg:ACCT_HDG#</h2>
            <fieldset>
                <legend>#rn:msg:ACCT_HDG#</legend>
                <strong><rn:field name="contacts.login"/></strong><br/>
<!--                <rn:widget path="input/FormInput" name="contacts.login" required="true" validate_on_blur="true" initial_focus="true"/> -->
            </fieldset>
<!--			
            <rn:condition external_login_used="false" show_on_pages="/app/account/profile">
            <h2>Search Preferences</h2>
            <fieldset>
            <legend>Search Preferences</legend>
<rn:widget path="input/ProductCategoryInput" table="contacts" data_type="products" label="#rn:msg:PROD_LBL#" />

<rn:widget path="input/ProductCategoryInput" table="contacts" data_type="categories" label_input="#rn:msg:CATEGORY_LBL#" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#"/>

<rn:widget path="input/FormInput" name="contacts.search_text" />

<rn:widget path="input/FormInput" name="contacts.search_type" />

<rn:widget path="input/FormInput" name="contacts.lines_per_page" />
			</fieldset>
            </rn:condition>
-->			
            <h2>#rn:msg:CONTACT_INFO_LBL#</h2>
            <fieldset>
                <legend>#rn:msg:CONTACT_INFO_LBL#</legend>
                <rn:widget path="input/ContactNameInput" table="contacts" required = "true"/>
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

                <rn:widget path="output/FieldDisplay" name="contacts.email"  /> <br />
                <rn:widget path="input/FormInput" name="contacts.c$ek_country_safe_harbor" required="true" />
                <rn:widget path="input/FormInput" name="contacts.ph_office" />
                <rn:widget path="input/FormInput" name="contacts.ph_mobile" />
                <rn:widget path="input/FormInput" name="contacts.ph_fax" />
                <rn:widget path="input/FormInput" name="contacts.c$ek_lang_pref1" required="true" />
                <rn:widget path="input/FormInput" name="contacts.c$ek_lang_pref2" />
                <rn:widget path="input/FormInput" name="contacts.c$ek_lang_pref3" />
                <rn:widget path="input/FormInput" name="contacts.ma_opt_in" hint="If set to Yes the contact will be eligible to receive email communications from Eastman Kodak Company about its products and services" 
 />
                <rn:widget path="input/FormInput" name="contacts.c$ek_closed_in_optin" />
                <rn:widget path="input/FormInput" name="contacts.c$ek_inc_update_optin" />
            </fieldset>
                <rn:condition external_login_used="false" show_on_pages="/app/account/profile">
            
            <rn:widget path="input/FormSubmit" label_button="#rn:msg:SAVE_CHANGE_CMD#" on_success_url="/app/utils/submit/profile_updated" error_location="rn_ErrorLocation"/>
            </rn:condition>
        </form>
    </div>
</div>
