<div id="rn_<?=$this->instanceID;?>" class="rn_webSearchType">
    <? if($this->data['attrs']['countrylabel']):?>
	<span for='rn_<?=$this->instanceID;?>_Options' class="rn_Label"><?=$this->data['attrs']['countrylabel']?></span>
	<?endif;?>
    <select name='rn_<?=$this->instanceID;?>_CountryParam' id='rn_<?=$this->instanceID;?>_CountryParam' <?=tabIndex($this->data['attrs']['tabindex'], 1);?>>
	<option value="">--</option>
        <? foreach($this->data['menu'] as $key => $value): ?>
	<option value="<?=$value['ID'];?>" <?=($this->data['attrs']['selected_value'] == $value['ID']) ? 'selected="selected"' : '';?> ><?=$value['LookupName']?></option>
        <? endforeach;?>
    </select>
</div>
