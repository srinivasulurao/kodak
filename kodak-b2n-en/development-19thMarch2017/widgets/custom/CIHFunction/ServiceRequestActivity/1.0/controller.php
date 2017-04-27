<?php
namespace Custom\Widgets\CIHFunction;

class ServiceRequestActivity extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);

    }

    function getData() {
        $customer_type = $this -> CI -> model('custom/custom_org_model') -> getDirectPartnerType($this->CI->session->getProfileData('org_id'));
        $enhanced_customer_type = $this -> CI -> model('custom/custom_org_model') -> getDirectPartnerTypeEnhanced($this->CI->session->getProfileData('org_id'));
        $this->data['customer_type'] = $customer_type;
		$this->data['js']['customer_type'] = $customer_type;
		$this->data['enhanced_customer_type'] = $enhanced_customer_type;
		$this->data['js']['enhanced_customer_type'] = $enhanced_customer_type;
		$this->data['js']['autosearch'] = true;
        $internal = $this->CI->model('custom/custom_contact_model')->checkInternalContact($this->CI->session->getProfileData('c_id'));
        $this->data['js']['internal_user'] = $internal;
        return parent::getData();

    }
}