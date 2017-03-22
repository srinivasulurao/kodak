<rn:meta controller_path="custom/CIHFunction/ContactSelect" js_path="custom/CIHFunction/ContactSelect" presentation_css="widgetCss/ContactSelect.css" compatibility_set="November '09+"/>
<?
		$sesslang = get_instance()->session->getSessionData("lang");
		switch ($sesslang) {
        case "en":
			$ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
        case "fr":
			$ccih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 
			break;
        case "es":
			$ccih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 
			break;
        case "pt":
			$ccih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 
			break;
        default:
			$ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
		}						
?>
<div id="rn_<?=$this->instanceID;?>" class="rn_ContactSelect">
	<? if($this->data['attrs']['label']):?>
	<label for='rn_<?=$this->instanceID;?>_Options' class="rn_Label"><?=$this->data['attrs']['label']?></label><br/>
	<?endif;?>
    <select class="mysel" name="<?=$this->data['attrs']['name']?>" id='rn_<?=$this->instanceID;?>_Options' <?=tabIndex($this->data['attrs']['tabindex'], 1);?>>
		<option value="0"><?=$ccih_lang_msg_base_array["createnewcontact"] ?></option>
        <? foreach($this->data['contacts'] as $key => $value): ?>
	<option value="<?=$value['c_id'];?>" <?=($this->data['attrs']['selected_value'] == $value['c_id']) ? 'selected="selected"' : '';?> ><?=$value['last_name'].', '.$value['first_name'].' '.$value['phone']?></option>
        <? endforeach;?>
    </select>
</div>
