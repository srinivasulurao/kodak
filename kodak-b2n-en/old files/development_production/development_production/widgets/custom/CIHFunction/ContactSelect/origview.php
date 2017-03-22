<rn:meta controller_path="custom/CIHFunction/ContactSelect" js_path="custom/CIHFunction/ContactSelect" presentation_css="widgetCss/ContactSelect.css" compatibility_set="November '09+"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_ContactSelect">
	<? if($this->data['attrs']['label']):?>
	<label for='rn_<?=$this->instanceID;?>_Options' class="rn_Label"><?=$this->data['attrs']['label']?></label><br/>
	<?endif;?>
    <select class="mysel" name="<?=$this->data['attrs']['name']?>" id='rn_<?=$this->instanceID;?>_Options' <?=tabIndex($this->data['attrs']['tabindex'], 1);?>>
		<option value="0"><?=$this->data['attrs']['no_selection_label'] ?></option>
        <? foreach($this->data['contacts'] as $key => $value): ?>
	<option value="<?=$value['c_id'];?>" <?=($this->data['attrs']['selected_value'] == $value['c_id']) ? 'selected="selected"' : '';?> ><?=$value['last_name'].', '.$value['first_name'].' '.$value['phone']?></option>
        <? endforeach;?>
    </select>
</div>
