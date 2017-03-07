<?php
namespace Custom\Models; 
use RightNow\Connect\v1_2 as RNCPHP;
//require_once( get_cfg_var( 'doc_root' ).'/include/ConnectPHP/Connect_init.phph' );
//initConnectAPI();
use RightNow\Utils\Connect,
    RightNow\Utils\Framework,
    RightNow\Utils\Text,
    RightNow\Utils\Config,
    RightNow\Api;

class CustomerFeedbackSystem extends \RightNow\Models\Base
{
    function __construct()
    {
        $this->IncidentSecurity();
        parent::__construct();
    }

    public function saveProfileData($formData){
       $data=$this->processFields($formData);
       $this->debug($data);
       exit;
    }

    private function debug($ao){
        echo "<pre>";
        print_r($ao);
        echo "</pre>";
    }

    private function processFields(array $fields, &$presentFields = array()) {
        $return = array();

        foreach ($fields as $field) {
            $fieldName = $field->name;

            if (!is_string($fieldName) || $fieldName === '') continue;

            unset($field->name);
            $return[$fieldName] = $field;

            if ($objectName = strtolower(Text::getSubstringBefore($fieldName, '.'))) {
                $presentFields[$objectName] = true;
            }
        }

        return $return;
    }


} //Class Ends here.