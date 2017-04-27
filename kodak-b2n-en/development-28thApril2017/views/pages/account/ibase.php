<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="#rn:language_code#" xml:lang="#rn:language_code#">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="X-UA-Compatible" content="chrome=1" />
    <title><rn:page_title/></title>
    <rn:widget path="search/BrowserSearchPlugin" pages="home, answers/list, answers/detail" />
    <rn:theme path="/euf/assets/themes/Kodak" css="site.css,/rnt/rnw/yui_2.7/container/assets/skins/sam/container.css" />
    <rn:head_content/>
    <link rel="icon" href="images/favicon.png" type="image/png"/>
</head>
<body class="yui-skin-sam">
<div id="rn_Container" >
    
<rn:widget path="standard/utils/AnnouncementText2" label_heading="Product Support" file_path="/euf/assets/RequestService.html" />

<?php 
$CI = &get_instance();
 $profile = $CI->session->getProfile();
 $c_id = $profile->c_id->value;
 $org_id = $profile->org_id->value;
 
 ?>
<rn:widget path="custom/reports/Grid2_ibaselist"  report_id="100309"/>

</div>
</body>
</html>


