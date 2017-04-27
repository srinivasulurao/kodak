<?php
namespace Custom\Widgets\CIHFunction;

class ManageContacts extends \RightNow\Libraries\Widget\Base {
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

        //$sesslang = &get_instance()->session->getSessionData("lang");
        $sesslang="en";
        switch ($sesslang) {
        case "en":
            $cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
            break;
        case "fr":
            $cih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 
            break;
        case "es":
            $cih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 
            break;
        case "pt":
            $cih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 
            break;
        default:
            $cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
            break;
        }                       
        $this->data['js']['loadingmessage'] = $cih_lang_msg_base_array['loadingmessage'];

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