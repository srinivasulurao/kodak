<rn:meta title="#rn:msg:SHP_TITLE_HDG#" template="mobile.php" clickstream="home" login_required="true"/>

<section id="rn_PageContent" class="rn_Home">
    <div class="rn_Module">
        <rn:widget path="navigation/Accordion" toggle="rn_AccordTrigger"/>
        <h2 id="rn_AccordTrigger" class="rn_Expanded">#rn:msg:MOST_POPULAR_ANSWERS_LBL#<span class="rn_Expand"></span></h2>
        <div>
            <rn:widget path="reports/MobileMultiline" report_id="162" per_page="6"/>
            <a class="rn_AnswersLink" href="/app/answers/list#rn:session#">#rn:msg:SEE_ALL_POPULAR_ANSWERS_UC_LBL#</a>
        </div>
    </div>
    <div class="rn_Module">
        <rn:widget path="navigation/Accordion" toggle="rn_ListTrigger"/>
        <h2 id="rn_ListTrigger" class="rn_Expanded">#rn:msg:FEATURED_SUPPORT_CATEGORIES_LBL#<span class="rn_Expand"></span></h2>
        <div>
            <rn:widget path="search/MobileProductCategoryList" data_type="categories" levels="1" label_title=""/>
        </div>
    </div>
</section>
