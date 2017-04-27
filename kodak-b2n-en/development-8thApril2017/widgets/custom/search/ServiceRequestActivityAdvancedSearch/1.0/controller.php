<?php
namespace Custom\Widgets\search;

class ServiceRequestActivityAdvancedSearch extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
       
        $this->data['thefilters'] = $this->CI->model('standard/Report')->getRuntimeFilters($this->data['attrs']['report_id']);
		
		$this->data['js']['rnSearchType'] = 'custom';
		$this->data['js']['searchName'] = 'custom';
        $country_list = $this->CI->model('custom/custom_report_model')->getReportDataByID(100818);
        $this->data['country_list'] = $country_list;
        $internal = $this->CI->model('custom/custom_contact_model')->checkInternalContact($this->CI->session->getProfileData('c_id'));
        $this->data['js']['internal_user'] = $internal;
        return parent::getData();

    }
}