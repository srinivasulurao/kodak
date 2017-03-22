<?php /* Originating Release: February 2012 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');

class AnswerFeedback2 extends Widget
{
    function __construct()
    {
        parent::__construct();

        $this->attrs['label_title'] = new Attribute(getMessage(HEADER_LABEL_LBL), 'STRING', getMessage(LABEL_TO_DISPLAY_ABOVE_FORM_LBL), getMessage(WAS_THIS_ANSWER_HELPFUL_MSG));
        $this->attrs['label_accessible_option_description'] = new Attribute(getMessage(ACCESSIBLE_OPTION_DESCRIPTION_LBL), 'STRING', getMessage(DESC_OPTION_SCALE_SYS_OPTS_CNT_2_MSG), getMessage(RATE_ANSWER_PCT_D_OF_PCT_D_LBL));
        $this->attrs['label_dialog_title'] = new Attribute(getMessage(DIALOG_TITLE_LBL), 'STRING', getMessage(LABEL_DISPLAY_DIALOG_TITLE_LBL), getMessage(RATING_SUBMITTED_LBL));
        $this->attrs['label_dialog_description'] = new Attribute(getMessage(DIALOG_DESCRIPTION_LBL), 'STRING', getMessage(LABEL_DISPLAY_DIALOG_DESCRIPTION_LBL), getMessage(PLEASE_TELL_ANSWER_USEFUL_MSG));
        $this->attrs['dialog_width'] = new Attribute(getMessage(DIALOG_WIDTH_LBL), 'STRING', getMessage(THE_DESIRED_WIDTH_OF_THE_DIALOG_LBL), "auto");
        $this->attrs['options_count'] = new Attribute(getMessage(NUM_OPTIONS_LBL), 'INT', getMessage(NUMBER_OF_OPTIONS_TO_DISPLAY_LBL), 2);
        $this->attrs['options_count']->min = 2;
        $this->attrs['options_count']->max = 5;
        $this->attrs['options_descending'] = new Attribute(getMessage(OPTIONS_DESCENDING_LBL), 'BOOL', getMessage(SET_TRUE_OPTIONS_DESCENDING_ORDER_MSG), false);
        $this->attrs['label_yes_button'] = new Attribute(getMessage(LABEL_FOR_YES_BUTTON_LBL), 'STRING', getMessage(LABEL_FOR_YES_BUTTON_LBL), getMessage(YES_LBL));
        $this->attrs['label_no_button'] = new Attribute(getMessage(LABEL_FOR_NO_BUTTON_LBL), 'STRING', getMessage(LABEL_FOR_NO_BUTTON_LBL), getMessage(NO_LBL));
        $this->attrs['feedback_page_url'] = new Attribute(getMessage(FEEDBACK_URL_LBL), 'STRING', getMessage(PG_URL_STD_DIALOG_RATING_LVL_FALLS_MSG), '');
        $this->attrs['dialog_threshold'] = new Attribute(getMessage(THRESHOLD_LBL), 'INT', getMessage(RATING_LVL_ADDTL_FORM_DISP_ADDTL_MSG), 1);
        $this->attrs['dialog_threshold']->min = 0;
        $this->attrs['label_feedback_submit'] = new Attribute(getMessage(SUBMIT_FEEDBACK_CMD), 'STRING', getMessage(LABEL_DISPLAY_RATING_SCALE_RATING_LBL), getMessage(THANKS_FOR_YOUR_FEEDBACK_MSG));
        $this->attrs['label_email_address'] = new Attribute(getMessage(EMAIL_ADDRESS_LABEL_LBL), 'STRING', getMessage(LABEL_FOR_EMAIL_ADDRESS_MSG), getMessage(EMAIL_LBL));
        $this->attrs['label_comment_box'] = new Attribute(getMessage(FEEDBACK_LABEL_LBL), 'STRING', getMessage(LABEL_FOR_FEEDBACK_TEXTBOX_MSG), getMessage(YOUR_FEEDBACK_HDG));
        $this->attrs['label_send_button'] = new Attribute(getMessage(LABEL_SEND_BUTTON_LBL), 'STRING', getMessage(LABEL_TO_DISPLAY_ON_SUBMIT_BUTTON_LBL), getMessage(SUBMIT_CMD));
        $this->attrs['label_cancel_button'] = new Attribute(getMessage(LABEL_CANCEL_BUTTON_LBL), 'STRING', getMessage(LABEL_TO_DISPLAY_ON_CANCEL_BUTTON_LBL), getMessage(CANCEL_LBL));
        $this->attrs['use_rank_labels'] = new Attribute(getMessage(USE_RANK_LABELS_LBL), 'BOOL', getMessage(SET_TRUE_RANK_X_LBL_LABELS_X_0_25_MSG), false);
    }

    function generateWidgetInformation()
    {
        $this->parms['a_id'] = new UrlParam(getMessage(ANS_ID_LBL), 'a_id', false, getMessage(ANSWER_ID_WHICH_ASSOCIATE_FEEDBACK_LBL), 'a_id/3');
        $this->info['notes'] = getMessage(WD_DISP_ANS_FB_CONTR_EU_RAT_MSG);
    }

    function getData()
    {
        $this->data['js']['buttonView'] = ($this->data['attrs']['options_count'] === 2) ? true : false;
        $this->data['rateLabels'] = $this->getRateLabels();
        
        $answerID = getUrlParm('a_id');
        $this->CI->load->model('standard/Contact_model');
        if($answerID)
        {
            $this->CI->load->model('standard/Answer_model');
            $answerData = $this->CI->Answer_model->get($answerID);
            $this->data['js']['summary'] = $answerData->summary->value;
            $this->data['js']['answerID'] = $answerID;
        }
        else
        {
            $this->data['js']['summary'] = getMessage(SITE_FEEDBACK_HDG);
            $this->data['js']['answerID'] = null;
        }

        $profile = $this->CI->session->getProfile();
        $this->data['js']['isProfile'] = false;
        $this->data['js']['email'] = '';
        if($profile !== null)
        {
            $contactsStruct = $this->CI->Contact_model->get($profile->c_id->value);
            $this->data['js']['email'] = $contactsStruct->email->value;
            $this->data['js']['isProfile'] = true;
        }
        else if($this->CI->session->getSessionData('previouslySeenEmail'))
        {
            $this->data['js']['email'] = $this->CI->session->getSessionData('previouslySeenEmail');
        }
    }

    private function getRateLabels() {
        switch($this->data['attrs']['options_count'])
        {
            case 3:
                return array(null, RANK_0_LBL, RANK_50_LBL, RANK_100_LBL);
            case 4:
                return array(null, RANK_0_LBL, RANK_25_LBL, RANK_75_LBL, RANK_100_LBL);
            case 5:
                return array(null, RANK_0_LBL, RANK_25_LBL, RANK_50_LBL, RANK_75_LBL, RANK_100_LBL);
            default:
                return array();
        }
    }
}
