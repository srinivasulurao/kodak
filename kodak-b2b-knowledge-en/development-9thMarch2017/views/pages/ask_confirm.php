<rn:meta title="#rn:msg:ASK_QUESTION_HDG#" template="kodak_b2b_template.php" clickstream="incident_confirm"/>

<div id="rn_PageTitle" class="rn_AskQuestion">
    <h1>#rn:msg:QUESTION_SUBMITTED_HDG#</h1>
</div>

<div id="rn_PageContent" class="rn_AskQuestion">
    <div class="rn_Padding">
        <p>
            #rn:msg:SUBMITTING_QUEST_REFERENCE_FOLLOW_LBL# <b>#<rn:field name="incidents.ref_no" />#rn:url_param_value:refno#</b>
        </p>
        <p>
            #rn:msg:SUPPORT_TEAM_SOON_MSG#
        </p>
        <rn:condition logged_in="true">
        <p>
            #rn:msg:UPD_QUEST_CLICK_ACCT_TAB_SEL_QUEST_MSG#
        </p>
        <rn:condition_else/>
        <p>
            #rn:msg:UPD_QUEST_ACCT_LG_CLICK_ACCT_TAB_MSG#
        </p>
        <p>
            #rn:msg:DONT_ACCT_ACCOUNT_ASST_ENTER_EMAIL_MSG#
            <a href="/app/utils/account_assistance#rn:session#">#rn:msg:ACCOUNT_ASSISTANCE_LBL#</a>
        </p>
        </rn:condition>
    </div>
</div>

