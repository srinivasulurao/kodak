<rn:meta title="#rn:msg:SHP_TITLE_HDG#" template="kodak_b2b_template.php" clickstream="home"/>
<div id="rn_PageTitle" class="rn_Home">
<?    
$home_msg_base_array=load_array("csv_home.php");
?>
<!--
<rn:condition is_spider="false">
        <div id="rn_SearchControls">
		<div class="quicksearch">#rn:msg:QUICK_SEARCH_CMD#</div>
            <h1 class="rn_ScreenReaderOnly">#rn:msg:SEARCH_CMD#</h1>
            <form method="post" action="" onsubmit="return false" >
<div style="float:right; padding: 25px 80px 0px 0px;">                            <a class="rn_SearchLink"  href="javascript:void(0);" onclick="window.open('/app/utils/help_search', '', 'scrollbars,resizable,width=720,height=700'); return false;">#rn:msg:SEARCH_TIPS_LBL#</a></div>
                <div class="rn_SearchInput">
     				<rn:widget path="custom/search/CustomAdvancedSearchDialog" report_page_url="/app/answers/list" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#" display_categories_filter="false" display_products_filter="false"/>  
                <rn:widget path="search/KeywordText" label_text="" initial_focus="true" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#"/> 
                </div>
                <rn:widget path="search/SearchButton" report_page_url="/app/answers/list" report_id="#rn:php:$templ_msg_base_array['portal_report_id']#"/>
            </form>
        </div>
</rn:condition>
-->
</div>

<div id="rn_PageContent" class="rn_Home">
<!-- <div style="height:22px;"></div> -->
<style>
.rn_Paginator {
	padding:1px 0;
    border-color:#CBCBCB;
    border-style:solid none none none;
    border-width:1px medium medium medium;
}
.rn_Grid2 {
    border-color:#CBCBCB;
    border-style:solid none none none;
    border-width:1px medium medium medium;
	margin-bottom:0px;
	padding-bottom:0px;
	line-height:1.75em;
}
</style>
<!--
<style>
#rn_SearchControls {
    border: 3px solid #CCCCCC;
    width: 97.0%;
}
</style>
-->
<div style="font-size: 16px;    line-height: 20px; padding-bottom:15px;"><? echo $templ_msg_base_array['lbl_home_intro']; ?></div>
                           <div class="rn_Module">
                          <h2>&nbsp;<? echo $home_msg_base_array['whatsnew']; ?></h2>
                          <div style="margin-left:3px;margin-top:6px;float:left;margin-bottom:10px;">&nbsp;&nbsp;<? echo $home_msg_base_array['filterlistby']; ?></div><div style="float:left;margin-bottom:2px;"><rn:widget path="search/ProductCategorySearchFilter" search_on_select="true" report_id="#rn:php:$home_msg_base_array['whatsnewreport_id']#" label_input="" /></div>
<rn:widget path="search/ProductCategorySearchFilter" report_id="#rn:php:$home_msg_base_array['whatsnewreport_id']#" label_input="" filter_type="categories" search_on_select="true" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#"  /> 
<div class="rn_ClearBoth">
                          <rn:widget path="reports/Grid" report_id="#rn:php:$home_msg_base_array['whatsnewreport_id']#" per_page="10"/>
                          <div align="center"><rn:widget path="reports/Paginator" report_id="#rn:php:$home_msg_base_array['whatsnewreport_id']#" per_page="10" /></div>
                        </div>
</div>
<!--<div style="height:10px;"></div>
    <div class="rn_Module">
        <h2>&nbsp;#rn:msg:MOST_POPULAR_ANSWERS_LBL#</h2>
<div style="margin-left:3px;margin-top:6px;float:left;margin-bottom:10px;">&nbsp;&nbsp;<? echo $home_msg_base_array['filterlistby']; ?></div><div style="float:left;margin-bottom:2px;"><rn:widget path="search/ProductCategorySearchFilter" search_on_select="true" report_id="#rn:php:$home_msg_base_array['mostpopularreport_id']#" label_input="" /></div>
<rn:widget path="search/ProductCategorySearchFilter" search_on_select="true" report_id="#rn:php:$home_msg_base_array['mostpopularreport_id']#" label_input="" filter_type="categories" search_on_select="true" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#"  /> <div class="rn_ClearBoth">

        <rn:widget path="reports/Grid" report_id="#rn:php:$home_msg_base_array['mostpopularreport_id']#" per_page="5"/>
        <div align="center"><rn:widget path="reports/Paginator" report_id="#rn:php:$home_msg_base_array['mostpopularreport_id']#" per_page="5" /></div>
    </div></div>
-->                   
</div>



