<?php
namespace Custom\Widgets\CIHFunction;

class ProvinceSelector extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
		
		
        parent::__construct($attrs);
		
    }

    function getData() {
		
		if($this->data['attrs']['data'])
		{
			$menu = json_decode($this->data['attrs']['data'], true);
		}
		else
		{
			$this->CI->load->model('custom/ibase_product_model');
			$menu = $this->CI->ibase_product_model->getStateList('US');
		}
		
		$this->data['menu'] = $menu;

        return parent::getData();

    }

}