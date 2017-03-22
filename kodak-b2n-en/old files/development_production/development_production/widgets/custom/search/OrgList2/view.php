<?php /* Originating Release: February 2012 */ ?>
<rn:meta controller_path="custom/search/OrgList2" js_path="custom/search/OrgList2" presentation_css="widgetCss/OrgList2.css"  compatibility_set="November '09+"/>
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

<div id="rn_<?=$this->instanceID;?>" class="rn_OrgList2">
    <label for="rn_<?=$this->instanceID;?>_Options"><?=$this->data['attrs']['label_title']?></label>
    <select id="rn_<?=$this->instanceID;?>_Options" <?=tabIndex($this->data['attrs']['tabindex'], 1);?>>
            <option value='1' <?=($this->data['js']['defaultIndex'] == 1) ? 'selected = "selected"' : '';?>><? echo $cih_lang_msg_base_array['sradd_fromanyone']; ?></option>
            <option value='0' <?=($this->data['js']['defaultIndex'] == 0) ? '' : '';?>><? echo $cih_lang_msg_base_array['sradd_onlymyinc']; ?></option>
    </select>
</div>
