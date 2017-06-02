<?php
namespace Custom\Widgets\CIHFunction;

class PartnerTypeList extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);

        $this->setAjaxHandlers(array(
            'default_ajax_endpoint' => array(
                'method'      => 'handle_default_ajax_endpoint',
                'clickstream' => 'custom_action',
            ),
        ));
    }

    function getData() {

        $this->CI->load->model('custom/custom_org_model');
        $org_id = $this->CI->session->getProfileData('org_id');
        if($org_id < 0) $org_id = 0;

        $partnerTypes = $this->CI->custom_org_model->getPartnerTypeList($org_id);

        $this->data['partnertypes'] = $partnerTypes;
        if($this->data['attrs']['selected_value'] > 0)
        {
            $this->data['js']['defaultFilter'] = $this->data['attrs']['selected_value'];
        }
        else
        {
            $i = 0;


            while ($i < count($partnerTypes))
            {
                if($partnerTypes[$i]['selected'] == 'selected')
                {
                    //$this->data['js']['defaultFilter'] =$partnerTypes[$i]['ID'];
                    $this->data['js']['defaultFilter'] =$partnerTypes[$i]['Type'];
                    break;
                }
                if($partnerTypes[$i]['selected'] == 'multiple')
                {
                    //$this->data['js']['defaultFilter'] = 0;
                    $this->data['js']['defaultFilter'] = '--';
                    break;
                }
                                $i++;  //2012.10.11 scott harris: this loop was never ending
                
            }

        }
        

        return parent::getData();

    }

    /**
     * Handles the default_ajax_endpoint AJAX request
     * @param array $params Get / Post parameters
     */
    function handle_default_ajax_endpoint($params) {
        // Perform AJAX-handling here...
        // echo response
    }
}