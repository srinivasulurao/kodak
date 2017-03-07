<!-- <rn:meta controller_path="standard/search/AdvancedSearchDialog" js_path="standard/search/AdvancedSearchDialog" presentation_css="widgetCss/AdvancedSearchDialog.css" compatibility_set="November '09+"/> -->

<div id="rn_<?=$this->instanceID;?>" class="rn_AdvancedSearchDialog">
    <a href="javascript:void(0);" id="rn_<?=$this->instanceID;?>_TriggerLink" class="rn_AdvancedLink"><?=$this->data['attrs']['label_link'];?></a>
    <div id="rn_<?=$this->instanceID;?>_DialogContent" class="rn_DialogContent rn_Hidden">
        <div class="rn_AdvancedKeyword rn_AdvancedSubWidget">
            <rn:widget path="search/KeywordText2" label_text="#rn:msg:SEARCH_TERMS_UC_CMD#"/>
        </div>
    <? if($this->data['webSearch']):?>
        <div class="rn_AdvancedSort rn_AdvancedSubWidget">
            <rn:widget path="search/WebSearchType"/>
        </div>
    <? else:?>
        <? if($this->data['attrs']['display_products_filter']):?>
        <div class="rn_AdvancedFilter rn_AdvancedSubWidget"><rn:widget path="search/ProductCategorySearchFilter" filter_type="products"/></div>
        <? endif;?>
        <? if($this->data['attrs']['display_categories_filter']):?>
        <div class="rn_AdvancedFilter rn_AdvancedSubWidget"><rn:widget path="search/ProductCategorySearchFilter" filter_type="categories" label_input="#rn:msg:LIMIT_BY_CATEGORY_LBL#" label_nothing_selected="#rn:msg:SELECT_A_CATEGORY_LBL#"/></div>
        <? endif;?>
        <div class="rn_AdvancedFilter rn_AdvancedSubWidget">
        <rn:widget path="search/SearchTypeList"  />
        </div>
        <? if($this->data['attrs']['display_sort_filter']):?>
        <div class="rn_AdvancedSort rn_AdvancedSubWidget"><rn:widget path="search/SortList2"/></div>
        <? endif;?>
    <? endif;?>
    <? if($this->data['attrs']['search_tips_url']):?>
        <a class="rn_SearchTips" href="javascript:void(0);" onclick="window.open('<?=$this->data['attrs']['search_tips_url']?>', '', 'scrollbars,resizable,width=720,height=700'); return false;">#rn:msg:SEARCH_TIPS_LBL#</a>
    <? endif;?>
    </div>
</div>






<!--
<rn:block id='AdvancedSearchDialog-top'>

</rn:block>
-->

<!--
<rn:block id='AdvancedSearchDialog-preDialog'>

</rn:block>
-->

<!--
<rn:block id='AdvancedSearchDialog-dialogTop'>

</rn:block>
-->

<!--
<rn:block id='AdvancedSearchDialog-dialogBottom'>

</rn:block>
-->

<!--
<rn:block id='AdvancedSearchDialog-postDialog'>

</rn:block>
-->

<!--
<rn:block id='AdvancedSearchDialog-bottom'>

</rn:block>
-->

<!--
<rn:block id='KeywordText-top'>

</rn:block>
-->

<!--
<rn:block id='KeywordText-preInput'>

</rn:block>
-->

<!--
<rn:block id='KeywordText-postInput'>

</rn:block>
-->

<!--
<rn:block id='KeywordText-bottom'>

</rn:block>
-->

<!--
<rn:block id='SearchTypeList-top'>

</rn:block>
-->

<!--
<rn:block id='SearchTypeList-preSelect'>

</rn:block>
-->

<!--
<rn:block id='SearchTypeList-postSelect'>

</rn:block>
-->

<!--
<rn:block id='SearchTypeList-bottom'>

</rn:block>
-->

<!--
<rn:block id='WebSearchSort-top'>

</rn:block>
-->

<!--
<rn:block id='WebSearchSort-preSelect'>

</rn:block>
-->

<!--
<rn:block id='WebSearchSort-postSelect'>

</rn:block>
-->

<!--
<rn:block id='WebSearchSort-bottom'>

</rn:block>
-->

<!--
<rn:block id='WebSearchType-top'>

</rn:block>
-->

<!--
<rn:block id='WebSearchType-preSelect'>

</rn:block>
-->

<!--
<rn:block id='WebSearchType-postSelect'>

</rn:block>
-->

<!--
<rn:block id='WebSearchType-bottom'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-top'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-content'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-preLink'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-postLink'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-preLabel'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-postLabel'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-preButton'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-postButton'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-preTree'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-preConfirmButton'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-confirmButtonTop'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-confirmButtonBottom'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-postConfirmButton'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-postTree'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-bottom'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-top'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-content'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-preLink'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-postLink'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-preLabel'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-postLabel'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-preButton'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-postButton'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-preTree'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-preConfirmButton'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-confirmButtonTop'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-confirmButtonBottom'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-postConfirmButton'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-postTree'>

</rn:block>
-->

<!--
<rn:block id='ProductCategorySearchFilter-bottom'>

</rn:block>
-->

<!--
<rn:block id='FilterDropdown-top'>

</rn:block>
-->

<!--
<rn:block id='FilterDropdown-preSelect'>

</rn:block>
-->

<!--
<rn:block id='FilterDropdown-postSelect'>

</rn:block>
-->

<!--
<rn:block id='FilterDropdown-bottom'>

</rn:block>
-->

<!--
<rn:block id='SortList-top'>

</rn:block>
-->

<!--
<rn:block id='SortList-preHeadingSelect'>

</rn:block>
-->

<!--
<rn:block id='SortList-postHeadingSelect'>

</rn:block>
-->

<!--
<rn:block id='SortList-preDirectionSelect'>

</rn:block>
-->

<!--
<rn:block id='SortList-postDirectionSelect'>

</rn:block>
-->

<!--
<rn:block id='SortList-bottom'>

</rn:block>
-->


