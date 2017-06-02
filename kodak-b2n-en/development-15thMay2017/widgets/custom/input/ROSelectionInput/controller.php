<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

if(!class_exists('FormInput'))
    requireWidgetController('standard/input/ROFormInput');

class ROSelectionInput extends FormInput
{
    function __construct()
    {
        parent::__construct();
        //this FormInput attr doesn't apply to SelectionInput
        unset($this->attrs['always_show_mask']);
    }

    function generateWidgetInformation()
    {
        parent::generateWidgetInformation();
        $this->info['notes'] = getMessage(WDGT_ALLWS_USERS_SET_FLD_VALS_DB_MSG);    
    }

    function getData()
    {
        if(parent::retrieveAndInitializeData() === false)
            return false;

        //Status field should not be shown if there is not an incident ID on the page
        if($this->fieldName === 'status' && !getUrlParm('i_id'))
        {
            echo $this->reportError(sprintf(getMessage(PCT_S_FLD_DISPLAYED_PG_I_ID_PARAM_MSG), 'incidents.status'));
            return false;
        }

        if($this->field->data_type !== EUF_DT_SELECT && $this->field->data_type !== EUF_DT_CHECK && $this->field->data_type !== EUF_DT_RADIO)
        {
            echo $this->reportError(sprintf(getMessage(PCT_S_MENU_YES_SLASH_FIELD_MSG), $this->fieldName));
            return false;
        }

        //standard field
        if(!($this->field instanceof CustomField))
        {
             if(($this->CI->meta['sla_failed_page'] || $this->CI->meta['sla_required_type']) && $this->fieldName === 'sla' && count($this->field->menu_items))
                 $this->data['hideEmptyOption'] = true;
             if($this->field->data_type === EUF_DT_CHECK)
             {
                 $this->data['menuItems'] = array(getMessage(YES_PLEASE_RESPOND_TO_MY_QUESTION_MSG), getMessage(I_DONT_QUESTION_ANSWERED_LBL));
                 $this->data['hideEmptyOption'] = true;
             }
        }
        if($this->field->data_type === EUF_DT_RADIO)
        {
            $this->data['radioLabel'] = array(getMessage(NO_LBL), getMessage(YES_LBL));
            //find the index of the checked value
            if(is_null($this->data['value']))
                $this->data['checkedIndex'] = -1;
            elseif(intval($this->data['value']) === 1)
                $this->data['checkedIndex'] = 1;
            else
                $this->data['checkedIndex'] = 0;
        }
        $this->data['showAriaHint'] = $this->CI->clientLoader->getCanUseAria() && $this->data['js']['hint'];
    }
}
