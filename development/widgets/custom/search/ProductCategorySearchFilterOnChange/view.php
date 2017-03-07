<rn:meta controller_path="custom/search/ProductCategorySearchFilterOnChange" js_path="custom/search/ProductCategorySearchFilterOnChange" base_css="custom/search/ProductCategorySearchFilterOnChange" presentation_css="widgetCss/ProductCategorySearchFilter.css" compatibility_set="November '09+"/>

<style type="text/css">
#rn_<?=$this->instanceID;?>_Tree {
    max-height:none;
}
#rn_<?=$this->instanceID;?>_Tree_c {
    position:relative;
}
#rn_<?=$this->instanceID;?>_Tree_c div.underlay{
    background:#ffffff;
}
</style>

<? $this->addJavaScriptInclude(getYUICodePath('treeview/treeview-min.js'));?>
<? $this->addStylesheet(getYUICodePath('treeview/assets/treeview-menu.css'));?>

<div id="rn_<?=$this->instanceID;?>" class="rn_ProductCategorySearchFilter">
    <button style="display: none;" type="button" id="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['filter_type'];?>_Button" class="rn_DisplayButton" <?=tabIndex($this->data['attrs']['tabindex'], 1);?>><span id="rn_<?=$this->instanceID?>_ButtonVisibleText"><?=$this->data['attrs']['label_nothing_selected'];?></span><span class="rn_ScreenReaderOnly"> &nbsp;- <?=getMessage(SORRY_CONTROL_ACC_PLEASE_LINK_MSG)?></span></button>
    <div class="rn_Hidden" id="rn_<?=$this->instanceID;?>_Links"></div>
    <div id="rn_<?=$this->instanceID;?>_Tree" class="rn_Panel rn_Hidden"><? /**Product / Category Tree goes here */?></div>
</div>
