<?php
namespace Custom\Widgets\CIHFunction;

class TextAreaInput extends \RightNow\Libraries\Widget\Base {
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
		
		$this->data['js']['value'] = $this->data['attrs']['value'];

        return parent::getData();

    }
	
	/*
    * Generate widget information
    */
    public function generateWidgetInformation()
    {
        $this->info['form']  = FORM;
        $this->info['type']  = 'Mixed';
        $this->info['notes'] = 'Text input allows you to submit values that are not tied to the database to a submission controller.';
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