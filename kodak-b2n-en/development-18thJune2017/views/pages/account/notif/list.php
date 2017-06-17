<rn:meta title="#rn:msg:NOTIFICATIONS_HDG#" template="newkodak_b2b_template.php" login_required="true"/>
<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:NOTIFICATIONS_HDG#</h1>
</div>
<div id="rn_PageContent">
    <div class="rn_Padding">
        <h2>#rn:msg:ANSWER_LBL# #rn:msg:NOTIFICATIONS_HDG#</h2>
        <rn:widget path="notifications/AnswerNotificationManager" />
        <h2>#rn:msg:PRODUCT_LBL#/#rn:msg:CATEGORY_LBL# #rn:msg:NOTIFICATIONS_HDG#</h2>
        <rn:widget path="notifications/ProdCatNotificationManager" />
		
        <?php
$ci=get_instance();
$contact=$ci->model('custom/profile_manager_model')->getContact();
$prod_id=$contact->CustomFields->c->prod_id;
$cat_id=$contact->CustomFields->c->cat_id;
?>

        <form id="rn_CreateAccount" method="post" action="/cc/CustomProfileManager/saveProfile" onsubmit="return false;">
            <div id="rn_ErrorLocation"></div>
		            <h2>Search Preferences</h2>
            <fieldset>
            <legend>Search Preferences</legend>
<!-- <rn:widget path="input/ProductCategoryInput" table="contacts" data_type="products" label="#rn:msg:PROD_LBL#" />

<rn:widget path="input/ProductCategoryInput" table="contacts" data_type="categories" label_input="#rn:msg:CATEGORY_LBL#" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#"/>

<rn:widget path="input/FormInput" name="contacts.search_text" />

<rn:widget path="input/FormInput" name="contacts.search_type" />

<rn:widget path="input/FormInput" name="contacts.lines_per_page" /> -->

<rn:widget path="input/ProductCategoryInput" linking_off="true" default_value="#rn:php:$prod_id#" name="Incident.Product"  label="#rn:msg:PROD_LBL#" />

<rn:widget path="input/ProductCategoryInput" linking_off="true" default_value="#rn:php:$cat_id#" name="Incident.Category"   label_input="#rn:msg:CATEGORY_LBL#" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#"/>

<rn:widget path="input/FormInput" name="contacts.c$search_text" table="contacts" />

<rn:widget path="input/FormInput" name="contacts.c$search_type" table="contacts" />

<rn:widget path="input/FormInput" name="contacts.c$lines_per_page" />

			</fieldset>

           
            <rn:widget path="input/FormSubmit" label_button="#rn:msg:SAVE_CHANGE_CMD#" label_confirm_dialog = "Changes Saved Successfully" on_success_url='/app/account/notif/list' error_location="rn_ErrorLocation" loading_icon_path="/euf/assets/themes/Kodak/images/indicator.gif" />
        </form>
		
    </div>
</div>

<style>
.rn_ProductCategoryInput button.rn_DisplayButton{
	width:270px;
}

#rnDialog1_c {
    width: 585px !important;
    left: 30% !important;
}

.rn_ProdCatNotificationManager_Dialog .rn_SelectionWidget:nth-child(2){
	float:right;
}
</style>
