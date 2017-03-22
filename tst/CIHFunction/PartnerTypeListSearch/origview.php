<rn:meta controller_path="custom/CIHFunction/PartnerTypeListSearch"  base_css="custom/CIHFunction/PartnerTypeListSearch" js_path="custom/CIHFunction/PartnerTypeListSearch" presentation_css="widgetCss/PartnerTypeList.css" compatibility_set="November '09+"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_webSearchType">
    <? if($this->data['attrs']['label']):?>
	<span for='rn_<?=$this->instanceID;?>_Options' class="rn_Label"><?=$this->data['attrs']['label']?></span><br/>
	<?endif;?>
    <select name="<?=$this->data['attrs']['name']?>" id='rn_<?=$this->instanceID;?>_Options' <?=tabIndex($this->data['attrs']['tabindex'], 1);?> class="rn_partnerTypeListSearch">
		<option value="0">Select a value...</option>
        <? foreach($this->data['partnertypes'] as $key => $value): ?>
	<option value="<?=$value['ID'];?>" <?=($this->data['attrs']['selected_value'] == $value['ID']) ? 'selected="selected"' : '';?> ><?=$value['Type']?></option>
        <? endforeach;?>
    </select>
</div>
