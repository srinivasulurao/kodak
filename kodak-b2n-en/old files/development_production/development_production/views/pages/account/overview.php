<rn:meta title="#rn:msg:ACCOUNT_OVERVIEW_LBL#" template="newkodak_b2b_template.php" login_required="true" />

<div id="rn_PageTitle" class="rn_Account">
    <h1>#rn:msg:ACCOUNT_OVERVIEW_LBL#</h1>
</div>
<div id="rn_PageContent">
    <div class="rn_Overview">
            <h2><a class="rn_Profile" href="/app/account/profile#rn:session#">#rn:msg:SETTINGS_LBL#</a></h2>
            <div class="rn_Profile">
                <a href="/app/account/profile#rn:session#">#rn:msg:UPDATE_YOUR_ACCOUNT_SETTINGS_CMD#</a><br/>
<!--                <a href="/app/account/change_password#rn:session#">#rn:msg:CHANGE_YOUR_PASSWORD_CMD#</a>   
Probably need link to Change Password from Partner Site
<a href="https://bizidqa.kodak.com/changePassword.html?success=https%3a%2f%2fkodak-b2b-en--opid.custhelp.com/app/account/overview&noLogout=true">#rn:msg:CHANGE_YOUR_PASSWORD_CMD#</a>
-->
            </div>
            <h2><a class="rn_Notifs" href="/app/account/notif/list#rn:session#">#rn:msg:NOTIFICATIONS_HDG#</a></h2>
            <div class="rn_Notifs">
                <rn:widget path="reports/Grid2" report_id="177" per_page="4" label_caption="#rn:msg:YOUR_RECENT_ANSWER_NOTIFICATIONS_LBL#"/>
                <a href="/app/account/notif/list#rn:session#">#rn:msg:PRODUCT_CATEGORY_ANS_NOTIFICATIONS_LBL#</a>
            </div>
    </div>
</div>


