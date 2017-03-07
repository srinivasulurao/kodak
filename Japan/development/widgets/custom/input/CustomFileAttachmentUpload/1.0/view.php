<!--
<rn:block id='FileAttachmentUpload-top'>

</rn:block>
-->

<!--
<rn:block id='FileAttachmentUpload-preFileInputLabel'>

</rn:block>
-->

<!--
<rn:block id='FileAttachmentUpload-postFileInputLabel'>

</rn:block>
-->

<!--
<rn:block id='FileAttachmentUpload-preFileInput'>

</rn:block>
-->

<!--
<rn:block id='FileAttachmentUpload-postFileInput'>

</rn:block>
-->

<!--
<rn:block id='FileAttachmentUpload-preStatus'>

</rn:block>
-->

<!--
<rn:block id='FileAttachmentUpload-postStatus'>

</rn:block>
-->

<!--
<rn:block id='FileAttachmentUpload-bottom'>

</rn:block>
-->

<?php /* Originating Release: February 2012 */ ?>


<div id="rn_<?=$this->instanceID;?>" class="rn_FileAttachmentUpload2">
    <label for="rn_<?=$this->instanceID;?>_FileInput" id="rn_<?=$this->instanceID;?>_Label"><?=$this->data['attrs']['label_input'];?>
    <? if($this->data['attrs']['min_required_attachments'] > 0):?>
        <span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?> </span><span class="rn_ScreenReaderOnly"><?=getMessage(REQUIRED_LBL)?></span>
    <? endif;?>
    </label>
    <input name="file" id="rn_<?=$this->instanceID;?>_FileInput" type="file" size="35"/>
    <? if($this->data['attrs']['loading_icon_path']):?>
    <img id="rn_<?=$this->instanceID;?>_LoadingIcon" class="rn_Hidden" alt="" src="<?=$this->data['attrs']['loading_icon_path'];?>" />
    <? endif;?>
    <span id="rn_<?=$this->instanceID;?>_StatusMessage"></span>
</div>

?>