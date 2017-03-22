 <rn:meta title="#rn:msg:FIND_ANS_HDG#" template="kodak_b2b_template.php" clickstream="answer_list" use_profile_defaults="true"/>
<?    
$browse_msg_base_array=load_array("csv_browse.php"); 
$ci=get_instance();
$contact=$ci->model('custom/profile_manager_model')->getContact();
$prod_id=(getUrlParm('p'))?getUrlParm('p'):$contact->CustomFields->c->prod_id;
$cat_id=(getUrlParm('c'))?getUrlParm('c'):$contact->CustomFields->c->cat_id;
$search_text=(getUrlParm('kw'))?getUrlParm('kw'):$contact->CustomFields->c->search_text;
$lines_per_page=(int)$contact->CustomFields->c->lines_per_page;  

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// exit;
?>
<style type="text/css">
#rn_MainColumn {
  width: 100%;
}
</style>

    <rn:container source_id="KFSearch,SocialSearch"   history_source_id="KFSearch,SocialSearch">


<table style="width:920px"><tr><td valign="top" style="width:260px"> 
<h1><? echo $browse_msg_base_array['browse_by_lbl']; ?></h1>

<b><? echo $browse_msg_base_array['product_lbl']; ?></b><br />
<rn:widget path="custom/search/ProductCategorySearchFilterOnChange" linking_off="true" default_value='#rn:php:$prod_id#' filter_type="products" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" label_input="" label_nothing_selected="All Products" search_on_select="true" linking_off="true" /> 
<br />
<b><div style='padding-top:10px;top:15px;position:relative'><? echo $browse_msg_base_array['category_lbl']; ?></div></b><br />
<rn:widget path="custom/search/ProductCategorySearchFilterOnChange" linking_off="true" default_value='#rn:php:$cat_id#' filter_type="categories" label_input="" label_nothing_selected="All Categories" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" search_on_select="true" linking_off="true" />
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
                    <rn:widget path="custom/search/CustomAdvancedSearchDialog" report_page_url="/app/answers/list/page/2" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" display_categories_filter="false" display_products_filter="false" additional_filters="search_nl,search_fnl,search_ex,search_cpx" />  
                    <rn:widget default_value="#rn:php:$search_text#"  path="custom/search/KeywordTextModified" label_text="" initial_focus="true" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#"/>
                </div>
                <rn:widget path="custom/search/SearchButton2OnChange" initial_focus="true" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#"/>

            </form>
                    <rn:widget path="search/DisplaySearchFilters" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" />
        </div>
    </rn:condition>
</div>
<div id="rn_PageContent" class="rn_AnswerList">
    <div class="rn_Padding">
        <h2 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_RESULTS_CMD#</h2>
        <rn:widget path="reports/ResultInfo" per_page="#rn:php:$lines_per_page#" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#"/>
        <rn:widget path="knowledgebase/TopicWords2" per_page="#rn:php:$lines_per_page#" />
        <rn:widget path="reports/Multiline" per_page="#rn:php:$lines_per_page#" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" />  <!-- stupid comment to fix problem with some content like on page 39262  -->
        <!--<rn:widget path="reports/SearchTruncation2"  report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" />     -->
        <div class='pagination'><rn:widget path="reports/Paginator" per_page="#rn:php:$lines_per_page#" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#"/></div>
		
    </div>
</div>
</td></tr></table>
 


     </rn:container>

<script>
        var original_url=window.location.href;
        ph_url=original_url.split("list");
        window.history.pushState("","Find Answers",ph_url[0]+"list");
</script> 
