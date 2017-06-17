<? /* Overriding LoginForm's view */ ?>


<div id="rn_<?=$this->instanceID;?>" class="rn_LoginForm2">
    <div id="rn_<?=$this->instanceID;?>_Content">
        <div id="rn_<?=$this->instanceID;?>_ErrorMessage"></div>
        <form method="post" action="" id="rn_<?=$this->instanceID;?>_Form" onsubmit="return false;">
            <label for="rn_<?=$this->instanceID;?>_Username"><?=$this->data['attrs']['label_username'];?></label>
            <input id="rn_<?=$this->instanceID;?>_Username" type="text" maxlength="80" value="<?=$this->data['username'];?>" />
        <?
if(!$this->data['attrs']['disable_password']):?>
            <label for="rn_<?=$this->instanceID;?>_Password"><?=$this->data['attrs']['label_password'];?></label>
            <input id="rn_<?=$this->instanceID;?>_Password" type="password" maxlength="20" value=''/>
        <?
elseif($this->data['isIE']):?>
            <label for="rn_<?=$this->instanceID;?>_HiddenInput" class="rn_Hidden">&nbsp;</label>
            <input id="rn_<?=$this->instanceID;?>_HiddenInput" type="text" class="rn_Hidden" disabled="disabled" />
        <?
endif;?>
            <br/>
            <input id="rn_<?=$this->instanceID;?>_Submit" type="submit" onclick="return false" value="<?=$this->data['attrs']['label_login_button'];?>" />
            <br/>
        </form>
    </div>
</div>