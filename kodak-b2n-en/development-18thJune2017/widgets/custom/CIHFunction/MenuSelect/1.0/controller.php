<?php
namespace Custom\Widgets\CIHFunction;

class MenuSelect extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);

    }

    function getData() {
        $lang = get_instance()->session->getSessionData("lang");
		
		if($this->data['attrs']['data'])
		{
			$menu = json_decode($this->data['attrs']['data'], true);
		}
		else
		{
			$menu = $this->CI->model('custom/custom_contact_model')->getMenuList($lang, $this->data['attrs']['custom_field'],$this->data['attrs']['table'],$this->data['attrs']['core_field']);
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
		
        return parent::getData();

    }

}