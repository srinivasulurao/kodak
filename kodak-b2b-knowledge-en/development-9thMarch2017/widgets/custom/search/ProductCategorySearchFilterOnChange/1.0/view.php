



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

