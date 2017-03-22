<rn:meta controller_path="custom/CIHFunction/IncidentThreadUpdate" js_path="custom/CIHFunction/IncidentThreadUpdate" base_css="custom/CIHFunction/IncidentThreadUpdate" presentation_css="widgetCss/IncidentThreadUpdate.css"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_TextInput">
    <? if($this->data['attrs']['label_input']):?>
	<label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" id="rn_<?=$this->instanceID;?>_Label" class="rn_Label"><?=$this->data['attrs']['label_input'];?>
    <? if($this->data['attrs']['required']):?>
	<span class="rn_Required"> <?=getMessage(FIELD_REQUIRED_MARK_LBL);?> </span><span class="rn_ScreenReaderOnly"><?=getMessage(REQUIRED_LBL)?></span>
    <? endif;?>
    <? if($this->data['js']['hint'] && !$this->data['attrs']['hide_hint']):?>
	<span class="rn_ScreenReaderOnly"> <?=$this->data['js']['hint']?></span>
		<? endif;?>
	</label>
    <? endif;?>
    <textarea id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" class="rn_TextArea" rows="7" cols="60" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> <? if($this->data['maxLength']): echo('maxlength="' . $this->data['maxLength'] . '"'); endif;?>><?=$this->data['value'];?></textarea>
</div>
