<rn:meta title="#rn:msg:FIND_ANS_HDG#" template="standard.php" clickstream="answer_list"/>

<rn:widget path="knowledgebase/RssIcon"/>
<rn:container report_id="176">
<div id="rn_PageTitle" class="rn_AnswerList">
    <div id="rn_SearchControls">
        <h1 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_CMD#</h1>
        <form onsubmit="return false;">
            <div class="rn_SearchInput">
                <rn:widget path="search/AdvancedSearchDialog"/>
                <rn:widget path="search/KeywordText" label_text="#rn:msg:FIND_THE_ANSWER_TO_YOUR_QUESTION_CMD#" initial_focus="true"/>
            </div>
            <rn:widget path="search/SearchButton"/>
        </form>
        <rn:widget path="search/DisplaySearchFilters"/>
    </div>
</div>
<div id="rn_PageContent" class="rn_AnswerList">
    <div class="rn_Padding">
        <h2 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_RESULTS_CMD#</h2>
        <rn:widget path="reports/ResultInfo"/>
        <rn:widget path="knowledgebase/TopicWords"/>
        <rn:widget path="reports/Multiline"/>
        <rn:widget path="reports/Paginator"/>
    </div>
</div>
</rn:container>
