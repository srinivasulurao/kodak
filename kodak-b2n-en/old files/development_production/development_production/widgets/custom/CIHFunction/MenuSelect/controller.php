<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class MenuSelect extends Widget
{
    function __construct()
    {
        parent::__construct();
		$this->attrs['label'] = new Attribute('', 'STRING', '', '');
		$this->attrs['name'] = new Attribute('', 'STRING', '', '');
		$this->attrs['label_input'] = new Attribute('', 'STRING', '', '');
		$this->attrs['selected_value'] = new Attribute('Selected Value','Int','The selected value of the select box when the page loads.',0);
		$this->attrs['custom_field'] = new Attribute('', 'STRING', '', '');
		$this->attrs['table'] = new Attribute('', 'STRING', '', 'Contact');
		$this->attrs['required'] = new Attribute(getMessage(REQUIRED_LBL), 'BOOL', getMessage(SET_TRUE_FLD_CONT_VAL_CF_SET_REQD_MSG), false);
		$this->attrs['label_required'] = new Attribute(getMessage(REQUIRED_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_REQUIREMENT_MESSAGE_LBL), getMessage(PCT_S_IS_REQUIRED_MSG));
		$this->attrs['data'] = new Attribute('Data List', 'STRING', 'JSON encoded string of ID and LookupName values for the drop down list', '');
		$this->attrs['usetextasvalue'] = new Attribute('Use option text as value', 'BOOL', 'Use the option text instead of the defined value as the value of the selected item', false);
		$this->attrs['core_field'] = new Attribute('Core Field','BOOL','Is the field being referenced a core field (not a custom field)',false);
		$this->attrs['is_search_filter'] = new Attribute('Search Filter', 'BOOL', 'Field is being used as a search filter', false);
		$this->attrs['search_filer_id'] = new Attribute('Filter ID', 'INT', 'Search filter id.', null);
		$this->attrs['search_report_id'] = new Attribute('Report ID', 'INT', 'Search report id.', null);
		$this->attrs['search_operator_id'] = new Attribute('Operator ID', 'INT', 'Search operator id.', null);
		$this->attrs['remove_options'] = new Attribute('Remove options', 'STRING', 'Options to remove from the drop down list', '');	

    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = '';
    }

    function getData()
    {	
		$lang = get_instance()->session->getSessionData("lang");
		if($this->data['attrs']['data'])
		{
			$menu = json_decode($this->data['attrs']['data'], true);
		}
		else
		{
			$this->CI->load->model('custom/custom_contact_model');
			$menu = $this->CI->custom_contact_model->getMenuList($lang, $this->data['attrs']['custom_field'],$this->data['attrs']['table'],$this->data['attrs']['core_field']);
		}
		
		$this->data['menu'] = $menu;
				
		if(strlen($this->data['attrs']['remove_options']) >= 1)
		{
		
			$remove_options = explode(",",$this->data['attrs']['remove_options']);
		
			foreach ($this->data['menu'] as $key=>$val)
				{
				foreach($remove_options as $opt)
					{
					if($val['ID'] == $opt)
						{
						unset($this->data['menu'][$key]);		
						}	
					}
				}
		}

    }
}
