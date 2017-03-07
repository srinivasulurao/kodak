 <rn:meta title="#rn:msg:FIND_ANS_HDG#" template="kodak_b2b_template.php" clickstream="answer_list" use_profile_defaults="true"/>
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
<rn:widget path="custom/search/ProductCategorySearchFilterOnChange" filter_type="products" report_id="176" label_input="" label_nothing_selected="All Products"  />
<br />
<b><? echo $browse_msg_base_array['category_lbl']; ?></b><br />
<rn:widget path="custom/search/ProductCategorySearchFilterOnChange" filter_type="categories" label_input="" label_nothing_selected="All Categories" report_id="176"  />
</td>
<td valign="top" style="width:660px">
<rn:widget path="knowledgebase/RssIcon2" icon_path="" />
<div id="rn_PageTitle" class="rn_AnswerList">
    <rn:condition is_spider="false">
        <div id="rn_SearchControls">
            <h1 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_CMD#</h1>
            <form method="post" action="" onsubmit="return false" >
                <div class="rn_SearchInput">
                    <rn:widget path="custom/search/CustomAdvancedSearchDialog" report_page_url="/app/answers/list" report_id="176" display_categories_filter="false" display_products_filter="false"/>  
                    <rn:widget path="search/KeywordText2" initial_focus="true" report_id="176"/>
                </div>
                <rn:widget path="custom/search/SearchButton2OnChange" report_id="176"/>
            </form>
                    <rn:widget path="search/DisplaySearchFilters" report_id="176" />
        </div>
    </rn:condition>
</div>
<div id="rn_PageContent" class="rn_AnswerList">
    <div class="rn_Padding">
        <h2 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_RESULTS_CMD#</h2>
        <rn:widget path="reports/ResultInfo2" report_id="176" add_params_to_url="p,c"/>
        <rn:widget path="knowledgebase/TopicWords2"/>
        <rn:widget path="reports/Multiline2" report_id="176"/>
        <rn:widget path="reports/Paginator" report_id="176"/>
    </div>
</div>
</td></tr></table>
