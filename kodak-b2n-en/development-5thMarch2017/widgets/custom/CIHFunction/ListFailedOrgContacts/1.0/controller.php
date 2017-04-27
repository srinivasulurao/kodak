<?php
namespace Custom\Widgets\CIHFunction;

class ListFailedOrgContacts extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

        $this->CI->load->model('custom/custom_contact_model');
        $contacts = $this->CI->custom_contact_model->getPPErrorOrgContacts();

        $this->data['contacts'] = $contacts;

        return parent::getData();

    }
}