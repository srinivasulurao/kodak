<?php /* Originating Release: November 2015 */?>
<div id="rn_<?=$this->instanceID;?>" class="<?= $this->classList ?> rn_Hidden">
    <rn:block id="top"/>



    <div class="rn_FormContent">

        <div id="rn_<?=$this->instanceID;?>_LoginContent" class="rn_LoginDialogContent">
            <rn:block id="preLoginErrorMessage"/>
            <div id="rn_<?=$this->instanceID;?>_LoginErrorMessage"></div>
            <rn:block id="postLoginErrorMessage"/>

            <form id="rn_<?=$this->instanceID;?>_Form">
                <rn:block id="preUsername"/>
                <label for="rn_<?=$this->instanceID;?>_Username" class="rn_ScreenReaderOnly"><?=$this->data['attrs']['label_username'];?></label>
                <input id="rn_<?=$this->instanceID;?>_Username" placeholder="<?=$this->data['attrs']['label_username'];?>" type="text" maxlength="80" name="Contact.Login" autocorrect="off" autocapitalize="off" value="<?=$this->data['username'];?>"/>
                <rn:block id="postUsername"/>
            <? if(!$this->data['attrs']['disable_password']):?>
                <rn:block id="prePassword"/>
                <label for="rn_<?=$this->instanceID;?>_Password" class="rn_ScreenReaderOnly"><?=$this->data['attrs']['label_password'];?></label>
                <input id="rn_<?=$this->instanceID;?>_Password" maxlength="20"  placeholder="<?=$this->data['attrs']['label_password'];?>" type="password" name="Contact.Password" <?=($this->data['attrs']['disable_password_autocomplete']) ? 'autocomplete="off"' : '' ?>/>
                <rn:block id="postPassword"/>
            <? endif;?>
            </form>
        </div>

        <? if ($this->data['attrs']['create_account_fields']): ?>
        <div id="rn_<?= $this->instanceID ?>_SignUpContent" class="rn_SignUpDialogContent rn_Hidden">
            <rn:block id="preSignUpErrorMessage"/>
            <div id="rn_<?=$this->instanceID;?>_SignUpErrorMessage"></div>
            <rn:block id="postSignUpErrorMessage"/>

            <form action="<?= $this->data['attrs']['create_account_ajax'] ?>" id="rn_<?= $this->instanceID ?>_SignUpForm">
            <? foreach ($this->data['create_account_fields'] as $fieldName): ?>
                <rn:widget path="input/FormInput" sub_id='#rn:php:"input_$fieldName"#' name="#rn:php:$fieldName#"/>
            <? endforeach; ?>
            <div class="rn_Hidden">
                <rn:widget path="input/FormSubmit" sub_id="submit" error_location="rn_#rn:php:$this->instanceID#_SignUpErrorMessage" on_success_url="#rn:php:$this->data['currentPage']#"/>
            </div>
            </form>
        </div>
        <? endif; ?>

    </div>
    <rn:block id="bottom"/>
</div>