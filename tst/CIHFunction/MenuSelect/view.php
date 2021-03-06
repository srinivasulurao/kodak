<rn:meta controller_path="custom/CIHFunction/MenuSelect" js_path="custom/CIHFunction/MenuSelect" presentation_css="widgetCss/MenuSelect.css" compatibility_set="November '09+"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_webSearchType">
    <? if($this->data['attrs']['label']):?>
	<span for='rn_<?=$this->instanceID;?>_Options' class="rn_Label"><?=$this->data['attrs']['label']?></span><br/>
	<?endif;?>
    <select class="rn_MenuSelect" name="<?=$this->data['attrs']['name']?>" id='rn_<?=$this->instanceID;?>_Options' <?=tabIndex($this->data['attrs']['tabindex'], 1);?>>
	<option value="">--</option>
        <? foreach($this->data['menu'] as $key => $value): ?>
	<option value="<?=$value['ID'];?>" <?=($this->data['attrs']['selected_value'] == $value['ID']) ? 'selected="selected"' : '';?> ><?=$value['LookupName']?></option>
        <? endforeach;?>
    </select>
</div>
