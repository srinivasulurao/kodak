<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ContactCommunicationOptin extends Widget
{
    function __construct()
    {
        parent::__construct();
		$this->attrs['label'] = new Attribute('', 'STRING', '', '');
		$this->attrs['name'] = new Attribute('', 'STRING', '', '');
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = '';
    }

    function getData()
    {
/*
		$this->CI->load->model('custom/custom_contact_model');
		$org_id = $this->CI->session->getProfileData('org_id');
		$contacts = $this->CI->custom_contact_model->getOrgContacts($org_id,$this->data['attrs']['include_deactivated']);
		$this->data['contacts'] = $contacts;
		$this->data['js']['last_contact_id'] = $contacts[0]['last_contact_id'];
		$this->data['js']['org_id'] = $org_id;
*/
    }
}
