<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Multiline2 extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->attrs['per_page'] = new Attribute(getMessage(ITEMS_PER_PAGE_LBL), 'INT', getMessage(CTRLS_RES_DISP_PG_OVERRIDDEN_MSG), 0);
        $this->attrs['report_id'] = new Attribute(getMessage(REPORT_ID_LBL), 'INT', getMessage(ID_RPT_DISP_DATA_SEARCH_RESULTS_MSG), CP_NOV09_ANSWERS_DEFAULT);
        $this->attrs['report_id']->min = 1;
        $this->attrs['report_id']->optlistId = OPTL_CURR_INTF_PUBLIC_REPORTS;
        $this->attrs['truncate_size'] = new Attribute(getMessage(WRAP_SIZE_LBL), 'INT', getMessage(NUMMBER_CHARACTERS_TRUNCATE_FIELD_LBL), 200);
        $this->attrs['truncate_size']->min = 1;
        $this->attrs['highlight'] = new Attribute(getMessage(HIGHLIGHTING_LBL), 'BOOL', getMessage(HIGHLIGHTS_TXT_FLDS_SRCH_TERM_LBL), true);
        $this->attrs['label_screen_reader_search_success_alert'] = new Attribute(getMessage(SCREEN_READER_SEARCH_SUCCESS_ALERT_LBL), 'STRING', getMessage(MSG_ANNOUNCD_SCREEN_READER_USERS_MSG), getMessage(YOUR_SEARCH_IS_COMPLETE_MSG));
        $this->attrs['label_screen_reader_search_no_results_alert'] = new Attribute(getMessage(SCREEN_READER_SEARCH_RESULTS_ALERT_LBL), 'STRING', getMessage(MSG_ANNOUNCED_SCREEN_READER_USERS_MSG), getMessage(YOUR_SEARCH_RETURNED_NO_RESULTS_LBL));
        $this->attrs['add_params_to_url'] = new Attribute(getMessage(ADD_PRMS_TO_URL_CMD), 'STRING', getMessage(COMMA_SEPARATED_L_URL_PARMS_LINKS_MSG), 'kw');
        $this->attrs['hide_when_no_results'] = new Attribute(getMessage(HIDE_WHEN_NO_RESULTS_CMD), 'BOOL', getMessage(HIDES_ENTIRE_WIDGET_CONTENT_CSS_RES_MSG), false);
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] =  getMessage(WIDGET_DISP_DATA_MULTILINE_FMT_MSG);
        $this->parms['kw'] = new UrlParam(getMessage(KEYWORD_LBL), 'kw', false, getMessage(THE_CURRENT_SEARCH_TERM_LBL), 'kw/search');
        $this->parms['r_id'] = new UrlParam(getMessage(REPORT_ID_LBL), 'r_id', false, getMessage(THE_REPORT_ID_TO_APPLY_FILTERS_TO_LBL), 'r_id/' . CP_REPORT_DEFAULT);
        $this->parms['st'] = new UrlParam(getMessage(SEARCH_TYPE_LBL), 'st', false, getMessage(SETS_SEARCH_TYPE_URL_PARAM_VALUE_LBL), 'st/6');
        $this->parms['org'] = new UrlParam(getMessage(ORGANIZATION_TYPE_LBL), 'org', false, getMessage(SETS_ORG_TYPE_URL_PARAMETER_VALUE_LBL), 'org/2');
        $this->parms['page'] = new UrlParam(getMessage(PAGE_LBL), 'page', false, getMessage(SETS_SELECT_PAGE_URL_PARAMETER_LBL), 'page/2');
        $this->parms['search'] = new UrlParam(getMessage(SEARCH_LBL), 'search', false, getMessage(KEY_DENOTING_SEARCH_PERFORMED_MSG), 'search/0');
        $this->parms['sort'] = new UrlParam(getMessage(SORT_BY_LBL), 'sort', false, getMessage(SETS_SORT_COL_VAL_DIRECTION_COL_1_LBL), 'sort/3,1');
    }

    function getData()
    {
        $this->CI->load->model('standard/Report_model');
        if ($this->data['attrs']['per_page'] > 0)
            $filters['per_page'] = $this->data['attrs']['per_page'];
        if ($this->data['attrs']['tabindex'] !== '')
            $format['tabindex'] = $this->data['attrs']['tabindex'];
        $format['truncate_size'] = $this->data['attrs']['truncate_size'];
        $format['emphasisHighlight'] = $this->data['attrs']['highlight'];
        $filters['recordKeywordSearch'] = true;
        $format['urlParms'] = getUrlParametersFromList($this->data['attrs']['add_params_to_url']);
        setFiltersFromUrl($this->data['attrs']['report_id'], $filters);
        $reportToken = createToken($this->data['attrs']['report_id']);
        $results = $this->CI->Report_model->getDataHTML($this->data['attrs']['report_id'], $reportToken, $filters, $format);
        if ($results['error'] !== null)
        {
            echo $this->reportError($results['error']);
            return false;
        }
        $this->data['tableData'] = $results;
        if(count($this->data['tableData']['data']) === 0 && $this->data['attrs']['hide_when_no_results'])
        {
            $this->data['topLevelClass'] = ' rn_Hidden';
        }
        $this->data['js'] = array('filters' => $filters,
                                  'format' => $format,
                                  'r_tok' => $reportToken
                                  );
        $this->data['js']['filters']['page'] = $results['page'];
    }
}
