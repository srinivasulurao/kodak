<?php /* Originating Release: February 2012 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class IncidentThreadDisplay extends Widget
{
    function __construct()
    {
        parent::__construct();
        unset($this->attrs['left_justify']);
        $this->attrs['name'] = new Attribute(getMessage(NAME_LBL), 'STRING', getMessage(NAME_ATTRIB_INC_THREAD_INC_MSG), '');
        $this->attrs['name']->required = false;
        $this->attrs['thread_order'] = new Attribute(getMessage(DISPLAY_ORDER_CMD), 'OPTION', getMessage(DETERMINES_DISP_THREAD_POSTS_LBL), 'descending');
        $this->attrs['thread_order']->options = array('ascending', 'descending');
		$this->attrs['obj_id'] = new Attribute('Incident id','STRING','Incident id',getUrlParm('obj_id'));
    }

    function generateWidgetInformation()
    {

        $this->info['notes'] = getMessage(DSP_ENTRIES_INC_CORRESPONDENCE_UC_MSG);

    }

    function getData()
    {
		$this->CI = & get_instance();
		$this->CI->load->model('custom/custom_incident_model');
		$incInfo = $this->CI->custom_incident_model->getIncidentThreads($this->data['attrs']['obj_id']);
		$incThreads = $incInfo['threads'];
		
		$i = 0;

		while($i <count($incThreads))
		{
			if($incThreads[$i]->EntryType->ID == 4 || $incThreads[$i]->EntryType->ID == 3 || $incThreads[$i]->EntryType->ID == 2)
				$this->data['value'][] = array('content'=>$incThreads[$i]->Text,'type'=>$incThreads[$i]->EntryType->LookupName,'channel_label'=>$incThreads[$i]->Channel->LookupName,'time'=>date('d-M-Y H:i',$incThreads[$i]->CreatedTime),'name'=>$incThreads[$i]->Contact->LookupName);
			$i++;
		}
		
		
		if($this->data['value'] && $this->data['attrs']['thread_order'] === 'ascending')
        {
            $this->data['value'] = array_reverse($this->data['value'], true);
        }
    }
}
