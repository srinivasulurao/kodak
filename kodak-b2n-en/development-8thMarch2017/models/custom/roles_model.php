<?php
namespace Custom\Models;

use RightNow\Connect\v1_2 as RNCPHP;
require_once( get_cfg_var("doc_root")."/include/ConnectPHP/Connect_init.phph" );
initConnectAPI();
class Roles_model extends \RightNow\Models\Base {
    function __construct() {
        parent::__construct();
    }
	
	public function getRoles()
	{
		$lang = get_instance()->session->getSessionData("lang");
		switch ($lang) {
        case "en":
			$ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
        case "fr":
			$ccih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 
			break;
        case "es":
			$ccih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 
			break;
        case "pt":
			$ccih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 
			break;
        default:
			$ccih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
		}						
		
		
		$roles_array = array();
		
		$roles = RNCPHP\ROQL::queryObject("Select CIH.ek_role from CIH.ek_role")->next();
		while($role = $roles->next())
		{
		$langvalue = $role->LookupName;
		if (($role->LookupName == "Super User") && (($lang=="fr") || ($lang=="es") || ($lang=="pt")))	$langvalue = $ccih_lang_msg_base_array['dd_superuser'];			
		if (($role->LookupName == "Standard User") && (($lang=="fr") || ($lang=="es") || ($lang=="pt")))	$langvalue = $ccih_lang_msg_base_array['dd_standarduser'];		
		if (($role->LookupName == "Repair Only") && (($lang=="fr") || ($lang=="es") || ($lang=="pt")))	$langvalue = $ccih_lang_msg_base_array['dd_repaironly'];			
		$roles_array[] = array('ID'=>$role->ID,'LookupName'=>$langvalue);			
		}
		
		return $roles_array;	
	}
		
	public function getRoleFunctionById($id_list)
	{
		$roleFunctions_array = array();
		
		$roleFunctions = RNCPHP\ROQL::queryObject(sprintf("Select CIH.ek_function from CIH.ek_function where ID in (%s)",$id_list))->next();
		
		while($roleFunction = $roleFunctions->next())
		{
			$roleFunctions_array = array('ID'=>$roleFunction->ID,'LookupName'=>$roleFunction->LookupName);		
		}
		
		return 	$roleFunctions_array;
	}
	
	public function getRole2FunctionsByRoleID($role_id)
	{
		$role2Function_array = array();
	
		$query = sprintf('Select CIH.ek_role2function from CIH.ek_role2function where role_id.ID = %s',$role_id);
		
		$role2Functions = RNCPHP\ROQL::queryObject($query)->next();
		while($role2Function = $role2Functions->next())
		{			
			
			$role2Function_array[] = array('Function_ID'=>$role2Function->function_id->ID,'Function_Name'=>$role2Function->function_id->LookupName);		
		}
		
		return $role2Function_array;
	}
	
}
