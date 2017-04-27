<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="#rn:language_code#" xml:lang="#rn:language_code#"> 
<head>
    <rn:theme path="/euf/assets/themes/Kodak" css="site.css,
		{YUI}/widget-stack/assets/skins/sam/widget-stack.css,
		{YUI}/widget-modality/assets/skins/sam/widget-modality.css,
		{YUI}/overlay/assets/overlay-core.css,
		{YUI}/panel/assets/skins/sam/panel.css" />
    <link rel="shortcut icon" href="/euf/assets/themes/Kodak/images/favicon.ico" type="image/x-icon" />
    <rn:head_content/>
<rn:condition logged_in="true">
      // Get Protocol type
<?	  
$uri = $_SERVER['REQUEST_URI']; 
$posreferer = strpos($uri, 'redirect');
//echo 'referer='.$url;
//echo 'posref='.$posreferer;
$url = substr($uri, strpos($uri,'redirect')+8);
if ($url == '') $url = '/app/home';
       header("Location: $url");  
	   ?>
<rn:condition_else>	   
<?	  
$uri = $_SERVER['REQUEST_URI']; 
$posreferer = strpos($uri, 'redirect');
echo 'referer='.$url;
echo 'posref='.$posreferer;
$url = substr($uri, strpos($uri,'redirect')+8);
/*       header("Location: $url");   */
	   ?>
<rn:widget path="login/OpenLogin" controller_endpoint="/ci/openlogin/openid/authorize" label_service_button=""  openid="true" 
						openid_placeholder="true"
						label_process_explanation="#rn:msg:YOULL_OPENID_PROVIDER_LOG_PROVIDER_MSG#" label_login_button="#rn:msg:LOG_IN_USING_THIS_OPENID_PROVIDER_LBL#"
	/>
      // Get Protocol type
</rn:condition>
</head>
<body>
            <rn:page_content/>
</body>
</html>