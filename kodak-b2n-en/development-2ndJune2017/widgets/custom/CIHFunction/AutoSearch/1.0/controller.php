<?php
namespace Custom\Widgets\CIHFunction;

class AutoSearch extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
        $enhanced_customer_type = $this->CI->model("custom/custom_org_model")->getDirectPartnerTypeEnhanced($this->CI->session->getProfileData('org_id'));
        $this->data['enhanced_customer_type'] = $enhanced_customer_type;
		$this->data['js']['enhanced_customer_type'] = $enhanced_customer_type;
        return parent::getData();

    }
}