<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="#rn:language_code#" xml:lang="#rn:language_code#"> 
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="X-UA-Compatible" content="chrome=1" />
    <title><rn:page_title/></title>
<? $servername = $_SERVER['SERVER_NAME'];  
$environ='partnerplace';
if ($servername == "dev-services.kodak.com" || ($servername == "kodak-b2b-en--upgrade.custhelp.com")) 
    { $environ='partnerplaceqa'; }
?>
<!--<rn:condition logged_in="true" >
<script type="text/javascript" src="https://<?echo $environ;?>.kodak.com/resources/scripts/jquery.js"></script>
<script type="text/javascript" src="https://<?echo $environ;?>.kodak.com/resources/scripts/jquery-noconflict.js"></script>
<script type="text/javascript" src="https://<?echo $environ;?>.kodak.com/resources/scripts/nav.js"></script>
<link rel="stylesheet" type="text/css" href="https://<?echo $environ;?>.kodak.com/resources/styles/styles.css" />
<script type="text/javascript">
	var page_application="Services"; 
	context = "serviceSupport";
</script>
<script src="https://<?echo $environ;?>.kodak.com/globalnav/headerfooter.js"></script>
</rn:condition> -->

<rn:condition logged_in="true" >
<script type="text/javascript" src="/euf/assets/partnerplaceJS/jquery.js"></script>
<script type="text/javascript" src="/euf/assets/partnerplaceJS/jquery-noconflict.js"></script>
<script type="text/javascript" src="/euf/assets/partnerplaceJS/nav.js"></script>
<link rel="stylesheet" type="text/css" href="/euf/assets/partnerplaceJS/css/format.css" />
<link rel="stylesheet" type="text/css" href="/euf/assets/partnerplaceJS/css/footer.css" />
<link rel="stylesheet" type="text/css" href="/euf/assets/partnerplaceJS/css/topnav.css" />
<script type="text/javascript">
	var page_application="Services"; 
	context = "serviceSupport";
</script>
<script src="/euf/assets/partnerplaceJS/headerfooter.js"></script>
</rn:condition>

<rn:widget path="search/BrowserSearchPlugin" pages="home, answers/list, answers/detail" />
    <rn:theme path="/euf/assets/themes/Kodak" css="site.css,
		{YUI}/widget-stack/assets/skins/sam/widget-stack.css,
		{YUI}/widget-modality/assets/skins/sam/widget-modality.css,
		{YUI}/overlay/assets/overlay-core.css,
		{YUI}/panel/assets/skins/sam/panel.css" />

    <rn:widget path="search/BrowserSearchPlugin" pages="home, answers/list, answers/detail" />
    <!--<rn:theme path="/euf/assets/themes/Kodak" css="site.css,/rnt/rnw/yui_2.7/container/assets/skins/sam/container.css" />-->
    <link rel="shortcut icon" href="/euf/assets/themes/Kodak/images/favicon.ico" type="image/x-icon" />
    <rn:head_content/>
<rn:condition logged_in="true" >
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
//    	alert("cp_resources cookie has been set");
    }
Set_Cookie('cp_resources', '42', 6, '/', 'kodak.com', '');

</script>
</rn:condition>
<!--[if lt IE 8]>
<link rel="stylesheet"  href="/euf/assets/themes/Kodak/ie7.css" type="text/css" />
<![endif]-->
</head>
<?
include(APPPATH.'libraries/load_msg_array.php');
$templ_msg_base_array=load_array("csv_b2b_template.php");
?>
<rn:condition logged_in = "true">
<script type="text/javascript">
header_element="inside";
footer_element="inside";
</script>
<!-- use this css to remove the context Partner Place Logo and footer_holder

#footer_holder, #context {
display:none;
}
#bottom {
height:35px;
}
-->
<style>
#footer_holder {
display:none;
}
#kodakHeader {
margin-top:10px;
}
#footer a {
    border-left: none;
}
</style>

</rn:condition>
<body class="yui-skin-sam">
<rn:condition show_on_pages="utils/login_form">
<div style="display:none">
</rn:condition>
<rn:condition logged_in="true">
<div id = "outside">
<div id = "inside">		
</rn:condition>
<rn:condition logged_in="false">
	<div id="kodakHeader">
		<div id="headerTop">
			<a href="http://www.kodak.com" class="logo"></a>   
			<div class="topHeadDiv chooseLang">[<? echo $templ_msg_base_array['lang_current']; ?> | <a href="<? echo $templ_msg_base_array['lang_url']; ?>"><? echo $templ_msg_base_array['lang_other']; ?></a>]
        <rn:condition is_spider="false">
				<rn:condition hide_on_pages="utils/login_form" >
                   <span id="largeLogin">&nbsp;|&nbsp; [<a class="rn_LoginProvider rn_" href="javascript:void(0);" onClick="document.getElementById('rn_OpenLogin_1_LoginButton').click();" id="rn_LoginLink">#rn:msg:LOG_IN_LBL#</a>
&nbsp;|&nbsp;<a href="https://<?echo $environ;?>.kodak.com/ups/Registration.jsp">Request Account</a>]</span>
				<rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button=""  openid="true" 
						openid_placeholder=""
						label_process_explanation="#rn:msg:YOULL_OPENID_PROVIDER_LOG_PROVIDER_MSG#" label_login_button="#rn:msg:LOG_IN_USING_THIS_OPENID_PROVIDER_LBL#" redirect_url="/app/home" />
                </rn:condition>
        </rn:condition>
            </div> 
	</div>
	<div id="headerMid">
			<a href="/" class="consumer"></a>
		</div>
		<div id="headerBot">
