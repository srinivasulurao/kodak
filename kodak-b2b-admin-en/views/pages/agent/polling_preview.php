<!DOCTYPE html> 
<html>
<head>
<title><?=\RightNow\Utils\Config::getMessage(POLLING_SURVEY_PREVIEW_LBL);?></title>
<link rel="stylesheet" type="text/css" href="<?=\RightNow\Utils\Url::getYUICodePath('panel/assets/skins/sam/panel.css')?>" />
</head>
<body class="yui-skin-sam yui3-skin-sam">
<br />
<!-- survey_id is a fake number, the controller will grab the real survey_id from $_REQUEST -->
<rn:widget path="surveys/Polling" admin_console="true" survey_id="1234567"/>
</body>
</html>
