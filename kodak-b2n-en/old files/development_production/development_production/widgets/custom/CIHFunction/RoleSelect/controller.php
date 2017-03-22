<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class RoleSelect extends Widget
{
    function __construct()
    {
        parent::__construct();
		$this->attrs['label'] = new Attribute('', 'STRING', '', '');
		$this->attrs['name'] = new Attribute('', 'STRING', '', '');
		$this->attrs['selected_value'] = new Attribute('Selected Value','Int','The selected value of the select box when the page loads.',0);
		$this->attrs['label_required'] = new Attribute('Required text', 'STRING', 'Text to display when the field is required', 'Role is required');
		
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = '';
    }

    function getData()
    {
		$this->CI->load->model('custom/Roles_model');
		$roles = $this->CI->Roles_model->getRoles();
		 $this->CI->load->model('custom/custom_contact_model');
		$contacts = $this->CI->custom_contact_model->getOrgContacts();
		
		//$role_functions = $this->CI->Roles_model->getRoleFunctionById(2);
		$role2functions = $this->CI->Roles_model->getRole2FunctionsByRoleID(1);
		$this->data['roles'] = $roles;

    }
}
