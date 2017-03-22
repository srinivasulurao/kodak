<rn:meta title="#rn:msg:SUPPORT_HISTORY_LBL#" template="standard.php" clickstream="incident_list" login_required="true" force_https="true" />
<rn:container report_id="196">
<div id="rn_PageTitle" class="rn_QuestionList">
    <div id="rn_SearchControls">
        <h1 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_CMD#</h1>
        <form onsubmit="return false;">
            <div class="rn_SearchInput">
                <rn:widget path="search/AdvancedSearchDialog"/>
                <rn:widget path="search/KeywordText" label_text="#rn:msg:SEARCH_YOUR_SUPPORT_HISTORY_CMD#" initial_focus="true"/>
            </div>
            <rn:widget path="search/SearchButton"/>
        </form>
        <rn:widget path="search/DisplaySearchFilters"/>
    </div>
</div>
<div id="rn_PageContent" class="rn_QuestionList">
    <div class="rn_Padding">
        <h2 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_RESULTS_CMD#</h2>
        <rn:widget path="reports/ResultInfo"/>
        <rn:widget path="reports/Grid" label_caption="<span class='rn_ScreenReaderOnly'>#rn:msg:SEARCH_YOUR_SUPPORT_HISTORY_CMD#</span>"/>
        <rn:widget path="reports/Paginator"/>
    </div>
</div>
</rn:container>