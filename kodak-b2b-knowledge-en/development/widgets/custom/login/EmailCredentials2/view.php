<rn:meta controller_path="standard/login/EmailCredentials2" js_path="standard/login/EmailCredentials2" presentation_css="widgetCss/EmailCredentials2.css" compatibility_set="November '09+" required_js_module="november_09,mobile_may_10"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_EmailCredentials2">
<?if($this->data['attrs']['label_heading'] !== ''):?>
    <h2><?=$this->data['attrs']['label_heading']?></h2>
<?endif;?>
<?if($this->data['attrs']['label_description'] !== ''):?>
    <p><?=$this->data['attrs']['label_description']?></p>
<?endif;?>
    <form id="rn_<?=$this->instanceID;?>_Form" action="" onsubmit="return false">
        <label for="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['credential_type'];?>_Input"><?=$this->data['attrs']['label_input'];?></label>
        <input id="rn_<?=$this->instanceID;?>_<?=$this->data['attrs']['credential_type'];?>_Input" type="text" maxlength="8" <?=tabIndex($this->data['attrs']['tabindex'], 1);?> value="<?=$this->data['email'];?>" />
        <input type="submit" value="<?=$this->data['attrs']['label_button']?>" <?=tabIndex($this->data['attrs']['tabindex'], 2);?>/>
        <div id="rn_<?=$this->instanceID;?>_LoadingIcon" class="rn_LoadingIndicator rn_Hidden">&nbsp;</div>
    </form>
</div>
