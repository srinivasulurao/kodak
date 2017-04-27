<rn:meta title="#rn:msg:ERROR_LBL#" template="mobile.php" />
<?
switch(getUrlParm('error_id'))
{
    case 1:
        $errorTitle = getMessage(NOT_AVAIL_LBL);
        $errorMessage = getMessage(ANSWER_IS_NO_LONGER_AVAILABLE_MSG);
        break;
    case 2:
        $errorTitle = getMessage(NOT_AVAIL_LBL);
        $errorMessage = getMessage(SORRY_ACCT_DOESNT_SERV_LVL_AGRMNT_MSG);
        break;
    case 3:
        $errorTitle = getMessage(FILE_DOWNLOAD_ERROR_LBL);
        $errorMessage = getMessage(SORRY_ERROR_DOWNLOADING_FILE_MSG);
        break;
    case 4:
        $errorTitle = getMessage(PERMISSION_DENIED_LBL);
        $errorMessage = getMessage(NO_ACCESS_PERMISSION_MSG);
        break;
    case 5:
        $errorTitle = getMessage(OPERATION_FAILED_LBL);
        $errorMessage = getMessage(OPERATION_TIMEOUT_BTN_REFRESH_PAGE_LBL);
        break;
    case 6:
        $errorTitle = getMessage(PERMISSION_DENIED_LBL);
        $errorMessage = getMessage(ILLEGAL_PARAMETER_LBL);
        break;
    case 7:
        $externalLogin = getConfig(PTA_EXTERNAL_LOGIN_URL);
        $loginPage = ($externalLogin) ? $externalLogin : '/app/' . getConfig(CP_LOGIN_URL) . sessionParm();
        $errorTitle = getMessage(COOKIES_ARE_REQUIRED_MSG);
        $errorMessage = getMessage(YOULL_ENABLE_COOKIES_BROWSER_BEF_MSG) . "<br/><a href='$loginPage'>" . getMessage(BACK_TO_LOGIN_CMD) . '</a>';
        break;
    case 'sso9':
        $errorTitle = getMessage(INCOMPLETE_ACCOUNT_DATA_LBL);
        $errorMessage = sprintf(getMessage(SRRY_CREATE_ACCT_COMMUNITY_SPEC_MSG), '<a href="/app/account/profile' . sessionParm() . '">', '</a>');
        break;
    case 'sso10':
        $errorTitle = getMessage(INCOMPLETE_ACCOUNT_DATA_LBL);
        $errorMessage = sprintf(getMessage(SORRY_CREATE_ACCT_COMMUNITY_SPEC_MSG), '<a href="/app/account/profile' . sessionParm() . '">', '</a>');
        break;
    case 'sso11':
        $errorTitle = getMessage(DUPLICATE_EMAIL_UC_LBL);
        $errorMessage = sprintf(getMessage(SORRY_EMAIL_ADDR_EXISTS_COMMUNITY_MSG), '<a href="/app/utils/account_assistance' . sessionParm() . '">', '</a>');
        break;
    case 'sso13':
    case 'sso14':
    case 'sso15':
        $errorTitle = getMessage(AUTHENTICATION_FAILED_LBL);
        $errorMessage = getMessage(LINK_CLICKED_CONTAINED_CMD);
        break;
    case 404:
        $errorTitle = getMessage(NOT_FOUND_UC_LBL);
        $errorMessage = sprintf(getMessage(PAGE_PCT_S_NOT_FOUND_MSG), urldecode(getUrlParm('url')));
        break;
    default:
        $errorTitle = getMessage(UNKNOWN_ERR_MSG);
        $errorMessage = getMessage(UNKNOWN_ERR_LBL);
        break;
}
?>
<section id="rn_PageTitle" class="rn_ErrorPage">
    <h1><?=$errorTitle;?></h1>
</section>
<section id="rn_PageContent" class="rn_ErrorPage">
    <div class="rn_Padding">
        <p><?=$errorMessage;?></p>
    </div>
</section>
