<rn:meta title="#rn:msg:ERROR_LBL#" template="standard.php" login_required="false" />

<?list($errorTitle, $errorMessage) = \RightNow\Utils\Framework::getErrorMessageFromCode(\RightNow\Utils\Url::getParameter('error_id'));?>
<div id="rn_PageTitle" class="rn_ErrorPage">
    <h1><?=$errorTitle;?></h1>
</div>
<div id="rn_PageContent" class="rn_ErrorPage">
    <div class="rn_Padding">
        <p><?=$errorMessage;?></p>
    </div>
</div>
