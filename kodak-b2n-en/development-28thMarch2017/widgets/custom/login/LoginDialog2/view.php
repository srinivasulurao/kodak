<rn:meta controller_path="custom/login/LoginDialog2" js_path="custom/login/LoginDialog2" presentation_css="widgetCss/LoginDialog2.css" compatibility_set="November '09+"/><div id="rn_<?=$this->instanceID;?>" class="rn_LoginDialog2 rn_Hidden <?=($this->data['attrs']['open_login_url']) ? 'rn_AdditionalOpenLogin' : ''?>">    <? if($this->data['attrs']['open_login_url']):?>    <div class="rn_OpenLoginAlternative">        <a id="rn_<?=$this->instanceID;?>_OpenLoginLink" href="<?=$this->data['attrs']['open_login_url'] . sessionParm();?>"><?=$this->data['attrs']['label_open_login_link']?></a>    </div>    <? endif;?>    <div id="rn_<?=$this->instanceID;?>_Content" class="rn_LoginDialogContent">        <div id="rn_<?=$this->instanceID;?>_ErrorMessage"></div>        If you have a Kodak Partner Place account, please log in to the <a href="https://partnerplace.kodak.com/login.html">Partner Place portal</a> to access all content that is available to you on My Kodak Services<br/><br/>        <form method="post" action="" id="rn_<?=$this->instanceID;?>_Form">            <label for="rn_<?=$this->instanceID;?>_Username"><?=$this->data['attrs']['label_username'];?></label>            <input id="rn_<?=$this->instanceID;?>_Username" type="text" maxlength="80" value="<?=$this->data['username'];?>" <?=tabIndex($this->data['attrs']['tabindex'], 1);?>/>        <? if(!$this->data['attrs']['disable_password']):?>            <label for="rn_<?=$this->instanceID;?>_Password"><?=$this->data['attrs']['label_password'];?></label>            <input id="rn_<?=$this->instanceID;?>_Password" type="password" value='' <?=tabIndex($this->data['attrs']['tabindex'], 2);?>/>        <? endif;?>        </form>        <span><a href="http://kodak-b2b-admin-en.custhelp.com/app/utils/account_assistance" <?=tabIndex($this->data['attrs']['tabindex'], 3);?>><?=$this->data['attrs']['label_assistance'];?></a></span>    </div></div>