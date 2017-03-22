
<rn:meta title="#rn:msg:NOTIFICATIONS_HDG#" template="iframekodak_b2b_template.php" />
<rn:condition logged_in = "false" >
<rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button=""  openid="true" 
						openid_placeholder="true"
						label_process_explanation="#rn:msg:YOULL_OPENID_PROVIDER_LOG_PROVIDER_MSG#" label_login_button="#rn:msg:LOG_IN_USING_THIS_OPENID_PROVIDER_LBL#"
redirect_url="/app/account/notif/iframelist"	/>
</rn:condition>
<div id="iframepage">
<style>
#rn_TextInput > label {
	color:#333333;
}	
#rn_PageContent form h2 {	
    border:none;
}
body, fieldset legend {
    color: #333333;
}
</style>
<div id="rn_PageTitle" class="rn_Account">
    <span style="font-size:120%;font-weight:bold;color:#666666;">My Kodak Services Account Information</span>
</div>
<div id="rn_PageContent" class="rn_Profile">
    <div class="rn_Padding">
        <h2 class="rn_Required">#rn:url_param_value:msg#</h2>

        <form id="rn_CreateAccount" method="post" action="" onsubmit="return false;">
            <div id="rn_ErrorLocation"></div>
<!--            <h2>#rn:msg:ACCT_HDG#</h2>
            <fieldset>
                <legend>#rn:msg:ACCT_HDG#</legend>
                <strong><rn:field name="contacts.login"/></strong><br/>
            </fieldset>
			-->
            <span style="font-size:120%;font-weight:bold;color:#666666;">#rn:msg:CONTACT_INFO_LBL#</span>
            <fieldset>
                <legend>#rn:msg:CONTACT_INFO_LBL#</legend>
<!--                <rn:widget path="input/ContactNameInput" table="contacts" required = "true"/>   -->
<!--                <rn:widget path="output/FieldDisplay" name="contacts.email"  /> <br />   -->
                <!--<rn:widget path="input/FormInput" name="contacts.c$ek_country_safe_harbor" required="true" />-->
                <!--<rn:widget path="input/FormInput" name="contacts.ph_office" />  -->
                <rn:widget path="input/FormInput" name="contacts.ph_mobile" />
                <rn:widget path="input/FormInput" name="contacts.ph_fax" />
<!--                <rn:widget path="input/FormInput" name="contacts.c$ek_lang_pref1" required="true" />  -->
                <rn:widget path="input/FormInput" name="contacts.c$ek_lang_pref2" />
                <rn:widget path="input/FormInput" name="contacts.c$ek_lang_pref3" />
                <rn:widget path="input/FormInput" name="contacts.ma_opt_in" hint="If set to Yes the contact will be eligible to receive email communications from Eastman Kodak Company about its products and services" 
 />
                <rn:widget path="input/FormInput" name="contacts.c$ek_closed_in_optin" />
                <rn:widget path="input/FormInput" name="contacts.c$ek_inc_update_optin" />
            </fieldset>
            <span style="font-size:120%;font-weight:bold;color:#666666;">Search Preferences</span>
            <fieldset>
            <legend>Search Preferences</legend>
<rn:widget path="input/ProductCategoryInput" table="contacts" data_type="products" label="#rn:msg:PROD_LBL#" />

<rn:widget path="input/ProductCategoryInput" table="contacts" data_type="categories" label_input="#rn:msg:CATEGORY_LBL#" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#"/>

<rn:widget path="input/FormInput" name="contacts.search_text" />

<rn:widget path="input/FormInput" name="contacts.search_type" />

<rn:widget path="input/FormInput" name="contacts.lines_per_page" />
			</fieldset>
           
            <rn:widget path="input/FormSubmit" label_button="#rn:msg:SAVE_CHANGE_CMD#" label_confirm_dialog = "Changes Saved Successfully" error_location="rn_ErrorLocation" loading_icon_path="/euf/assets/themes/Kodak/images/indicator.gif" />
        </form>
   </div><br>
	
	
    <div class="rn_Padding">
        <span style="font-size:120%;font-weight:bold;color:#666666;">#rn:msg:ANSWER_LBL# #rn:msg:NOTIFICATIONS_HDG#</span>
        <rn:widget path="notifications/AnswerNotificationManager" />
		
    </div>
</div>
</div>
