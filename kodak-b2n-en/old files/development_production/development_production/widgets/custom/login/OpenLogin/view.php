<rn:meta controller_path="custom/login/OpenLogin" 
    js_path="custom/login/OpenLogin" 
    presentation_css="widgetCss/OpenLogin.css" 
    compatibility_set="November '09+"
    required_js_module="november_09,mobile_may_10"/>

<div id="rn_<?=$this->instanceID;?>" class="rn_OpenLogin">
    <a href="javascript:void(0)" id="rn_<?=$this->instanceID;?>_ProviderButton" class="rn_LoginProvider rn_<?=$this->data['attrs']['label_service_button']?>"><?=$this->data['attrs']['label_service_button']?></a>
<div id="rn_<?=$this->instanceID;?>_ActionArea" class="rn_ActionArea rn_Hidden" aria-live="assertive">
        <form name="openLoginForm" method="post" action="" id="rn_<?=$this->instanceID;?>_Info" class="rn_OpenLoginForm <?=($this->data['attrs']['openid']) ? 'rn_OpenIDForm' : 'rn_OAuthForm';?>" onsubmit="return false;">
            <? if($this->data['attrs']['openid']):?>
                <label for="rn_<?=$this->instanceID;?>_ProviderUrl" class="rn_ScreenReaderOnly">#rn:msg:ENTER_YOUR_OPENID_PROVIDER_URL_LBL#</label>
                <input id="rn_<?=$this->instanceID;?>_ProviderUrl" type="text" value="<?=$this->data['attrs']['openid_placeholder']?>"/>
            <? endif;?>
            <input type="submit" id="rn_<?=$this->instanceID;?>_LoginButton" aria-labelledby="rn_<?=$this->instanceID;?>_LoginButtonLabel" class="rn_LoginButton rn_<?=$this->data['attrs']['label_service_button']?>" value="<?=$this->data['attrs']['label_login_button']?>"/>
            <div class="rn_Explanation">
                <label for="rn_<?=$this->instanceID;?>_LoginButton" id="rn_<?=$this->instanceID;?>_LoginButtonLabel">
                    <span class="rn_Header"><?=$this->data['attrs']['label_process_header']?><em></em></span>
                    <?=$this->data['attrs']['label_process_explanation'];?>
                </label>
            </div>
        </form>
    </div>
</div>