<rn:condition hide_on_pages="utils/help_search">
<ul class="menu">
<rn:condition show_on_pages="home" >
<li><a href="/app/home" class="curOutside"><span class="curInside menuText">My Kodak<!--<? echo $templ_msg_base_array['lbl_home']; ?> --></span></a></li>
<rn:condition_else/>
<style>
ul.menu li a.myKodak {
    background: url("images/layout/bBackMyKodak.gif") repeat-x scroll 0 0 transparent;
	color:#000000;

}
</style>
<li><a  class="myKodak" href="/app/home"><span class="menuText">My Kodak<!--<? echo $templ_msg_base_array['lbl_home']; ?> --></span><span class="menuSpacer"></span></a></li>
</rn:condition> 
</rn:condition>		

<rn:condition show_on_pages="answers/detail, answers/list" >
<li><a href="/app/answers/list" class="curOutside"><span class="curInside menuText"><? echo $templ_msg_base_array['lbl_search_answers']; ?></span></span></a></li>
<rn:condition_else/>
<li><a href="/app/answers/list"><span class="menuText"><? echo $templ_msg_base_array['lbl_search_answers']; ?></span><span class="menuSpacer"></span></a></li>
</rn:condition> 
<rn:condition logged_in="true" >
<rn:condition show_on_pages="account/overview, account/profile, account/change_password, account/reset_password, account/setup_password, account/questions/detail, account/questions/list" >
<li><a href="/app/account/overview"  class="curOutside"><span class="curInside menuText">#rn:msg:YOUR_ACCOUNT_LBL#</span>
<span class="menuArrow mASelected"></span></a>
<ul>
  <li><a href="/app/account/overview">#rn:msg:ACCOUNT_OVERVIEW_LBL#</a></li>
<rn:condition external_login_used="false" >
  <li><a href="/app/account/questions/list">#rn:msg:SUPPORT_HISTORY_LBL#</a></li>
</rn:condition>  
  <li><a href="/app/account/profile">#rn:msg:ACCOUNT_SETTINGS_LBL#</a></li>
  <li><a href="/app/account/notif/list">#rn:msg:NOTIFICATIONS_LBL#</a></li>
<rn:condition external_login_used="false" >
  <li><a href="/app/account/request_ibase">Product Listing</a></li>
</rn:condition>  
</ul>
</li>

  <rn:condition url_parameter_check="src == 'ibase'">
  <li><a href="/app/ask" ><span class="menuText">Request Support<br/></span></a></li>
  </rn:condition>
  
<rn:condition_else/>
<li><a href="/app/account/overview" ><span class="menuText">#rn:msg:YOUR_ACCOUNT_LBL#</span><span class="menuArrow mAUnSelected"></span></a>
<ul>
  <li><a href="/app/account/overview">#rn:msg:ACCOUNT_OVERVIEW_LBL#</a></li>
<rn:condition external_login_used="false" >
  <li><a href="/app/account/questions/list">#rn:msg:SUPPORT_HISTORY_LBL#</a></li>
</rn:condition>  
  <li><a href="/app/account/profile">#rn:msg:ACCOUNT_SETTINGS_LBL#</a></li>
  <li><a href="/app/account/notif/list">#rn:msg:NOTIFICATIONS_LBL#</a></li>
<rn:condition external_login_used="false" >
  <li><a href="/app/account/request_ibase">Product Listing</a></li>
</rn:condition>  
</ul>
</li>
</rn:condition>
</rn:condition>
</ul>
</rn:condition>
<rn:condition logged_in="false">			
        </div>
  </div> 
<div id="main">
<rn:condition_else>
<div id="amain">
</rn:condition>  
	<div id="content">
<div id="rn_Container" >
<rn:widget path="custom/CIHFunction/Roles" />
    <div id="rn_SkipNav"><a href="#rn_MainContent">#rn:msg:SKIP_NAVIGATION_CMD#</a></div>
    <div id="rn_Body">
        <div id="rn_MainColumn_full">
            <a name="rn_MainContent" id="rn_MainContent"></a>
            <rn:page_content/>
        </div>
    </div>

    </div>
    </div>
<!--    </div>  -->
	<div id="footer">
	<? echo $templ_msg_base_array['footer_html']; ?>		
	</div>

<rn:condition logged_in="true">
    <div class="bottom">
</div>
<rn:condition_else>
    </div>
</rn:condition>	
<? echo $templ_msg_base_array['seo_script_tag']; ?>
</body>
</html>
