<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
    <input type="text" name="<?=$this->data['attrs']['name']?>" id="rn_<?=$this->instanceID?>_TextInput" value="<?=$this->data['attrs']['value']?>" <?= $this->data['attrs']['width'] ? 'maxlength="'.$this->data['attrs']['width'].'"'.' size="'.$this->data['attrs']['width'].'" ':''?> <?= $this->data['attrs']['readonly'] == true ? 'readonly="readonly" disabled="disabled"':'' ?> />
</div>