<rn:meta controller_path="custom/CIHFunction/CustomTextInput" js_path="custom/CIHFunction/CustomTextInput" presentation_css="widgetCss/TextInput.css"  />

<input type="text" name="<?=$this->data['attrs']['name']?>" id="rn_<?=$this->instanceID?>_TextInput" value="<?=$this->data['attrs']['value']?>" <?= $this->data['attrs']['width'] ? 'maxlength="'.$this->data['attrs']['width'].'"'.' size="'.$this->data['attrs']['width'].'" ':''?> <?= $this->data['attrs']['readonly'] == true ? 'readonly="readonly" disabled="disabled"':'' ?> />

