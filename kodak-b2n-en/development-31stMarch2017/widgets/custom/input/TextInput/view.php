<rn:meta controller_path="custom/input/TextInput" 
    js_path="custom/input/TextInput" 
    base_css="custom/input/TextInput" 
    presentation_css="widgetCss/TextInput.css" 
    compatibility_set="November '09+"
    required_js_module="november_09,mobile_may_10"/>

<? if($this->data['readOnly']):?>
<rn:widget path="output/FieldDisplay" left_justify="true"/>
<? else:?>
<div id="rn_<?=$this->instanceID;?>" class="rn_TextInput">
<?
switch($this->data['js']['type']): case (8): case (5): ?>
    <? if($this->data['attrs']['label_input'] && !$this->data['attrs']['hide'] ):?>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" id="rn_<?=$this->instanceID;?>_Label" class="rn_Label"><?=$this->data['attrs']['label_input'];?>
    <?
if($this->data['attrs']['required']):?>
        <span class="rn_Required"> * </span><span class="rn_ScreenReaderOnly"><?=getMessage((5883))?></span>
    <? endif;?>
    <?
if($this->data['js']['hint']):?>
    <span class="rn_ScreenReaderOnly"> <?=$this->data['js']['hint']?></span>
    <? endif;?>
    </label>
    <?
endif;?>

<? 
if($this->data['attrs']['name']=='incidents.c$ek_k_number' ||
   $this->data['attrs']['name']=='incidents.c$ek_serial_number' )
	{
		$readonly_flag='readonly="readonly"';
	}
	else {
			$readonly_flag='';
	}
	
if($this->data['attrs']['hide'])
{
	$class = 'rn_Hidden';
	$show_span_open = '<span class="rn_Hidden">';
	$show_span_close = '</span>';
}
else {
	$class = 'rn_Text';	
	$show_span_open = '';
	$show_span_close = '';
}

?>

<?=$show_span_open?>
<input type="text" id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" name="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" class="<?=$class?>" <?=tabIndex($this->data['attrs']['tabindex'],
1);?> <?
if($this->data['maxLength']): echo('maxlength="' . $this->data['maxLength'] . '"');
endif;?> value="<?=$this->data['value'];?>" <?=$readonly_flag?>/>
<?=$show_span_close?>

<?
break;
case (7): ?>
    <? if($this->data['attrs']['label_input']):?>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" id="rn_<?=$this->instanceID;?>_Label" class="rn_Label"><?=$this->data['attrs']['label_input'];?>
    <?
if($this->data['attrs']['required']):?>
        <span class="rn_Required"> * </span><span class="rn_ScreenReaderOnly"><?=getMessage((5883))?></span>
    <? endif;?>
    <?
if($this->data['js']['hint']):?>
    <span class="rn_ScreenReaderOnly"> <?=$this->data['js']['hint']?></span>
    <? endif;?>
    </label>
    <?
endif;?>
    <input type="password" id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" class="rn_Password" <?=tabIndex($this->data['attrs']['tabindex'],
1);?> <?
if($this->data['maxLength']): echo('maxlength="' . $this->data['maxLength'] . '"');
endif;?>/>
<?
break;
default: ?>
    <? if($this->data['attrs']['label_input']):?>
    <label for="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" id="rn_<?=$this->instanceID;?>_Label" class="rn_Label"><?=$this->data['attrs']['label_input'];?>
    <?
if($this->data['attrs']['required']):?>
        <span class="rn_Required"> * </span><span class="rn_ScreenReaderOnly"><?=getMessage((5883))?></span>
    <? endif;?>
    <?
if($this->data['js']['hint']):?>
    <span class="rn_ScreenReaderOnly"> <?=$this->data['js']['hint']?></span>
    <? endif;?>
    </label>
    <?
endif;?>
    <textarea id="rn_<?=$this->instanceID;?>_<?=$this->data['js']['name'];?>" class="rn_TextArea" rows="7" cols="60" <?=tabIndex($this->data['attrs']['tabindex'],
1);?>><?=$this->data['value'];?></textarea>
<?
break;
endswitch;
?>
</div>
<? endif;?>
