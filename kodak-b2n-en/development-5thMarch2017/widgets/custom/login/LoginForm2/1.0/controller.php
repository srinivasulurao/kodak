<?php
namespace Custom\Widgets\login;

class LoginForm2 extends \RightNow\Widgets\LoginForm {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

    	if(isLoggedIn())
            return false;
        if(getUrlParm('redirect'))
        {
            //We need to check if the redirect location is a fully qualified URL, or just a relative one
            $redirectLocation = urldecode(urldecode(getUrlParm('redirect')));
            $parsedURL = parse_url($redirectLocation);

            if($parsedURL['scheme'] || (beginsWith($parsedURL['path'], '/ci/') || beginsWith($parsedURL['path'], '/cc/')))
            {
                $this->data['js']['redirectOverride'] = $redirectLocation;
            }
            else
            {
                $this->data['js']['redirectOverride'] = beginsWith($redirectLocation, '/app/') ? $redirectLocation : "/app/$redirectLocation";
            }
        }

        //honor: (1) attribute's T value (2) config
        $this->data['attrs']['disable_password'] = ($this->data['attrs']['disable_password']) ? $this->data['attrs']['disable_password'] : !getConfig(EU_CUST_PASSWD_ENABLED);
        $this->data['username'] = getUrlParm('username');
        if($this->CI->agent->browser() === 'Internet Explorer')
            $this->data['isIE'] = true;

        return parent::getData();

    }
}