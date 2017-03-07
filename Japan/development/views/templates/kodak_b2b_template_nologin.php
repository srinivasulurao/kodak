<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="#rn:language_code#" xml:lang=kground-color: white; 
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="X-UA-Compatible" content="chrome=1" />
    <title><rn:page_title/></title>
    <rn:widget path="search/BrowserSearchPlugin" pages="home, answers/list, answers/detail" />
    
<rn:theme path="/euf/assets/themes/Kodak" css="site.css,
{YUI}/widget-stack/assets/skins/sam/widget-stack.css,
{YUI}/widget-modality/assets/skins/sam/widget-modality.css,
{YUI}/overlay/assets/overlay-core.css,
{YUI}/panel/assets/skins/sam/panel.css" />

<rn:head_content/>
    <link rel="shortcut icon" href="images/favicon.ico" />
</head>

<body class="yui-skin-sam yui3-skin-sam">
<?
include(APPPATH.'libraries/load_msg_array.php');
$templ_msg_base_array=load_array("csv_b2b_template.php");
?>
<div id="main">
	<div id="kodakHeader">
		<div id="vaultheaderTop">
			<a href="http://www.kodak.com" class="logo"></a>
			<div class="topHeadDiv">
			</div>
			<div class="topHeadDiv chooseLang"><? echo $templ_msg_base_array['lang_current']; ?> | <a href="<? echo $templ_msg_base_array['lang_url']; ?>"><? echo $templ_msg_base_array['lang_other']; ?></a>
            </div> 
		</div>
		<div id="vaultheaderMid">
			<a href="/" class="vaultconsumer"></a>
		</div>
		<div id="headerBot">
<rn:condition hide_on_pages="utils/help_search">
<ul class="menu">
<rn:condition show_on_pages="home" >
<li><a href="/app/home" class="curOutside"><span class="curInside menuText"><? echo $templ_msg_base_array['lbl_home']; ?></span></a></li>
<rn:condition_else/>
<li><a href="/app/home"><span class="menuText"><? echo $templ_msg_base_array['lbl_home']; ?></span><span class="menuSpacer"></span></a></li>
</rn:condition> 
<!--
<rn:condition show_on_pages="browse/list" >
<li><a href="/app/browse/list" class="curOutside"><span class="curInside menuText"><? echo $templ_msg_base_array['lbl_browse_answers']; ?></span></a></li>
<rn:condition_else/>
<li><a href="/app/browse/list"><span class="menuText"><? echo $templ_msg_base_array['lbl_browse_answers']; ?></span><span class="menuSpacer"></span></a></li>
</rn:condition> 
--> 
<rn:condition show_on_pages="answers/detail, answers/list" >
<li><a href="/app/answers/list" class="curOutside"><span class="curInside menuText"><? echo $templ_msg_base_array['lbl_search_answers']; ?></span></span></a></li>
<rn:condition_else/>
<li><a href="/app/answers/list"><span class="menuText"><? echo $templ_msg_base_array['lbl_search_answers']; ?></span><span class="menuSpacer"></span></a></li>
</rn:condition> 
<rn:condition show_on_pages="ask, ask_confirm" >
<li><a href="/app/ask" class="curOutside"><span class="curInside menuText"><? echo $templ_msg_base_array['lbl_propose']; ?></span></a></li>
<rn:condition_else/>
<li><a href="/app/ask" ><span class="menuText"><? echo $templ_msg_base_array['lbl_propose']; ?></span></a></li>
</rn:condition>   
<rn:condition show_on_pages="account/overview, account/profile, account/change_password, account/reset_password, account/setup_password, account/questions/detail, account/questions/list" >
<li><a href="/app/account/overview"  class="curOutside"><span class="curInside menuText">#rn:msg:YOUR_ACCOUNT_LBL#</span>
<span class="menuArrow mASelected"></span></a>
<ul>
<li><a href="/app/account/overview">#rn:msg:ACCOUNT_OVERVIEW_LBL#</a></li>
<li><a href="/app/account/questions/list">#rn:msg:SUPPORT_HISTORY_LBL#</a></li>
<li><a href="/app/account/profile">#rn:msg:ACCOUNT_SETTINGS_LBL#</a></li>
<li><a href="/app/account/notif/list">#rn:msg:NOTIFICATIONS_LBL#</a></li>
</ul>
</li>
<rn:condition_else/>
<li><a href="/app/account/overview" ><span class="menuText">#rn:msg:YOUR_ACCOUNT_LBL#</span><span class="menuArrow mAUnSelected"></span></a>
<ul>
<li><a href="/app/account/overview">#rn:msg:ACCOUNT_OVERVIEW_LBL#</a></li>
<li><a href="/app/account/questions/list">#rn:msg:SUPPORT_HISTORY_LBL#</a></li>
<li><a href="/app/account/profile">#rn:msg:ACCOUNT_SETTINGS_LBL#</a></li>
<li><a href="/app/account/notif/list">#rn:msg:NOTIFICATIONS_LBL#</a></li>
</ul>
</li>
</rn:condition>   
</ul>
</rn:condition>
			<div class="custom-logindialog" >
        <rn:condition is_spider="false">
            <div id="rn_LoginStatus">
                <rn:condition logged_in="true">
                     #rn:msg:WELCOME_BACK_LBL#
                        <rn:field name="contacts.full_name"/>
                    <rn:widget path="login/LogoutLink"/>
                       <rn:field name="contacts.organization_name"/>  
                <rn:condition_else />
                    <a href="javascript:void(0);" id="rn_LoginLink">#rn:msg:LOG_IN_LBL#</a><!--&nbsp;|&nbsp;<a href="/app/utils/create_account#rn:session#">#rn:msg:SIGN_UP_LBL#</a>-->
                    <rn:condition hide_on_pages="utils/create_account, utils/login_form, utils/account_assistance">
                        <rn:widget path="custom/login/CustomLoginDialog" trigger_element="rn_LoginLink"/>
                    </rn:condition>
                    <rn:condition show_on_pages="utils/create_account, utils/login_form, utils/account_assistance">
                        <rn:widget path="custom/login/CustomLoginDialog" trigger_element="rn_LoginLink" redirect_url="/app/account/overview" />
                    </rn:condition>
                </rn:condition>
            </div>
        </rn:condition>
			</div>
        </div>
  </div> 
	<div id="content">
