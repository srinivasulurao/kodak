<?php
use RightNow\Connect\v1_2 as RNCPHP;
require_once(get_cfg_var('doc_root').'/include/ConnectPHP/Connect_init.phph');
initConnectAPI();

class Hooks_model extends Model
{
    function __construct()
    {
        parent::__construct();
    }


    public function mypre_login(&$arr)
    {
        $login = $this->input->post("login");
        
        try
        {
            $contact = RNCPHP\Contact::first("Login = '".$login."'");
            $org_id = $contact->Organization->ID;
        }catch(RNCPHP_CO\ConnectAPIError $err)
        {
            $err->getMessage();
        }

        if ($org_id == null)
            return;
        
        if(($org_id == 115) || ($org_id == 116) || ($org_id == 117))
            return "The username or password you entered is incorrect or your account has been disabled or you do not have access to this site. ";
        else
            return;
    }
		public function get_note($aid)
	{
		$answer = RNCPHP\Answer::fetch($aid,RNCPHP\RNObject::VALIDATE_KEYS_OFF );
		$note = $answer->Comment;
		return $note;
	}

}

