<rn:meta title="#rn:msg:SHP_TITLE_HDG#" template="standard.php" clickstream="home"/>
<div id="rn_PageTitle" class="rn_Home">
    <div id="rn_SearchControls">
        <h1 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_CMD#</h1>
        <form onsubmit="return false;">
            <rn:container report_id="176">
            <div class="rn_SearchInput">
                <rn:widget path="search/AdvancedSearchDialog" report_page_url="/app/answers/list"/>
                <rn:widget path="search/KeywordText" label_text="#rn:msg:FIND_THE_ANSWER_TO_YOUR_QUESTION_CMD#" initial_focus="true"/>
            </div>
            <rn:widget path="search/SearchButton" report_page_url="/app/answers/list"/>
            </rn:container>
        </form>
    </div>
</div>
<div id="rn_PageContent" class="rn_Home">
    <rn:widget path="search/ProductCategoryList" data_type="categories" label_title="#rn:msg:FEATURED_SUPPORT_CATEGORIES_LBL#"/>
    <br/><br/>
    <div class="rn_Module">
        <h2>#rn:msg:MOST_POPULAR_ANSWERS_LBL#</h2>
        <rn:widget path="reports/TopAnswers"/>
        <a class="rn_AnswersLink" href="/app/answers/list#rn:session#">#rn:msg:SEE_ALL_POPULAR_ANSWERS_UC_LBL#</a>
    </div>
</div>