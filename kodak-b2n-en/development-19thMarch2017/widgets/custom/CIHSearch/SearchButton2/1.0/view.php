<?
		$sesslang = get_instance()->session->getSessionData("lang");
		switch ($sesslang) {
        case "en":
			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
        case "fr":
			$cih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 
			break;
        case "es":
			$cih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 
			break;
        case "pt":
			$cih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 
			break;
        default:
			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
		}						
?>
<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
       <? if ($this->data['attrs']['icon_path']):?>
        <input type="image" class="rn_SubmitImage" id="rn_<?=$this->instanceID;?>_Button" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> src="<?=$this->data['attrs']['icon_path'];?>" alt="<?=$this->data['attrs']['icon_alt_text'];?>" title="<? echo $cih_lang_msg_base_array['search']; ?>"/>
    <? else:?>
    <input type="submit" class="rn_Button" name="rnServiceRequestSearchButton" id="rn_<?=$this->instanceID;?>_Button" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> value="<? echo $cih_lang_msg_base_array['search']; ?>" />
    <? endif;?>
    <? if($this->data['isIE']): ?>
    <label for="rn_<?=$this->instanceID;?>_HiddenInput" class="rn_Hidden">&nbsp;</label>
    <input id="rn_<?=$this->instanceID;?>_HiddenInput" type="text" class="rn_Hidden" disabled="disabled" />
    <? endif;?>
</div>