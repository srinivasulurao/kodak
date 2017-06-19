<rn:meta javascript_module="mobile_may_10"/>
<!DOCTYPE html>
<html lang="#rn:language_code#">
    <head>
        <meta name="viewport" content="width=device-width; initial-scale=1.0; minimum-scale=1.0; maximum-scale=1.0; user-scalable=no;"/>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <meta http-equiv="Content-Style-Type" content="text/css"/>
        <meta http-equiv="Content-Script-Type" content="text/javascript"/>
        <title><rn:page_title/></title>
        <rn:theme path="/euf/assets/themes/mobile" css="site.css,
			{YUI}/widget-stack/assets/skins/sam/widget-stack.css,
			{YUI}/widget-modality/assets/skins/sam/widget-modality.css,
			{YUI}/overlay/assets/overlay-core.css,
			{YUI}/panel/assets/skins/sam/panel.css" />
        <rn:head_content/>
    <link rel="shortcut icon" href="images/favicon.ico" />
    </head>
    <body>
        <noscript><h1>#rn:msg:SCRIPTING_ENABLED_SITE_MSG#</h1></noscript>
        <div id="headerTop">
        <a href="http://www.kodak.com" class="logo"></a>
        </div>
        <rn:condition hide_on_pages="utils/login_form" >
        <div align="center" class="rn_padding">
                <rn:condition logged_in="true">
                     #rn:msg:WELCOME_BACK_LBL#
                    <strong>
                        <rn:field name="contacts.full_name"/>
                    </strong>
                    <rn:widget path="custom/login/LogoutLink2"/>
                <rn:condition_else />
<!--                    <a href="/app/utils/login_form" id="rn_LoginLink">#rn:msg:LOG_IN_LBL#</a>  -->
                </rn:condition>
        </div>
        </rn:condition>
        
        <header>
            <rn:condition is_spider="false">
            <nav id="rn_Navigation">
                <span class="rn_FloatRight rn_Search">
                    <rn:widget path="navigation/MobileNavigationMenu" label_button="#rn:msg:SEARCH_LBL#<img src='images/search.png' alt='#rn:msg:SEARCH_LBL#'/>" submenu="rn_SearchForm"/>
                </span>
                <div id="rn_SearchForm" class="rn_Hidden">
                    <rn:widget path="search/MobileSimpleSearch" report_page_url="/app/answers/list"/>
                </div>
            </nav>
            </rn:condition>
        </header>

        <section>
            <rn:page_content/>
        </section>

        <footer>
<!--            <div class="rn_FloatLeft"><a href="javascript:window.scrollTo(0, 0);">#rn:msg:ARR_BACK_TO_TOP_LBL#</a></div>
            <div class="rn_FloatRight">Powered by <a href="http://www.rightnow.com">RightNow</a></div>
            <br/><br/>
-->            
        </footer>
    </body>
</html>
