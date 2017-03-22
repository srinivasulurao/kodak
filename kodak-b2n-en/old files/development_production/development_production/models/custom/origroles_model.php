<?php
use RightNow\Connect\v1_2 as RNCPHP;
require_once( get_cfg_var("doc_root")."/include/ConnectPHP/Connect_init.phph" );
initConnectAPI();
class Roles_model extends Model
{
    function __construct()
    {
        parent::__construct();
        //This model would be loaded by using $this->load->model('custom/Sample_model');
    }
	
	public function getRoles()
	{
		$roles_array = array();
		
		$roles = RNCPHP\ROQL::queryObject("Select CIH.ek_role from CIH.ek_role")->next();

		while($role = $roles->next())
		{
			$roles_array[] = array('ID'=>$role->ID,'LookupName'=>$role->LookupName);			
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
