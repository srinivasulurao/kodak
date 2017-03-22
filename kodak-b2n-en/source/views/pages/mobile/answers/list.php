<rn:meta title="#rn:msg:FIND_ANS_HDG#" template="mobile.php" clickstream="answer_list"/>
<rn:container report_id="176">
<section id="rn_PageTitle" class="rn_AnswerList">
    <div id="rn_SearchControls">
        <h1>#rn:msg:SEARCH_RESULTS_CMD#</h1>
        <form onsubmit="return false;">
            <rn:widget path="search/KeywordText" label_text="<span class='rn_ScreenReaderOnly'>#rn:msg:FIND_THE_ANSWER_TO_YOUR_QUESTION_CMD#</span>"/>
            <rn:widget path="search/SearchButton" icon_path="images/icons/search.png"/>
        </form>
        <rn:widget path="navigation/Accordion" toggle="rn_Advanced"/>
        <div class="rn_Padding">
            <a class="rn_AlignRight" href="javascript:void(0);" id="rn_Advanced">#rn:msg:PLUS_SEARCH_OPTIONS_LBL#</a>
            <div>
                <rn:widget path="search/MobileProductCategorySearchFilter" filter_type="products"/>
                <rn:widget path="search/MobileProductCategorySearchFilter" filter_type="categories"/>
            </div>
        </div>
    </div>
</section>
<section id="rn_PageContent" class="rn_AnswerList">
    <div class="rn_Padding">
        <rn:widget path="reports/ResultInfo"/>
        <rn:widget path="reports/MobileMultiline"/>
        <rn:widget path="reports/Paginator"/>
    </div>
</section>
</rn:container>
