<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="#rn:language_code#" xml:lang="#rn:language_code#"> 
<head>
    <rn:theme path="/euf/assets/themes/Kodak" css="site.css,/rnt/rnw/yui_2.7/container/assets/skins/sam/container.css" />
    <link rel="shortcut icon" href="/euf/assets/themes/Kodak/images/favicon.ico" type="image/x-icon" />
    <rn:head_content/>
<rn:condition logged_in="true">
      // Get Protocol type
<?	  
$uri = $_SERVER['REQUEST_URI']; 
$posreferer = strpos($uri, 'redirect');
echo 'referer='.$url;
echo 'posref='.$posreferer;
$url = substr($uri, strpos($uri,'redirect')+8);

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
