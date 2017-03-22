<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class CountryRegionSelector extends Widget
{
    function __construct()
    {
        parent::__construct();
		$this->attrs['countrylabel'] = new Attribute('', 'STRING', '', '');
		$this->attrs['regionlabel'] = new Attribute('', 'STRING', '', '');
		$this->attrs['name'] = new Attribute('', 'STRING', '', '');
		$this->attrs['label_input'] = new Attribute('', 'STRING', '', '');
		$this->attrs['selected_value'] = new Attribute('Selected Value','Int','The selected value of the select box when the page loads.',0);
		$this->attrs['required'] = new Attribute(getMessage(REQUIRED_LBL), 'BOOL', getMessage(SET_TRUE_FLD_CONT_VAL_CF_SET_REQD_MSG), false);
		$this->attrs['data'] = new Attribute('Data List', 'STRING', 'JSON encoded string of ID and LookupName values for the drop down list', '');
		
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = '';
    }

    function getData()
    {		
		if($this->data['attrs']['data'])
		{
			$menu = json_decode($this->data['attrs']['data'], true);
		}
		else
		{

			$this->CI->load->model('custom/ibase_product_model');
			$menu = $this->CI->ibase_product_model->getCountryList();
		}

		$this->data['menu'] = $menu;

    }
}
