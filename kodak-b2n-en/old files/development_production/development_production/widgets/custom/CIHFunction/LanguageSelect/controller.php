<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class LanguageSelect extends Widget
{
    function __construct()
    {
        parent::__construct();
		$this->attrs['label'] = new Attribute('', 'STRING', '', '');
		$this->attrs['name'] = new Attribute('', 'STRING', '', '');
		$this->attrs['selected_value'] = new Attribute('Selected Value','Int','The selected value of the select box when the page loads.',0);
		$this->attrs['custom_field'] = new Attribute('', 'STRING', '', '');
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = '';
    }

    function getData()
    {

		$this->CI->load->model('custom/custom_contact_model');
		$languages = $this->CI->custom_contact_model->getLanguages($this->data['attrs']['custom_field']);
		$this->data['languages'] = $languages;

    }
}
