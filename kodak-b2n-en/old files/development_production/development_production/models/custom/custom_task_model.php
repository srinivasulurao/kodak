<?php

use RightNow\Connect\v1 as RNCPHP;

require_once( get_cfg_var("doc_root")."/include/ConnectPHP/Connect_init.phph" );

class custom_task_model extends Model
{
	private $cacheHandlePrefix = 'task';
	
    function __construct()
    {
        parent::__construct();
		
		$CI = &get_instance();
		
		$this->CI = $CI;

    }
	
	function createincidenttask($values)
	{
		$c_id = $values['c_id'];
		
		$task = new RNCPHP\Task;
		
		//$task->TaskType = new RNCPHP\NamedIDOptList();
		//$task->TaskType->LookupName = 'incident task';
		
		$task->StatusWithType->Status = new RNCPHP\NamedIDOptList();
		$task->StatusWithType->Status->LookupName = 'Not Started';
		
		$task->Name = 'Ibase Update Task';
		
		$task->ServiceSettings = new RNCPHP\TaskServiceSettings();
                logmessage("createincidenttask: i_id = " . $values['i_id']);
		$task->ServiceSettings->Incident = RNCPHP\Incident::fetch(intval($values['i_id']));
	
		$task->CustomFields->ek_work_state = new RNCPHP\NamedIDLabel();
		$task->CustomFields->ek_work_state->LookupName = $values['ek_work_state'];
		
		$task->CustomFields->ek_category = new RNCPHP\NamedIDLabel();
		$task->CustomFields->ek_category->LookupName = $values['ek_category'];
		
		$task->Notes = new RNCPHP\NoteArray();
		$task->Notes[0] = new RNCPHP\Note();
		$task->Notes[0]->Text = urldecode($values['note']);

		$task->save(RNCPHP\RNObject::SuppressAll);
		
	}
}
