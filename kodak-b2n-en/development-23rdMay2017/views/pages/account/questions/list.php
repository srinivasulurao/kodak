<rn:meta title="#rn:msg:SUPPORT_HISTORY_LBL#" template="kodak_b2b_template.php" clickstream="incident_list" login_required="true" />
<div id="rn_PageTitle" class="rn_QuestionList">
    <div id="rn_SearchControls">
            <h1 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_CMD#</h1>
        <form method="post" action="" onsubmit="return false" >
        <fieldset>
                <div class="rn_SearchInput">
                    <rn:widget path="custom/search/AdvancedSearchDialog" report_id="100568"/>
                    <rn:widget path="search/KeywordText2" label_text="#rn:msg:SEARCH_YOUR_SUPPORT_HISTORY_CMD#" report_id="100568" initial_focus="true"/>
                    
                </div>
                <rn:widget path="search/SearchButton2" report_id="100568"/>
                <p>
                <rn:widget path="search/OrgList" search_on_select="true" display_type="2" report_id="100568"/>
        </fieldset>
        </form>
        <rn:widget path="search/DisplaySearchFilters" report_id="100568"/>
    </div>
</div>
<div id="rn_PageContent" class="rn_QuestionList">
    <div class="rn_Padding">
        <h2 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_RESULTS_CMD#</h2>
        <rn:widget path="reports/ResultInfo2" report_id="100568" add_params_to_url="p,c"/>
        <rn:widget path="reports/Grid2" label_caption="#rn:msg:SUPPORT_HISTORY_LBL#" report_id="100568"/>
        <rn:widget path="reports/Paginator" report_id="100568"/>
    </div>
</div>
