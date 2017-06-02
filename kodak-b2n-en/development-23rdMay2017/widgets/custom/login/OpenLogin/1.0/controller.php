<?php
namespace Custom\Widgets\login;

class OpenLogin extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);

        $this->setAjaxHandlers(array(
            'default_ajax_endpoint' => array(
                'method'      => 'handle_default_ajax_endpoint',
                'clickstream' => 'custom_action',
            ),
        ));
    }

    function getData() {

    	        if((strtolower($this->data['attrs']['label_service_button']) === 'facebook' || stringContains(strtolower($this->data['attrs']['controller_endpoint']), '/ci/openlogin/oauth/authorize/facebook')) &&
            (!getConfig(FACEBOOK_OAUTH_APP_ID) || !getConfig(FACEBOOK_OAUTH_APP_SECRET))) {
            $this->reportError(sprintf(ASTRgetMessage("Both of the %s and %s configuration settings must be specified."), '<m4-ignore>FACEBOOK_OAUTH_APP_ID</m4-ignore>', '<m4-ignore>FACEBOOK_OAUTH_APP_SECRET</m4-ignore>'));
            return false;
        }
        if((strtolower($this->data['attrs']['label_service_button']) === 'twitter' || stringContains(strtolower($this->data['attrs']['controller_endpoint']), '/ci/openlogin/oauth/authorize/twitter')) &&
            (!getConfig(TWITTER_OAUTH_APP_ID) || !getConfig(TWITTER_OAUTH_APP_SECRET))) {
            $this->reportError(sprintf(ASTRgetMessage("Both of the %s and %s configuration settings must be specified."), '<m4-ignore>TWITTER_OAUTH_APP_ID</m4-ignore>', '<m4-ignore>TWITTER_OAUTH_APP_SECRET</m4-ignore>'));
            return false;
        }

        if($this->data['attrs']['openid'] && $this->data['attrs']['preset_openid_url']){
            $keyToReplace = '[username]';
            if(!stringContains($this->data['attrs']['preset_openid_url'], $keyToReplace)){
                echo $this->reportError(sprintf(getMessage(PCT_S_DOESNT_CONTAIN_PCT_S_VALUE_LBL), 'preset_openid_url', $keyToReplace));
                return false;
            }
        }
        if($redirectParam = getUrlParm('redirect')){
            //check if the redirect location is a fully-qualified or relative
            $redirectLocation = urldecode(urldecode($redirectParam));
            $parsedURL = parse_url($redirectLocation);

            if($parsedURL['scheme'] || (beginsWith($parsedURL['path'], '/ci/') || beginsWith($parsedURL['path'], '/cc/'))){
                $this->data['attrs']['redirect_url'] = $redirectLocation;
            }
            else{
                $this->data['attrs']['redirect_url'] = beginsWith($redirectLocation, '/app/') ? $redirectLocation : "/app/$redirectLocation";
            }
        }
        if(!endsWith($this->data['attrs']['controller_endpoint'], '/'))
            $this->data['attrs']['controller_endpoint'] .= '/';
        
        if($errorCode = getUrlParm('oautherror')){
            require_once(CPCORE . 'classes/OpenLoginErrors.php');
            $this->data['js']['error'] = OpenLoginErrors::getErrorMessage($errorCode);
        }
        $this->data['attrs']['redirect_url'] = urlParmDelete($this->data['attrs']['redirect_url'], 'oautherror');

        return parent::getData();

    }

    /**
     * Handles the default_ajax_endpoint AJAX request
     * @param array $params Get / Post parameters
     */
    function handle_default_ajax_endpoint($params) {
        // Perform AJAX-handling here...
        // echo response
    }
}