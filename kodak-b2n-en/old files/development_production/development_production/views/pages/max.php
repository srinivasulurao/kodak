<rn:meta title="#rn:msg:SHP_TITLE_HDG#" template="kodak_b2b_template.php" clickstream="home"/>
<?
$home_msg_base_array=load_array("csv_home.php");
?>

<div id="rn_PageTitle" class="rn_Home">
    <rn:widget path="custom/search/ProductCategorySearchFilter1" search_on_select="true" report_id="#rn:php:$home_msg_base_array['whatsnewreport_id']#" label_input="" />
	<rn:widget path="custom/search/ProductCategorySearchFilter1" report_id="#rn:php:$home_msg_base_array['whatsnewreport_id']#" label_input="" filter_type="categories" search_on_select="true" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#"  /> 
	<div class="rn_ClearBoth">
		<rn:widget path="reports/Grid2" report_id="#rn:php:$home_msg_base_array['whatsnewreport_id']#" per_page="5"/>
		<div align="center">
			<rn:widget path="reports/Paginator" report_id="#rn:php:$home_msg_base_array['whatsnewreport_id']#" per_page="5" />
		</div>
	</div>
</div>
<div style="height:10px;"></div>
<div class="rn_Module">
	<rn:widget path="standard/search/ProductCategorySearchFilter" search_on_select="true" report_id="#rn:php:$home_msg_base_array['mostpopularreport_id']#" label_input="" />
	<rn:widget path="standard/search/ProductCategorySearchFilter" search_on_select="true" report_id="#rn:php:$home_msg_base_array['mostpopularreport_id']#" label_input="" filter_type="categories" search_on_select="true" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#"  />
	<div class="rn_ClearBoth">
		<rn:widget path="reports/Grid2" report_id="#rn:php:$home_msg_base_array['mostpopularreport_id']#" per_page="5"/>
		<div align="center">
			<rn:widget path="reports/Paginator" report_id="#rn:php:$home_msg_base_array['mostpopularreport_id']#" per_page="5" />
		</div>
	</div>
</div>
</div>
