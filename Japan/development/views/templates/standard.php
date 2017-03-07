<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="#rn:language_code#" xml:lang="#rn:language_code#">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="X-UA-Compatible" content="chrome=1" />
    <title><rn:page_title/></title>
    <rn:widget path="search/BrowserSearchPlugin" pages="home, answers/list, answers/detail" />
    <rn:theme path="/euf/assets/themes/standard" css="site.css,
        {YUI}/widget-stack/assets/skins/sam/widget-stack.css,
        {YUI}/widget-modality/assets/skins/sam/widget-modality.css,
        {YUI}/overlay/assets/overlay-core.css,
        {YUI}/panel/assets/skins/sam/panel.css" />
    <rn:head_content/>
    <link rel="icon" href="images/favicon.png" type="image/png"/>
</head>
<body class="yui-skin-sam yui3-skin-sam">>
<div id="rn_Container" >
    <div id="rn_SkipNav"><a href="#rn_MainContent">#rn:msg:SKIP_NAVIGATION_CMD#</a></div>
    <div id="rn_Header">
    <noscript><h1>#rn:msg:SCRIPTING_ENABLED_SITE_MSG#</h1></noscript>
        <div id="rn_Logo"><a href="/app/home#rn:session#"><span class="rn_LogoTitle">#rn:msg:SUPPORT_LBL# <span class="rn_LogoTitleMinor">#rn:msg:CENTER_LBL#</span></span></a></div>
        <rn:condition is_spider="false">
            <div id="rn_LoginStatus">
                <rn:condition logged_in="true">
                     #rn:msg:WELCOME_BACK_LBL#
                    <strong>
                        <rn:field name="contacts.full_name"/>
                    </strong>
                    <div>
                        <rn:field name="contacts.organization_name"/>
                    </div>
                    <rn:widget path="login/LogoutLink"/>
                <rn:condition_else />
                    <a href="javascript:void(0);" id="rn_LoginLink">#rn:msg:LOG_IN_LBL#</a>&nbsp;|&nbsp;<a href="/app/utils/create_account#rn:session#">#rn:msg:SIGN_UP_LBL#</a>
                    <rn:condition hide_on_pages="utils/create_account, utils/login_form, utils/account_assistance">
                        <rn:widget path="login/LoginDialog" trigger_element="rn_LoginLink"/>
                    </rn:condition>
                    <rn:condition show_on_pages="utils/create_account, utils/login_form, utils/account_assistance">
                        <rn:widget path="login/LoginDialog" trigger_element="rn_LoginLink" redirect_url="/app/account/overview" />
                    </rn:condition>
                </rn:condition>
            </div>
        </rn:condition>
    </div>
    <div id="rn_Navigation">
    <rn:condition hide_on_pages="utils/help_search">
        <div id="rn_NavigationBar">
            <ul>
                <li><rn:widget path="navigation/NavigationTab" label_tab="#rn:msg:SUPPORT_HOME_TAB_HDG#" link="/app/home" pages="home, "/></li>
                <li><rn:widget path="navigation/NavigationTab" label_tab="#rn:msg:ANSWERS_HDG#" link="/app/answers/list" pages="answers/list, answers/detail"/></li>
                
            </ul>
        </div>
    </rn:condition>
    </div>
    <div id="rn_Body">
        <div id="rn_MainColumn">
            <a name="rn_MainContent" id="rn_MainContent"></a>
            <rn:page_content/>
        </div>
        <rn:condition is_spider="false">
        <div id="rn_sidebar" >
                       
                <rn:widget path="utils/AnnouncementText" label_heading="Announcements" file_path="/euf/assets/announcements" /> 
       
        </div>

        </rn:condition>
    </div>
    <div id="rn_Footer">
        <div id="rn_RightNowCredit">
            <div class="rn_FloatRight">
                 <!-- <rn:widget path="utils/RightNowLogo"/> -->
                <rn:widget path="utils/OracleLogo"/>
            </div>
        </div>
    </div>
</div>
</body>
</html>
