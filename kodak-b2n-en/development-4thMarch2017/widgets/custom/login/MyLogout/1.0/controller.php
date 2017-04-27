<?php
namespace Custom\Widgets\login;

class MyLogout extends \RightNow\Widgets\LogoutLink {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

        //if(!isLoggedIn() || (isPta() && !getConfig(PTA_EXTERNAL_LOGOUT_SCRIPT_URL)))
//        return false;

        return parent::getData();

    }

    /**
     * Overridable methods from LogoutLink:
     */
    // function doLogout($parameters)
}