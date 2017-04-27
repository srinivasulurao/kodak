<?php /* Originating Release: February 2016 */?>
<? if($this->data['js']['readOnly']):?>
<rn:widget path="output/ProductCategoryDisplay" label="#rn:php:$this->data['attrs']['label_input']#" left_justify="true"/>
<? else:?>
<?php //echo "<pre>";
//print_r($this->data);
?>

<div id="rn_<?=$this->instanceID;?>" class="<?= $this->classList ?>">
    <rn:block id="top"/>
    <a href="javascript:void(0);" class="rn_ScreenReaderOnly" id="rn_<?=$this->instanceID?>_LinksTrigger"><?printf($this->data['attrs']['label_screen_reader_accessible_option'], $this->data['attrs']['label_input'])?>&nbsp;<span id="rn_<?=$this->instanceID;?>_TreeDescription"></span></a>
    <span class="rn_Label" id="rn_<?= $this->instanceID ?>_Label">
        <rn:block id="preLabel"/>
        <?=$this->data['attrs']['label_input']?>
        <? if($this->data['attrs']['label_input']):?>
            <span id="rn_<?=$this->instanceID;?>_RequiredLabel" class="rn_Required rn_Hidden"> <?=\RightNow\Utils\Config::getMessage(FIELD_REQUIRED_MARK_LBL);?></span><span id="rn_<?=$this->instanceID;?>_ErrorLabel" class="rn_ErrorLabel">
                <span class="rn_ScreenReaderOnly">
                    <?=\RightNow\Utils\Config::getMessage(REQUIRED_LBL);?>
                </span>
            </span>
        <? endif;?>
        <rn:block id="postLabel"/>
    </span>
    <rn:block id="preButton"/>
    <button type="button" id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['data_type'];?>_Button" class="rn_DisplayButton"><span class="rn_ScreenReaderOnly"><?=$this->data['attrs']['label_accessible_interface']?></span> <span id="rn_<?=$this->instanceID?>_Button_Visible_Text"><?=$this->data['attrs']['label_nothing_selected'];?></span></button>
    <rn:block id="postButton"/>
    <div class="rn_ProductCategoryLinks rn_Hidden" id="rn_<?=$this->instanceID;?>_Links"></div>
    <div id="rn_<?=$this->instanceID;?>_TreeContainer" class="rn_PanelContainer rn_Hidden">
        <rn:block id="preTree"/>
        <div id="rn_<?=$this->instanceID;?>_Tree" class="rn_Panel">
            <? /* Product / Category YUI TreeView is created here */?>
        </div>
    <? if ($this->data['attrs']['show_confirm_button_in_dialog']): ?>
        <rn:block id="preConfirmButton"/>
        <div id="rn_<?=$this->instanceID;?>_SelectionButtons" class="rn_SelectionButtons">
            <rn:block id="confirmButtonTop"/>
            <button type="button" id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['data_type'];?>_ConfirmButton"><?=$this->data['attrs']['label_confirm_button'];?></button>
            <button type="button" id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['data_type'];?>_CancelButton"><?=$this->data['attrs']['label_cancel_button'];?></button>
            <rn:block id="confirmButtonBottom"/>
        </div>
        <rn:block id="postConfirmButton"/>
    <? endif; ?>
        <rn:block id="postTree"/>
    </div>

    <? if($this->data['attrs']['set_button']):?>
    <rn:block id="preSetButton"/>
    <button type="button" id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['data_type'];?>_SetButton"><?=$this->data['attrs']['label_set_button']?></button>
    <rn:block id="postSetButton"/>
    <? endif;?>
    <rn:block id="bottom"/>
</div>
<? endif;?>