<div id="rn_Container" >
    <div id="rn_SkipNav"><a href="#rn_MainContent">#rn:msg:SKIP_NAVIGATION_CMD#</a></div>
    <div id="rn_Body">
        <div id="rn_MainColumn">
            <a name="rn_MainContent" id="rn_MainContent"></a>
            <rn:page_content/>
        </div>
        <rn:condition show_on_pages="home, utils/login_form" >
       <div id="rn_SideBar">
       <img style="margin-top:16px;" src="/euf/assets/themes/Kodak/images/layout/welcomeVault.jpg" />
       </div>
       </rn:condition>
        
<!--        <rn:condition is_spider="false">
            <div id="rn_SideBar">
                <div class="rn_Padding">
                    <rn:condition hide_on_pages="answers/list, home, account/questions/list">
                    <div class="rn_Module">
                        <h2>#rn:msg:FIND_ANS_HDG#</h2>
                        <rn:widget path="search/SimpleSearch"/>
                    </div>
                    </rn:condition>
                    <div class="rn_Module">
                        <h2>#rn:msg:CONTACT_US_LBL#</h2>
                        <div class="rn_HelpResources">
                            <div class="rn_Questions">
                                <a href="/app/ask#rn:session#">#rn:msg:ASK_QUESTION_LBL#</a>
                                <span>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</span>
                            </div>
                            <div class="rn_Feedback">
                                <rn:widget path="feedback/SiteFeedback" />
                                <span>#rn:msg:SITE_USEFUL_MSG#</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </rn:condition>
-->
    </div>
<!--    <div id="rn_Footer">
        <div id="rn_RightNowCredit">
            <div class="rn_FloatRight">
                 <!-- <rn:widget path="utils/RightNowLogo"/> -->
                <rn:widget path="utils/OracleLogo"/>
            </div>
        </div>
    </div>
-->    

</div>
</div>
    <div class="bottom">
	<div id="footer">
		<? echo $templ_msg_base_array['footer_html']; ?>	
	</div>
	</div>
</div>
<? echo $templ_msg_base_array['seo_script_tag']; ?>   

</body>
</html>
