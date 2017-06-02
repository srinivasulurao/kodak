<?php
namespace Custom\Widgets\CIHFunction;

class RoleSelect extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

        $this->CI->load->model('custom/Roles_model');
        $roles = $this->CI->Roles_model->getRoles();
         $this->CI->load->model('custom/custom_contact_model');
         $org_id=$this->CI->session->getProfile()->org_id->value;
        $contacts = $this->CI->custom_contact_model->getOrgContacts($org_id,1); //All Activated Contacts.
        
        //$role_functions = $this->CI->Roles_model->getRoleFunctionById(2);
        $role2functions = $this->CI->Roles_model->getRole2FunctionsByRoleID(1);
        $this->data['roles'] = $roles;

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