<rn:meta title="#rn:msg:SUPPORT_HISTORY_LBL#" template="kodak_b2b_template.php" clickstream="incident_list" login_required="true" />
<rn:container report_id="159">
<div class="rn_Hero">
    <div class="rn_HeroInner">
        <div id="rn_SearchControls">
            <h1 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_CMD#</h1>
            <form onsubmit="return false;" class="translucent">
                <div class="rn_SearchInput">
                    <rn:widget path="search/KeywordText" label_text="#rn:msg:SEARCH_YOUR_SUPPORT_HISTORY_CMD#" label_placeholder="#rn:msg:SEARCH_YOUR_SUPPORT_HISTORY_CMD#" initial_focus="true"/>
                </div>
                <rn:widget path="search/SearchButton"/>
            </form>
            
        </div>
    </div>
</div>
<div class="rn_PageContent rn_Container">
    <h2 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_RESULTS_CMD#</h2>
    <rn:widget path="reports/ResultInfo"/>
    <rn:widget path="reports/Grid" label_caption="<span class='rn_ScreenReaderOnly'>#rn:msg:SEARCH_YOUR_SUPPORT_HISTORY_CMD#</span>"/>
    <rn:widget path="reports/Paginator"/>
</div>
</rn:container>
