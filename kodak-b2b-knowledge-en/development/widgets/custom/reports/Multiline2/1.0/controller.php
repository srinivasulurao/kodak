<?php
namespace Custom\Widgets\reports;

class Multiline2 extends \RightNow\Widgets\Multiline{
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
        
        $format = array(
            'truncate_size' => $this->data['attrs']['truncate_size'],
            'max_wordbreak_trunc' => $this->data['attrs']['max_wordbreak_trunc'],
            'emphasisHighlight' => $this->data['attrs']['highlight'],
            'dateFormat' => $this->data['attrs']['date_format'],
            'urlParms' => \RightNow\Utils\Url::getParametersFromList($this->data['attrs']['add_params_to_url']),
        );
        $filters = array('recordKeywordSearch' => true);
        

        $reportToken = \RightNow\Utils\Framework::createToken($this->data['attrs']['report_id']);

        \RightNow\Utils\Url::setFiltersFromAttributesAndUrl($this->data['attrs'], $filters);
       
        
        
        //$filters['per_page']=$filters[per_page]-1;
        $results = $this->CI->model('Report')->getDataHTML($this->data['attrs']['report_id'], $reportToken, $filters, $format)->result;

        

        if ($results['error'] !== null) {
            echo $this->reportError($results['error']);
        }

        
        $this->data['reportData'] = $results;
        
        // echo "<pre>";
        // print_r( $this->data['reportData']);
        // echo "</pre>";
        // exit;
        if($this->data['attrs']['hide_when_no_results'] && count($this->data['reportData']['data']) === 0) {
            $this->classList->add('rn_Hidden');
        }
        $this->data['js'] = array(
            'filters' => $filters,
            'format' => $format,
            'r_tok' => $reportToken,
            'error' => $results['error']
        );
        $this->data['js']['filters']['page'] = $results['page'];

        
        
        //Fields to hide
        $this->data['js']['hide_columns'] = array_map('trim', explode(",", $this->data['attrs']['hide_columns']));
    }

    

  
    
}