<?php
namespace Custom\Widgets\reports;

class Grid2 extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {
        $this->data['attrs']['sanitize_data'] = $this->parseSanitizeDataToArray($this->data['attrs']['sanitize_data']);
        $internal = $this->CI->model('custom/custom_contact_model')->checkInternalContact($this->CI->session->getProfileData('c_id'));
        $this->data['internal_user'] = $internal;
         $format = array(
            'truncate_size' => $this->data['attrs']['truncate_size'],
            'max_wordbreak_trunc' => $this->data['attrs']['max_wordbreak_trunc'],
            'emphasisHighlight' => $this->data['attrs']['highlight'],
            'recordKeywordSearch' => true,
            'dateFormat' => $this->data['attrs']['date_format'],
            'urlParms' => getUrlParametersFromList($this->data['attrs']['add_params_to_url']),
        );
        if ($this->data['attrs']['tabindex'] !== '')
            $format['tabindex'] = $this->data['attrs']['tabindex'];
        
        $filters = array('recordKeywordSearch' => true);
        \RightNow\Utils\Url::setFiltersFromAttributesAndUrl($this->data['attrs'], $filters);
        if (!$this->helper('Social')->validateModerationMaxDateRangeInterval($this->data['attrs']['max_date_range_interval'])) {
            echo $this->reportError(Config::getMessage(MAX_FMT_YEAR_T_S_EX_90_S_5_YEAR_ETC_MSG));
            return false;
        }        
        $filters = $this->CI->model('Report')->cleanFilterValues($filters, $this->helper('Social')->getModerationDateRangeValidationFunctions($this->data['attrs']['max_date_range_interval']));
       
        $reportToken = createToken($this->data['attrs']['report_id']);
        
        $results = $this->CI->model('standard/Report')->getDataHTML($this->data['attrs']['report_id'], $reportToken, $filters, $format)->result;
        if ($results['error'] !== null)
        {
            echo $this->reportError($results['error']);
            return false;
        }
        $this->data['tableData'] = $results;
        $filters['page'] = $results['page'];
        if(count($this->data['tableData']['data']) === 0 && $this->data['attrs']['hide_when_no_results'])
        {
            $this->data['topLevelClass'] = ' rn_Hidden';
        }
        $this->data['js'] = array(
                             'filters' => $filters,
                             'colId' => $filters['sort_args']['filters']['col_id'],
                             'sortDirection' => $filters['sort_args']['filters']['sort_direction'],
                             'format' => $format,
                             'token' => $reportToken,
                             'headers' => $this->data['tableData']['headers'],
                             'row_num' => $this->data['tableData']['row_num'],
                             'searchName' => 'sort_args',
                             'dataTypes' => array('date' => VDT_DATE, 'datetime' => VDT_DATETIME, 'number' => VDT_INT)
                            );
        $this->data['js']['filters']['page'] = $results['page'];
        $this->data['attrs']['exclude_from_sorting'] = array_map('trim', explode(",", $this->data['attrs']['exclude_from_sorting']));
    }
      private function parseSanitizeDataToArray($data) {
        $returnData = array();
        if (is_string($data) && preg_match_all("/(\d)|([a-z\/-]+)/", $data, $matches)) {
            $length = count($matches[0]);
            for($i = 0; $i < $length; $i += 2) {
                $returnData[(int)$matches[0][$i]] = $matches[0][$i + 1];
            }
        }

        return $returnData;
    }

}