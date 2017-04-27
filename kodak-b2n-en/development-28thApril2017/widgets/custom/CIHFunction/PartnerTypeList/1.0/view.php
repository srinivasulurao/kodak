<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?> rn_webSearchType">
	<? if($this->data['attrs']['label']):?>
		<span for='rn_<?=$this->instanceID;?>_Options' class="rn_Label"><?=$this->data['attrs']['label']?></span><br/>
		<?endif;?>
	    <select name="<?=$this->data['attrs']['name']?>" id='rn_<?=$this->instanceID;?>_Options' <?=tabIndex($this->data['attrs']['tabindex'], 1);?>  length="30">
		<option value="0">--</option>
	        <? foreach($this->data['partnertypes'] as $key => $value): ?>
		<option value="<?=$value['ID'];?>" <?=$this->data['attrs']['selected_value'] == $value['ID'] ? 'selected="selected"' : $value['selected'] == 'selected' ? 'selected="selected"' : '' ;?> ><?=$value['Type']?></option>
	        <? endforeach;?>
	    </select>
</div>