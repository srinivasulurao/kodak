<?php
namespace Custom\Controllers;
use RightNow\Utils\Framework,
    RightNow\Libraries\AbuseDetection,
    RightNow\Utils\Config,
    RightNow\Utils\Okcs;
class CustomProfileManager extends \RightNow\Controllers\Base
{
    function __construct()
    {
        parent::__construct();
    }

    function saveProfile(){
    	AbuseDetection::check($this->input->post('f_tok'));
        $data=json_decode($this->input->post('form'));
        $response = $this->model('custom/profile_manager_model')->saveProfileData($data);
        echo $response;
    }

    public function test(){
        echo "Hii";
    }

    
} // Class Ends here.

