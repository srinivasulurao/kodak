<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <title><?=getMessage(ANSWER_PRINT_PAGE_LBL);?></title>
    <link href="<?=$baseurl;?>/euf/assets/themes/Kodak/site.css" rel="stylesheet" type="text/css" media="all" />
    <link href="<?=$baseurl;?>/rnt/rnw/yui_2.7/container/assets/skins/sam/container.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
setResourcesCookie('cp_resources', '42', 6, '/', 'kodak.com', '');
function setResourcesCookie( name, value, expires, path, domain, secure )
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

    	document.cookie = name + "=" +escape( value ) +
    	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
    	( ( path ) ? ";path=" + path : "" ) +
    	( ( domain ) ? ";domain=" + domain : "" ) +
    	( ( secure ) ? ";secure" : "" );
    }
</script>
</head>

<body class="yui-skin-sam">
<div id="rn_Container">
    <div id="rn_Header"></div>
    <div id="rn_Navigation"></div>
    <div id="rn_Body">
      <div id="rn_MainColumn">
        <div id="rn_PageTitle" class="rn_AnswerDetail">
            <h1 id="rn_Summary"><?=$summary;?></h1>
            <div id="rn_AnswerInfo"></div>
            <?=$description;?>
        </div>
        <div id="rn_PageContent" class="rn_AnswerDetail">
            <div id="rn_AnswerText">
                <p><?=$solution;?></p>
            </div>
            <div id="rn_FileAttach" class="rn_FileListDisplay">
                <?if(count($file_attachments) > 0):?>
                    <span class="rn_DataLabel"> <?= getMessage(FILE_ATTACHMENTS_LBL) ?> </span>
                    <div class="rn_DataValue rn_FileList">
                        <ul>
                            <?$loopCount = count($file_attachments);
                            for($i=0; $i<$loopCount; $i++):?>
                            <li>
                                <a href="/ci/fattach/get/<?=$file_attachments[$i][0] . '/' . $file_attachments[$i][2] . sessionParm()?>" target="_blank"><?=$file_attachments[$i]['icon'];?><?=$file_attachments[$i][1];?></a>
                            </li>
                            <?endfor;?>
                        </ul>
                    </div>
                <?endif;?>
            </div>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
var tags = document.getElementsByTagName('A');

for (var i = tags.length - 1; i >= 0; i--)
{
    tags[i].target = "_blank";
}

tags = document.getElementsByTagName('FORM');

for (var i = tags.length - 1; i >= 0; i--)
{
    tags[i].onsubmit = new Function("return(disabled_msg())");
}

function disabled_msg()
{
    alert("<?= getMessageJS(DISABLED_FOR_PREVIEW_MSG) ?>");
    return(false);
}
</script>

</body>
</html>
