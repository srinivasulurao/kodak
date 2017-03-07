<?php /* Originating Release: February 2014 */ ?>
<rn:meta controller_path="standard/login/LoginForm2" 
    js_path="custom/login/LoginForm2" 
    base_css="standard/login/LoginForm2" 
    presentation_css="widgetCss/LoginForm2.css" 
    compatibility_set="November '09+"
    required_js_module="november_09,mobile_may_10"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_LoginForm2">
    <div id="rn_<?=$this->instanceID;?>_Content">
        <div id="rn_<?=$this->instanceID;?>_ErrorMessage"></div>
        <form id="rn_<?=$this->instanceID;?>_Form" onsubmit="return false;">
            <label for="rn_<?=$this->instanceID;?>_Username"><?=$this->data['attrs']['label_username'];?></label>
            <input id="rn_<?=$this->instanceID;?>_Username" type="text" maxlength="80" name="rn_<?=$this->instanceID;?>_Username" value="<?=$this->data['username'];?>" <?=tabIndex($this->data['attrs']['tabindex'], 1);?>/>
        <? if(!$this->data['attrs']['disable_password']):?>
            <label for="rn_<?=$this->instanceID;?>_Password"><?=$this->data['attrs']['label_password'];?></label>
            <input id="rn_<?=$this->instanceID;?>_Password" type="password" maxlength="20" value="" name="rn_<?=$this->instanceID;?>_Password" <?=($this->data['attrs']['disable_password_autocomplete']) ? 'autocomplete="off"' : '' ?> <?=tabIndex($this->data['attrs']['tabindex'], 2);?>/>
        <? elseif($this->data['isIE']):?>
            <label for="rn_<?=$this->instanceID;?>_HiddenInput" class="rn_Hidden">&nbsp;</label>
            <input id="rn_<?=$this->instanceID;?>_HiddenInput" type="text" class="rn_Hidden" disabled="disabled" />
        <? endif;?>
            <br/>
            <input id="rn_<?=$this->instanceID;?>_Submit" type="submit" onclick="return false;" value="<?=$this->data['attrs']['label_login_button'];?>" <?=tabIndex($this->data['attrs']['tabindex'], 3);?>/>
            <br/>
        </form>
    </div>
</div>
