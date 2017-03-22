<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class TopicWords2 extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->attrs['label_title'] = new Attribute(getMessage(LABEL_LBL), 'STRING', getMessage(LABEL_TO_USE_FOR_HEADING_LBL), getMessage(RECOMMENDED_LINKS_LBL));
        $this->attrs['display_icon'] = new Attribute(getMessage(DISPLAY_ICON_CMD), 'BOOL', getMessage(DETS_ICON_DISPLAYED_LINK_ICON_MSG), true);
        $this->attrs['target'] = new Attribute(getMessage(TARGET_LBL), 'STRING', getMessage(CONTROLS_DOC_DISPLAYED_FOLLOWS_MSG), '_self');
        $this->attrs['add_params_to_url'] = new Attribute(getMessage(ADD_PRMS_TO_URL_CMD), 'STRING', getMessage(CMMA_SPRATED_L_URL_PARMS_LINKS_MSG), 'kw');
    }

    function generateWidgetInformation()
    {
        $this->parms['kw'] = new UrlParam(getMessage(KEYWORD_LBL), 'kw', false, getMessage(KEYWORD_SEARCH_TEXT_LBL), 'kw/search');
        $this->info['notes'] = getMessage(WIDGET_DISP_L_RECOMMENDED_ANS_DOCS_LBL);
    }

    function getData()
    {
        $this->data['appendedParameters'] = getUrlParametersFromList($this->data['attrs']['add_params_to_url']) . sessionParm();

        $this->CI->load->model('standard/CustomReport_model');
        $this->CI->load->model('standard/CustomReport_model');
        $this->data['topicWords'] = $this->CI->CustomReport_model->getTopicWords(getUrlParm('kw'));
		logmessage('new count is '.count($this->data['topicWords']));
        for($i = 0; $i < count($this->data['topicWords']); $i++)
        {
            if (!(isExternalUrl($this->data['topicWords'][$i]['url'])))
            {
                $this->data['topicWords'][$i]['url'] .= $this->data['appendedParameters'];
            }
        }
        if(!count($this->data['topicWords']))
            $this->data['hiddenClass'] = 'rn_Hidden';
    }
}
