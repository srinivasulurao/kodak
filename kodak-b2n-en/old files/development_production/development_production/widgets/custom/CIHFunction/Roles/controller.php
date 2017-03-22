<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends Widget
{
    function __construct()
    {
        parent::__construct();
		
    
    }

    function generateWidgetInformation()
    {
        //Create information to display in the tag gallery here
        $this->info['notes'] = 'Determine and check roles for logged in users.';
    }

    function getData()
    {
        //Perform php logic here
    }
	
	public function hasRoleFunction($role_function)
	{
		$hasRole = false;
				
		$this->CI->load->model('custom/roles_model');
		$this->CI->load->model('custom/custom_contact_model');
		
		$user_role_id = $this->CI->custom_contact_model->getLoggedInUsersRole();
		$user_role_id = 1;
		$user_roles = $this->CI->roles_model->getRole2FunctionsByRoleID($user_role_id);

		foreach($user_roles as $value)
			{
			if(is_int($role))
				{
				if((int)$value['Function_ID'] == $role_function)
					{
						$hasRole = true;	
					}
				}
				else
				{
				if(strtolower($value['Function_Name']) == strtolower($role_function))
					{
						$hasRole = true;	
					}
				}					
			}
		
		return $hasRole;
	}
}



