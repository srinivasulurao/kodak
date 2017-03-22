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
		
        <form id="rn_CreateAccount" method="post" action="" onsubmit="return false;">
            <div id="rn_ErrorLocation"></div>
		            <h2>Search Preferences</h2>
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
		
    </div>
</div>
