<div id="rn_<?=$this->instanceID;?>" class="rn_ListFailedOrgContacts">
    <? if($this->data['attrs']['label']):?>
	<span for='rn_<?=$this->instanceID;?>_Options' class="rn_Label"><?=$this->data['attrs']['label']?></span><br/>
	<?endif;?>
    <select id='rn_<?=$this->instanceID;?>_Options' <?=tabIndex($this->data['attrs']['tabindex'], 1);?> class="rn_ListFailedOrgContacts" name="rn_ListFailedOrgContacts">
		<option>--</option>
        <? foreach($this->data['contacts'] as $key => $value): ?>
	<option value="<?=$value['c_id'];?>" <?=($this->data['attrs']['selected_value'] == $value['c_id']) ? 'selected="selected"' : '';?> ><?=$value['last_name'].', '.$value['first_name']?></option>
        <? endforeach;?>
    </select>
</div>
