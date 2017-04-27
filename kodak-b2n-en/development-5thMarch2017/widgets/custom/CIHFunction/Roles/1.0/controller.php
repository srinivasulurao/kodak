<?php
namespace Custom\Widgets\CIHFunction;

class Roles extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
        return parent::getData();

    }
    public function hasRoleFunction($role_function)
	{
		$hasRole = false;		
		$user_role_id = $this->CI->model('custom/custom_contact_model')->getLoggedInUsersRole();
		$user_role_id = 1;
		$user_roles = $this->CI->model('custom/roles_model')->getRole2FunctionsByRoleID($user_role_id);
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