<?php /* Originating Release: February 2012 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');

class MyRelatedAnswers extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->attrs['limit'] = new Attribute(getMessage(NUMBER_LBL), 'INT', getMessage(MAXIMUM_ANS_DISPLAY_SET_0_LIMIT_MSG), 5);
        $this->attrs['limit']->min = 0;
        $this->attrs['target'] = new Attribute(getMessage(TARGET_LBL), 'STRING', getMessage(CONTROLS_DOC_DISPLAYED_FOLLOWS_MSG), '_self');
        $this->attrs['label_title'] = new Attribute(getMessage(LABEL_LBL), 'STRING', getMessage(LABEL_TO_USE_FOR_HEADING_LBL), getMessage(ANSWERS_OTHERS_FOUND_HELPFUL_LBL));
        $this->attrs['url'] = new Attribute(getMessage(URL_LBL), 'STRING', getMessage(URL_PG_REF_LINK_ACTIVATED_DEF_VAL_MSG), '/app/' . getConfig(CP_ANSWERS_DETAIL_URL));
        $this->attrs['highlight'] = new Attribute(getMessage(HIGHLIGHT_LBL), 'BOOL', getMessage(HIGHLIGHTS_TXT_FLDS_SRCH_TERM_LBL), true);
        $this->attrs['truncate_size'] = new Attribute(getMessage(TRUNCATE_SIZE_LBL), 'INT', getMessage(CHARS_LIMIT_ANS_LINK_SET_0_MSG), 0);
        $this->attrs['truncate_size']->min = 0;
        $this->attrs['relatedlinksonly'] = new Attribute('Related Links Only', 'BOOL', 'Related Links Only Returned when true', true);
        $this->attrs['add_params_to_url'] = new Attribute(getMessage(ADD_PRMS_TO_URL_CMD), 'STRING', getMessage(CMMA_SPRATED_L_URL_PARMS_LINKS_MSG), 'kw');
    }

    function generateWidgetInformation()
    {
        $this->parms['a_id'] = new UrlParam(getMessage(ANS_ID_LBL), 'a_id', true, getMessage(ANSWER_ID_CHECK_RELATED_ANSWERS_LBL), 'a_id/3');
        $this->parms['kw'] = new UrlParam(getMessage(KEYWORD_LBL), 'kw', false, getMessage(HIGHLIGHT_WORDS_ANSWERS_LINKS_MSG), 'kw/searchTerm');
        $this->info['notes'] = getMessage(WIDGET_DISP_L_REL_ANS_DETERMINED_KB_MSG);
    }

    function getData()
    {
        $this->data['appendedParameters'] = getUrlParametersFromList($this->data['attrs']['add_params_to_url']) . '/related/1' . sessionParm();
        $answerID = getUrlParm('a_id');
        if($answerID)
        {
            $this->CI->load->model('custom/related_model');
            $this->data['relatedAnswers'] = $this->CI->related_model->getMyRelatedAnswers($answerID, $this->data['attrs']['limit'], $this->data['attrs']['truncate_size'], $this->data['attrs']['relatedlinksonly']);
            if(is_array($this->data['relatedAnswers']) && count($this->data['relatedAnswers']) === 0)
                return false;
            if($this->data['attrs']['highlight'] && getUrlParm('kw'))
            {
                for($i=0; $i<count($this->data['relatedAnswers']); $i++)
                    $this->data['relatedAnswers'][$i][2] = emphasizeText($this->data['relatedAnswers'][$i][2]);
            }
        }
        else
        {
            return false;
        }
    }
}

