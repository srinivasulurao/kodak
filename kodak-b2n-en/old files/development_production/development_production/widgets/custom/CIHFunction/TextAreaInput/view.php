<rn:meta controller_path="custom/CIHFunction/TextAreaInput" js_path="custom/CIHFunction/TextAreaInput" presentation_css="widgetCss/TextAreaInput.css"  />

<textarea class="rn_TextAreaInput" type="text" name="<?=$this->data['attrs']['name']?>" id="rn_<?=$this->instanceID?>_TextAreaInput" style='<?= $this->data['attrs']['width'] ? 'width:'.$this->data['attrs']['width'] :'' ?><?= $this->data['attrs']['height'] ? ';height:'.$this->data['attrs']['height'] :'' ?>' ><?=$this->data['attrs']['value']?></textarea>

