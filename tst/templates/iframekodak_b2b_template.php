<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="#rn:language_code#" xml:lang="#rn:language_code#"> 
<head>
    <rn:theme path="/euf/assets/themes/Kodak" css="site.css,/rnt/rnw/yui_2.7/container/assets/skins/sam/container.css" />
	<script type="text/javascript" src="/euf/assets/jquery.js"></script>
	<script type="text/javascript" src="/euf/assets/jquery.ba-resize.min.js"></script>
<? $servername = $_SERVER['SERVER_NAME'];  
$environ='partnerplace';
if ($servername == "dev-services.kodak.com") 
    { $environ='partnerplaceqa'; }
?>
	<script type="text/javascript">
		$(document).ready(function(){
			<!--- Start of Pat's code -->
			$('<iframe src="https://<?echo $environ;?>.kodak.com/helper/xdomain.html#'+$(document.body).height()+'" id="manageHeightFrame" name="tempFrame" height="50" style="display:none;"/>').appendTo($('body'));
			
			$('#iframepage').resize(function(){
				//Reload iframe to send the new height
				$('#manageHeightFrame').attr('src', 'https://<?echo $environ;?>.kodak.com/helper/xdomain.html?time=' + Date() + '#' + $(document.body).height());
			});
			<!-- End of Pat's code -->
		});
	</script>
    <link rel="shortcut icon" href="/euf/assets/themes/Kodak/images/favicon.ico" type="image/x-icon" />
    <rn:head_content/>

</head>
<body  style="background:#FFFFFF;" class="yui-skin-sam">
<div style="background-color:#FFFFFF;width:930px;">
<rn:page_content/>
</div>
</body>
</html>
