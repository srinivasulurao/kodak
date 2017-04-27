<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="#rn:language_code#" xml:lang="#rn:language_code#"> 
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
<?
include(APPPATH.'libraries/load_msg_array.php');
$templ_msg_base_array=load_array("csv_b2b_template.php");
?>
<body class="yui-skin-sam yui3-skin-sam">
<div id="main">
	<div id="kodakHeader">
		<div id="headerTop">
			<a href="http://www.kodak.com" class="logo"></a>
			<div class="topHeadDiv">
			</div>
			<div class="topHeadDiv chooseLang"><!--<? echo $templ_msg_base_array['lang_current']; ?> | <a href="<? echo $templ_msg_base_array['lang_url']; ?>"><? echo $templ_msg_base_array['lang_other']; ?></a>
            -->
            </div> 
		</div>
		<div id="headerMid">
			<a href="/" class="consumer"></a>
		</div>
		<div id="headerBot">
			<div >
        <rn:condition is_spider="false">
            <div id="rn_LoginStatus">
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
    <rn:condition show_on_pages="home" >
    <div id="rn_SideBar">
    <img style="margin-top:16px;" src="/euf/assets/themes/Kodak/images/layout/welcomeMKS.jpg" />
     <br/><br/><rn:widget path="utils/AnnouncementText2" file_path="/euf/assets/announcements.php" />
    </div>
    </rn:condition>

        <rn:condition is_spider="false">
            <div id="rn_SideBar">
                <div class="rn_Padding">
<!--                    <rn:condition hide_on_pages="answers/list, home, account/questions/list, browse/list, utils/help_search">
                    <div class="rn_Module">
                        <h2>#rn:msg:FIND_ANS_HDG#</h2>
                        <rn:widget path="search/SimpleSearch"/>
                    </div>
                    </rn:condition>
                    <rn:condition show_on_pages="answers/detail" >
                    <div class="rn_Module">
                       <h2>#rn:msg:ANS_DETAIL_LBL#</h2>
                       <div class="rn_Padding">&nbsp;
                       <rn:widget path="output/DataDisplay" name="answers.products" left_justify="true" />
    <rn:widget path="output/DataDisplay" name="answers.categories" left_justify="true"/>
                       </div>
                    </div>
                    </rn:condition>
-->                    
<!--                    <div class="rn_Module">
                        <h2>#rn:msg:CONTACT_US_LBL#</h2>
                        <div class="rn_HelpResources">
                            <div class="rn_Questions">
                                <a href="/app/ask#rn:session#">#rn:msg:ASK_QUESTION_LBL#</a>
                                <span>#rn:msg:SUBMIT_QUESTION_OUR_SUPPORT_TEAM_CMD#</span>
                            </div>
                            <div class="rn_Feedback">
                                <rn:widget path="feedback/SiteFeedback2" />
                                <span>#rn:msg:SITE_USEFUL_MSG#</span>
                            </div>
                        </div>
                    </div>
                        -->
                </div>
            </div>
        </rn:condition>
    </div>
<!--    <div id="rn_Footer">
        <div id="rn_RightNowCredit">
            <div class="rn_FloatRight">
                <rn:widget path="utils/RightNowLogo"/>
            </div>
        </div>
    </div>
-->        

</div>
</div>
    <div class="bottom">
	<div id="footer">
	</div>
	</div>
</div>

</body>
</html>
