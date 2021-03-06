<rn:meta javascript_module="none"/>
<!DOCTYPE html>
<html lang="#rn:language_code#">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <title><rn:page_title/></title>
		<rn:theme path="/euf/assets/themes/basic" css="site.css,
			{YUI}/widget-stack/assets/skins/sam/widget-stack.css,
			{YUI}/widget-modality/assets/skins/sam/widget-modality.css,
			{YUI}/overlay/assets/overlay-core.css,
			{YUI}/panel/assets/skins/sam/panel.css" />
        <style type="text/css">
            #rn_SkipNav a{
                left:0px;
                height:1px;
                overflow:hidden;
                position:absolute;
                top:-500px;
                width:1px;
            }
            #rn_SkipNav a:active, #rn_SkipNav a:focus{
                background-color:#FFF;
                height:auto;
                left:auto;
                top:auto;
                width:auto;
            }
            .rn_Header{
                font-weight:bold;
                font-size:18pt;
            }
            .rn_LinksBlock a{
                display:block;
                margin-bottom:10px;
            }
            a img{
                border:0;
            }
            .rn_CenterText{
                text-align:center;
            }
            h1{
                font-weight:bold;
                font-size:16pt;
                line-height:1.4em;
                margin:0;
                padding:0;
            }
            h2{
                font-size:14pt;
                line-height:1.3em;
                margin:0;
                padding:0;
            }
            h3{
                font-size:12pt;
                line-height:1.2em;
                margin:0;
                padding:0;
            }
        </style>
        <rn:head_content/>
        <rn:widget path="utils/ClickjackPrevention"/>
    </head>
    <body>
        <div id="rn_SkipNav"><a href="<?=ORIGINAL_REQUEST_URI?>#rn_MainContent">#rn:msg:SKIP_NAVIGATION_CMD#</a></div>
        <rn:widget path="utils/CapabilityDetector"/>
        <div class="rn_Header rn_CenterText">#rn:msg:SUPPORT_CENTER_LBL#</div>
        <hr/>
        <div class="rn_CenterText">
            <a href="/app/#rn:config:CP_HOME_URL##rn:session#">#rn:msg:HOME_LBL#</a>&nbsp;|
            <a href="/app/ask#rn:session#">#rn:msg:EMAIL_US_LBL#</a>&nbsp;|
            <a href="javascript:void(0);">#rn:msg:CALL_US_LBL#</a>
        </div>
        <hr/>
        <div><a id="rn_MainContent"></a></div>
        <rn:page_content/>
        <hr/>
        <div class="rn_CenterText">
            <rn:condition logged_in="true">
                <strong><rn:field name="Contact.LookupName"/><rn:condition language_in="ja-JP">#rn:msg:NAME_SUFFIX_LBL#</rn:condition></strong>&nbsp;|
                <rn:widget path="login/BasicLogoutLink" label="#rn:msg:LOGOUT_CMD#"/>&nbsp;|
                <a href="/app/account/overview#rn:session#">#rn:msg:YOUR_ACCOUNT_LBL#</a>
            <rn:condition_else />
                <rn:condition config_check="PTA_ENABLED == true">
                    <a href="javascript:void(0);">#rn:msg:LOG_IN_LBL#</a>&nbsp;|
                    <a href="javascript:void(0);">#rn:msg:SIGN_UP_LBL#</a>
                <rn:condition_else />
                    <a href="/app/#rn:config:CP_LOGIN_URL##rn:session#">#rn:msg:LOG_IN_LBL#</a>&nbsp;|
                    <a href="/app/utils/create_account#rn:session#">#rn:msg:SIGN_UP_LBL#</a>
                </rn:condition>
            </rn:condition>
            <br/>
            <rn:widget path="utils/PageSetSelector" label_message=""/>
        </div>
    </body>
</html>
