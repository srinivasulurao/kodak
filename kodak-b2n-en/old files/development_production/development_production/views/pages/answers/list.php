 <rn:meta title="#rn:msg:FIND_ANS_HDG#" template="newkodak_b2b_template.php" clickstream="answer_list" use_profile_defaults="true"/>
<?    
$browse_msg_base_array=load_array("csv_browse.php");
?>
<style type="text/css">
#rn_MainColumn {
  width: 100%;
}
</style>

<table style="width:920px"><tr><td valign="top" style="width:260px"> 
<h1><? echo $browse_msg_base_array['browse_by_lbl']; ?></h1>
<b><? echo $browse_msg_base_array['product_lbl']; ?></b><br />
<rn:widget path="custom/search/ProductCategorySearchFilterOnChange" filter_type="products" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" label_input="" label_nothing_selected="All Products"  search_on_select="true" linking_off="true" />
<br />
<b><? echo $browse_msg_base_array['category_lbl']; ?></b><br />
<rn:widget path="custom/search/ProductCategorySearchFilterOnChange" filter_type="categories" label_input="" label_nothing_selected="All Categories" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" search_on_select="true" linking_off="true" />
</td>
<td valign="top" style="width:660px">
<rn:widget path="knowledgebase/RssIcon2" icon_path="" />
<div id="rn_PageTitle" class="rn_AnswerList">
    <rn:condition is_spider="false">
        <div id="rn_SearchControls">
            <h1 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_CMD#</h1>
            <form method="post" action="" onsubmit="return false" >
<div style="float:right; padding: 25px 80px 0px 0px;">                            <a class="rn_SearchLink"  href="javascript:void(0);" onclick="window.open('/app/utils/help_search', '', 'scrollbars,resizable,width=720,height=700'); return false;">#rn:msg:SEARCH_TIPS_LBL#</a></div>
                <div class="rn_SearchInput">
                    <rn:widget path="search/AdvancedSearchDialog" report_page_url="/app/answers/list" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" display_categories_filter="false" display_products_filter="false"/>  
                    <rn:widget path="search/KeywordText2" label_text="" initial_focus="true" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#"/>
                </div>
                <rn:widget path="custom/search/SearchButton2OnChange" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#"/>
            </form>
                    <rn:widget path="search/DisplaySearchFilters" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" />
        </div>
    </rn:condition>
</div>
<div id="rn_PageContent" class="rn_AnswerList">
    <div class="rn_Padding">
        <h2 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_RESULTS_CMD#</h2>
        <rn:widget path="reports/ResultInfo2" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" add_params_to_url="p,c"/>
        <rn:widget path="knowledgebase/TopicWords2"/>
        <rn:widget path="reports/Multiline2" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#"/>
        <rn:widget path="reports/SearchTruncation2"  report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" />
        <rn:widget path="reports/Paginator" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#"/>
    </div>
</div>
</td></tr></table>
