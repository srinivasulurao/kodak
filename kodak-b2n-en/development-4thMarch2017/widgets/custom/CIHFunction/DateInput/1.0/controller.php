<?php
namespace Custom\Widgets\CIHFunction;

class DateInput extends \RightNow\Libraries\Widget\Base {
    function __construct($attrs) {
        parent::__construct($attrs);
        unset($this->attrs['always_show_mask']);
         
        try{
           $maxYear = getMaxYear();
        }
        catch (Exception $e){
            echo $this->reportError($e->getMessage());
            $maxYear = date('Y');
        }
        
        $this->attrs['max_year']->default=$maxYear;
        $this->attrs['max_year']->min=getMinYear();
        $this->attrs['min_year']->default=getMinYear();
        $this->attrs['min_year']->min=getMinYear();
        //$this->attrs['data_type']=$this->field->data_type;
    }

    function getData() {
        $minYear = $this->data['minYear'] = $this->data['js']['minYear'] = $this->data['attrs']['min_year']= $this->attrs['min_year']->default;
        $this->data['maxYear'] =$this->attrs['max_year']->default;
        $dateOrder = getConfig(DTF_INPUT_DATE_ORDER, 'COMMON');

        $this->data['dayLabel'] = getMessage(DAY_LBL, 'COMMON');
        $this->data['monthLabel'] = getMessage(MONTH_LBL, 'COMMON');
        $this->data['yearLabel'] = getMessage(YEAR_LBL, 'COMMON');
        $this->data['hourLabel'] = getMessage(HOUR_LBL, 'COMMON');
        $this->data['minuteLabel'] = getMessage(MINUTE_LBL, 'COMMON');
		$this->data['js']['name'] = $this->data['attrs']['name'];
        
         if ($dateOrder == 0)
        {
            $this->data['monthOrder'] = 0;
            $this->data['dayOrder'] = 1;
            $this->data['yearOrder'] = 2;
            $this->data['js']['min_val'] = "1/2/$minYear";
        }
         else if ($dateOrder == 1)
        {
            $this->data['monthOrder'] = 1;
            $this->data['dayOrder'] = 2;
            $this->data['yearOrder'] = 0;
       //     if ($this->field->data_type === EUF_DT_DATETIME)
                $this->data['js']['min_val'] = sprintf("{$minYear}%s/1%s/2%s 09:00", $this->data['yearLabel'], $this->data['monthLabel'], $this->data['dayLabel']);
          //  else
          //      $this->data['js']['min_val'] = sprintf("{$minYear}%s/1%s/2%s", $this->data['yearLabel'], $this->data['monthLabel'], $this->data['dayLabel']);
        }
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
        return parent::getData();

    }

  
}