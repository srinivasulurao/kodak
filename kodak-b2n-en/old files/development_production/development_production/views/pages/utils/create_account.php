<rn:meta title="#rn:msg:CREATE_NEW_ACCT_HDG#" template="kodak_b2b_template.php" />
<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:CREATE_AN_ACCOUNT_CMD#</h1>
</div>
<div id="rn_PageContent" class="rn_CreateAccount">
    <div class="rn_Padding">
        <form id="rn_CreateAccount" method="post" action="" onsubmit="return false;">
      
            <div id="rn_ErrorLocation"></div>
            <rn:widget path="custom/output/DialogPopup" title="Safe Harbor" message="Please be aware we are recording your name and contact details for the purpose of this and future incidents.  This data will be saved on one of our servers in the US.  Do you agree to Kodak recording your name and contact details?" width="450" />
            
            <rn:widget path="input/FormInput" name="contacts.email" required="true" validate_on_blur="true" initial_focus="true" hint="The email address provided will be your userid and will be used for all incident correspondence. If updates to your email address are required please contact your local call center for assistance"/>
            <rn:widget path="input/ContactNameInput" required="true"/>
            <rn:widget path="input/FormInput" name="contacts.ph_office" label_input="Office Phone" />
            <rn:widget path="input/FormInput" name="contacts.ph_mobile" label_input="Mobile Phone" />
            <rn:widget path="input/FormInput" name="contacts.ph_fax" label_input="Fax" />
            <rn:widget path="input/FormInput" name="contacts.ma_opt_in" hint="If set to Yes the contact will be eligible to receive email communications from Eastman Kodak Company about its products and services" 
 />
            <rn:widget path="input/FormInput" name="contacts.c$ek_inc_update_optin" />
            <rn:widget path="input/FormInput" name="contacts.c$ek_closed_in_optin" label_input="Closed Incident Survey Opt In" />
            <rn:widget path="input/FormInput" name="contacts.c$ek_country_safe_harbor" />
            <rn:widget path="input/FormInput" name="contacts.c$ek_lang_pref1" required="true"/>
            <rn:widget path="input/FormInput" name="contacts.c$ek_lang_pref2" />
            <rn:widget path="input/FormInput" name="contacts.c$ek_lang_pref3" />
            <rn:widget path="input/FormInput" name="contacts.c$ek_customer_name" required="true" />
            <p> 
              • Please complete at least one of the data fields below (two or more will help us expedite your account request).
            </p>
            <rn:widget path="input/FormInput" name="contacts.c$ek_customer_number" />
            <rn:widget path="input/FormInput" name="contacts.c$ek_knumber" />
            <rn:widget path="input/FormInput" name="contacts.c$ek_serial_number" label_input="Equipment Serial Number" />
            
            <rn:widget path="input/FormSubmit" label_button="#rn:msg:CREATE_ACCT_CMD#" on_success_url="/app/account/account_request" error_location="rn_ErrorLocation"/>
        </form>
    </div>
</div>
