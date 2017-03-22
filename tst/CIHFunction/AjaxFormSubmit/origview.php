<rn:meta controller_path="custom/CIHFunction/AjaxFormSubmit" 
    js_path="custom/CIHFunction/AjaxFormSubmit" 
    base_css="standard/input/FormSubmit" 
    presentation_css="widgetCss/FormSubmit.css" 
    compatibility_set="November '09+"
    required_js_module="november_09,mobile_may_10"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_FormSubmit">
    <input type="submit" id="rn_<?=$this->instanceID;?>_Button" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> value="<?=$this->data['attrs']['label_button']?>"/>
    <? if($this->data['attrs']['loading_icon_path']):?>
    <img id="rn_<?=$this->instanceID;?>_LoadingIcon" class="rn_Hidden" alt="<?=getMessage(LOADING_LBL)?>" src="<?=$this->data['attrs']['loading_icon_path'];?>" />
    <? endif;?>
    <span id="rn_<?=$this->instanceID;?>_StatusMessage"></span>
    <input id="rn_<?=$this->instanceID;?>_Submission" type ="hidden"/>
</div>
