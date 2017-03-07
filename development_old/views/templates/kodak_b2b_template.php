<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="#rn:language_code#" xml:lang="#rn:language_code#"> 
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="X-UA-Compatible" content="chrome=1" />
    <title><rn:page_title/></title>
    <rn:widget path="search/BrowserSearchPlugin" pages="home, answers/list, answers/detail" />
    <rn:theme path="/euf/assets/themes/Kodak" css="site.css,/rnt/rnw/yui_2.7/container/assets/skins/sam/container.css" />
    <link rel="shortcut icon" href="/euf/assets/themes/Kodak/images/favicon.ico" type="image/x-icon" />
    <rn:head_content/>
<!--    <link rel="shortcut icon" href="images/favicon.ico" />  -->
<rn:condition show_on_pages="utils/submit/password_changed">
<script>
function Set_Cookie( name, value, expires, path, domain, secure )
    {
    	// set time, it's in milliseconds
    	var today = new Date();
    	today.setTime( today.getTime() );

    	/*
    	if the expires variable is set, make the correct
    	expires time, the current script below will set
    	it for x number of days, to make it for hours,
    	delete * 24, for minutes, delete * 60 * 24
    	*/
    	if ( expires )
    	{
    	expires = expires * 1000 * 60 * 60 * 24;
    	}
    	var expires_date = new Date( today.getTime() + (expires) );

    	document.cookie = 'cp_resources' + "=" +escape( value ) +
    	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
    	( ( path ) ? ";path=" + path : "" ) +
    	( ( domain ) ? ";domain=" + domain : "" ) +
    	( ( secure ) ? ";secure" : "" );
    }

Set_Cookie('cp_resources', '42', 6, '/', 'kodak.com', '');

</script>
</rn:condition>
</head>
<body class="yui-skin-sam">
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
			<div class="topHeadDiv chooseLang">
			<? echo $templ_msg_base_array['lang_current']; ?> | <a href="<? echo $templ_msg_base_array['lang_url']; ?>"><? echo $templ_msg_base_array['lang_other']; ?></a>
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
<!--<rn:condition show_on_pages="browse/list" >
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
<li><a href="/app/account/notif/list">#rn:msg:NOTIFICATIONS_LBL#</a></li>
<li><a href="/app/account/profile">#rn:msg:ACCOUNT_SETTINGS_LBL#</a></li>
</ul>
<rn:condition_else/>
<li><a href="/app/account/overview" ><span class="menuText">#rn:msg:YOUR_ACCOUNT_LBL#</span><span class="menuArrow mAUnSelected"></span></a>
<ul>
<li><a href="/app/account/overview">#rn:msg:ACCOUNT_OVERVIEW_LBL#</a></li>
<li><a href="/app/account/questions/list">#rn:msg:SUPPORT_HISTORY_LBL#</a></li>
<li><a href="/app/account/notif/list">#rn:msg:NOTIFICATIONS_LBL#</a></li>
<li><a href="/app/account/profile">#rn:msg:ACCOUNT_SETTINGS_LBL#</a></li>
</ul>
</rn:condition>   
</li>
</ul>
</rn:condition>
			<div >
        <rn:condition is_spider="false">
            <div id="rn_LoginStatus">
                <rn:condition logged_in="true">
                     #rn:msg:WELCOME_BACK_LBL#
                        <rn:field name="contacts.full_name"/>
                    <rn:widget path="login/LogoutLink2"/>
 <!--                       <rn:field name="contacts.organization_name"/>  -->
                <rn:condition_else />
                    <a href="javascript:void(0);" id="rn_LoginLink">#rn:msg:LOG_IN_LBL#</a><!--&nbsp;|&nbsp;<a href="/app/utils/create_account#rn:session#">#rn:msg:SIGN_UP_LBL#</a>-->
                    <rn:condition hide_on_pages="utils/create_account, utils/login_form, utils/account_assistance">
                        <rn:widget path="login/LoginDialog2" trigger_element="rn_LoginLink"/>
                    </rn:condition>
                    <rn:condition show_on_pages="utils/create_account, utils/login_form, utils/account_assistance">
                        <rn:widget path="login/LoginDialog2" trigger_element="rn_LoginLink" redirect_url="/app/account/overview" />
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
			<rn:meta login_required="true" /> 
            <rn:page_content/>
        </div>
    <rn:condition show_on_pages="home" >
