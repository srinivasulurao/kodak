<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ListFailedOrgContacts extends Widget
{
    function __construct()
    {
        parent::__construct();
		$this->attrs['label'] = new Attribute('', 'STRING', '', '');
		$this->attrs['selected_value'] = new Attribute('Selected Value','Int','The selected value of the select box when the page loads.',0);
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = '';
    }

    function getData()
    {

		$this->CI->load->model('custom/custom_contact_model');
		$contacts = $this->CI->custom_contact_model->getPPErrorOrgContacts();

		$this->data['contacts'] = $contacts;

    }
}
