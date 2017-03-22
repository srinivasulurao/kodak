<?php /* Originating Release: February 2012 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');


class DateInput extends Widget
{
    function __construct()
    {
        parent::__construct();
        //this FormInput attr doesn't apply to DateInput
        unset($this->attrs['always_show_mask']);
        
        try{
           $maxYear = getMaxYear();
        }
        catch (Exception $e){
            echo $this->reportError($e->getMessage());
            $maxYear = date('Y');
        }
		$this->attrs['name'] = new Attribute('Field name','STRING','Name of the field','');
        $this->attrs['max_year'] = new Attribute(getMessage(MAXIMUM_YEAR_LBL), 'INT', getMessage(MAX_DISPLAYED_DATE_FLD_DEFS_VAL_EU_LBL), $maxYear);
        $this->attrs['max_year']->min = getMinYear();
        $this->attrs['max_year']->max = 2100;
        $this->attrs['min_year'] = new Attribute(getMessage(MINIMUM_YEAR_LBL), 'INT', getMessage(MINIMUM_YEAR_DISPLAYED_DATE_FIELD_MSG), getMinYear());
        $this->attrs['min_year']->min = getMinYear();
        $this->attrs['min_year']->max = 2100;
        $this->attrs['hide_hint'] = new Attribute(getMessage(HIDE_HINT_CMD), 'BOOL', getMessage(SPECIFIES_HINTS_HIDDEN_DISPLAYED_MSG), false);

    }

    function generateWidgetInformation()
    {
        //parent::generateWidgetInformation();
        $this->info['notes'] =  getMessage(WDGT_ALLOWS_USERS_SET_FLD_VALS_DB_MSG);
    }

    function getData()
    {
        /*
		if(parent::retrieveAndInitializeData() === false)
            return false;

        if($this->field->data_type !== EUF_DT_DATE && $this->field->data_type !== EUF_DT_DATETIME)
        {
            echo $this->reportError(sprintf(getMessage(PCT_S_DATE_DATE_SLASH_TIME_FIELD_MSG), $this->fieldName));
            return false;
        }
*/
        $minYear = $this->data['minYear'] = $this->data['js']['minYear'] = $this->data['attrs']['min_year'];
        $this->data['maxYear'] = $this->data['attrs']['max_year'];

        $dateOrder = getConfig(DTF_INPUT_DATE_ORDER, 'COMMON');

        $this->data['dayLabel'] = getMessage(DAY_LBL, 'COMMON');
        $this->data['monthLabel'] = getMessage(MONTH_LBL, 'COMMON');
        $this->data['yearLabel'] = getMessage(YEAR_LBL, 'COMMON');
        $this->data['hourLabel'] = getMessage(HOUR_LBL, 'COMMON');
        $this->data['minuteLabel'] = getMessage(MINUTE_LBL, 'COMMON');
		
		$this->data['js']['name'] = $this->data['attrs']['name'];

        //mm/dd/yyyy
        if ($dateOrder == 0)
        {
            $this->data['monthOrder'] = 0;
            $this->data['dayOrder'] = 1;
            $this->data['yearOrder'] = 2;
            $this->data['js']['min_val'] = "1/2/$minYear";
        }
        //yyyy/mm/dd
        else if ($dateOrder == 1)
        {
            $this->data['monthOrder'] = 1;
            $this->data['dayOrder'] = 2;
            $this->data['yearOrder'] = 0;
            if ($this->field->data_type === EUF_DT_DATETIME)
                $this->data['js']['min_val'] = sprintf("{$minYear}%s/1%s/2%s 09:00", $this->data['yearLabel'], $this->data['monthLabel'], $this->data['dayLabel']);
            else
                $this->data['js']['min_val'] = sprintf("{$minYear}%s/1%s/2%s", $this->data['yearLabel'], $this->data['monthLabel'], $this->data['dayLabel']);
        }
        //dd/mm/yyyy
        else
        {
            $this->data['monthOrder'] = 1;
            $this->data['dayOrder'] = 0;
            $this->data['yearOrder'] = 2;

                $this->data['js']['min_val'] = "2/1/$minYear";
        }
        if($this->data['value'])
        {
            $this->data['value'] = explode(' ', date('m j Y G i', intval($this->data['value'])));
            $this->data['defaultValue'] = true;
        }

    }
}