<style>
#rn_SideBar .rn_Padding {
    overflow: hidden;
}
</style>
</rn:condition>
        <rn:condition is_spider="false">
            <div id="rn_SideBar">
                <div class="rn_Padding">
                    <rn:condition hide_on_pages="answers/list, account/questions/list, browse/list, utils/help_search">
                    <div class="rn_Module">
                        <h2>#rn:msg:FIND_ANS_HDG#</h2>
                        <rn:widget path="search/SimpleSearch"/>
                    </div>
                    </rn:condition>
    <rn:condition show_on_pages="home" >
    <br/><!--<rn:widget path="utils/AnnouncementText2" file_path="/euf/assets/announcements.php" />   -->
	<h2>Announcements</h2>
        <rn:widget path="reports/Multiline2" report_id="100807" />
	
	    <img style="margin-top:16px;" src="/euf/assets/themes/Kodak/images/layout/welcomeVault.jpg" />  

    </rn:condition>
                    <rn:condition show_on_pages="answers/detail" >
<script>var l = document.links;
for(var i=0; i<l.length; i++) 
{  if   (l[i].href.indexOf("file=") > 0) 
{   //alert ('there is download manager link on this page');	 
  document.write('<p> <a target="_blank" href="/app/answers/detail/a_id/66996">Problems Downloading?</a></p>');	
  break;  }
}</script> 					
                    <div class="rn_Module">
                       <h2>#rn:msg:ANS_DETAIL_LBL#</h2>
<style>
.rn_Multiline2 .rn_Element1{
    font-size: 1em;
}
</style>
					   
					   	<div class="rn_Padding">	
<!--    <strong><? echo $templ_msg_base_array['lbl_note']; ?></strong> -->
	<!--	<rn:widget path="reports/Multiline2" hide_when_no_results="false" report_id="#rn:php:$templ_msg_base_array['notes_report_id']#" />  -->
<?
$aid = getURLParm("a_id");
$CI = get_instance();
$CI->load->model('custom/Hooks_model');
$comment = $CI->Hooks_model->get_note($aid);
$date = date('Y-m-d', time());
$str = substr($comment,0,10);
//echo '<p>str '.$str.'</p>';
if (preg_match('((19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01]))',$str)) {
            $valid_input = 1;
//            echo "<p>Date From is valid</p>";
}  
else {
//            echo '<p class="red_text">Date From is invalid.</p>';
            $valid_input = 0;
}
$str1 = strtotime($date) - (strtotime($str));
$notedaysold = floor($str1/3600/24);
//echo '<p>notedaysold'.$notedaysold.'</p>';
//echo '<p>date '.$date.'</p>';
//echo '<p>str1 '.$str1.'</p>';
if ($comment != "") {
	if (($valid_input) && ($notedaysold <=14)) 
//		echo '<strong>Recent Note</strong><p><strong><span style="color:#008000;">'.$comment.'</span></strong></p>';
		echo '<p style="margin-left:-7px; padding-left:7px; padding-top:2px; padding-bottom:2px; padding-right:2px; background-color:#ffe26e;"><strong>Note</strong><br/>'.$comment.'</p>';
	else  echo '<strong>Note</strong><p>'.$comment.'</p>'; 
}	
else echo '<strong>Note</strong><p> No Recent Updates</p>';
?>
    <rn:widget path="output/DataDisplay" name="answers.products" left_justify="true"  report_page_url="/app/answers/list" />
    <rn:widget path="output/DataDisplay" name="answers.categories" left_justify="true"  report_page_url="/app/answers/list" />
    <b>Security Classification:</b><br/><rn:field name="answers.c$ek_security_classification" /><br/><br/>
    <b>Access levels:</b><br/>
	<script> var acl = '<rn:field name="answers.access_level" left-justify="true" />';
    document.write(acl.replace(/,/g, "<br/>"));
    </script>
    <!--<b>Access levels:</b><br/><rn:field name="answers.access_level" left-justify="true" /><br/> -->
    
                       </div>
                    </div>
                    </rn:condition>
                    
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
  

    <div id="rn_Footer" style="display:none;">
        <div id="rn_RightNowCredit">
            <div class="rn_FloatRight">
                <rn:widget path="utils/RightNowLogo"/>
            </div>
        </div>
    </div>

</div>
</div>
    <div class="bottom">
	<div id="footer">
<? echo $templ_msg_base_array['footer_html']; ?>		
    <rn:condition show_on_pages="answers/detail">
<div style="color:#ccc;"><br/><br/><strong>Confidentiality Information</strong> -- Please note that this site includes confidential and proprietary information of the Kodak Graphic Communications Canada Company and its subsidiaries ("Information"). The users of this site shall at all times maintain the Information in the strictest confidence and shall not modify, copy or adapt the Information or disclose it to any person, firm or corporation, except to employees of their respective companies on a need to know basis.</div>
    </rn:condition>
	</div>
	</div>
		</div>
</div>
<? echo $templ_msg_base_array['seo_script_tag']; ?>

</body>
</html>
