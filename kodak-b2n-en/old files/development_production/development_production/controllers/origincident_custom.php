<?
use RightNow\Connect\v1 as RNCPHP;

class incident_custom extends ControllerBase
{
	private $_form;
	private $_task_category;

	/*
	* Constructor
	*/
	public function __construct()
	{
		parent::__construct();

		$CI =& get_instance();
		
		$this->CI = $CI;
		$this->CI->load->model('custom/custom_incident_model');
                $this->CI->load->model('standard/contact_model');
		$this->CI->load->model('custom/custom_task_model');

		require_once(get_cfg_var('doc_root') . '/include/ConnectPHP/Connect_init.phph');
		
		initConnectAPI();

	}
	

	/*
	* Answer review submission controller
	*/
	public function incident_submit()
	{
		$this->_instantiateForm();
				
		$c_id = $this->input->post('c_id') ||  $this->_form['c_id'];

		$result = array();
		
		if($this->_form['panel'] == 'ibaseupdate')
		{
			$result['dialog_title'] = 'Ibase Update';
			//$success_message = "Ibase Update request submitted successfully";
		}
		if($this->_form['panel'] == 'repairrequest')
		{
			$result['dialog_title'] = 'Repair Request';
			//$success_message = "Repair Request created successfully";
		}
		

		
		
		//Check to see what panel submitted
		try
		{

                   //2013.03.27 scott harris: check for existing contact before continuing
                   if(!$c_id) {

                     if($this->CI->contact_model->lookupContactByEmail($this->_form['emailaddress'])) {
                       $error = "An account with this email already exists.";
                       throw new Exception($error);
                     }
                   }


                        $nl = "&#60;&#47;br&#62;";
			$refno = $this->_createIncident();

                        $result['message'] = $success_message = sprintf(getMessage(QUESTION_SUBMITTED_MSG), $refno);

/*
                        $result['message'] = $success_message = sprintf("%s # %s %s %s %s", getMessage(QUESTION_SUBMITTED_HDG),
                                                                       $refno,
                                                                       getMessage(SUBMITTING_QUEST_REFERENCE_FOLLOW_LBL),
                                                                       getMessage(SUPPORT_TEAM_SOON_MSG),
                                                                       getMessage(UPD_QUEST_CLICK_ACCT_TAB_SEL_QUEST_MSG));
*/
		
		}
		catch(Exception $e)
		{
			$result['status']  = 0;
			$result['message'] = 'Error: ' . $e->getMessage();
			$result['form_values'] = $this->_form;
			print(json_encode($result));
			return;
		}
		
		
		$result['status']  = 1;
		$result['form_values'] = $this->_form;
		$result['c_id'] = $c_id; 
logmessage("incident_submit 6:".json_encode($result));
		print(json_encode($result));
		return;			
	}
	
	/*
	* Add thread	
	*/
	
	public function add_thread(){
		
		$this->_instantiateForm();
		$i_id = $this->input->post('obj_id') ||  $this->_form['obj_id'];
		
		$result['dialog_title'] = 'Service Request Update';
		
		$this->_addThread();
		
		$result['status']  = 1;
		$result['message'] = 'The Service Request was updated successfully';
		$result['form_values'] = $this->_form;
		print(json_encode($result));
		return;
	}

	/*
	* Instantiate the easy-to-use form property.
	*/
	private function _instantiateForm()
	{

		$form = $this->CI->input->post('form');

		if (is_string($form))
			$form = json_decode($form);

		if (is_array($form))
			foreach ($form as $field)
				$this->_form[$field->name] = $field->value;

	}

	
	/*
	* Create Incident
	*/
	
	private function _createIncident()
	{

		$orgID = $this->CI->session->getProfileData('org_id');
		$managed_cid = $this->CI->session->getProfileData('c_id');
		$this->_form['managed_cid'] = $managed_cid;
		$this->_form['org_id'] = $orgID;

		
		if($this->_form['panel'] == 'ibaseupdate');
			$this->_task_category = 'Ibase Request';
			$thread = $this->_getIBaseThread();
		
		//$i_id = $this->CI->custom_incident_model->createIncident($this->_form,$thread);
		$result = $this->CI->custom_incident_model->createIncident($this->_form,$thread);

		if($this->_form['panel'] == 'ibaseupdate') {  //2012.07.12 scott harris: added to avoid task when repair request
		  $task_data = array();
		  $task_data['i_id'] = $result['i_id'];  // $i_id;
		  $task_data['ek_work_state'] = 'Not Started';
		  $task_data['ek_category'] = $this->_task_category;
		  $task_data['note'] = $thread;
		
		  $this->CI->custom_task_model->createincidenttask($task_data);
	        }	
                return $result['refno'];
	}
	
	private function _addThread(){
		
		$this->CI->custom_incident_model->addThread($this->_form,$this->_form['thread']);
		
	}

	private function _getIBaseThread(){
		$thread_text;

		switch($this->_form['ek_ibase_updt_type'])
		{
			case "460":
				$thread_text = sprintf("Component Id: %s\nIbase Update Type: Remove Product(s)\nReason for Removal:%s\nEffective Date:%s\nAdditional Comments:%s\n",$this->_form['ek_equip_component_id'],$this->_form['removal_reason'],$this->_form['effective_date'],$this->_form['thread']);
				break;
			case "461":
				$thread_text = sprintf("Component Id: %s\nIbase Update Type: Move Product(s)\nProduct Identifier (K#):%s\nContact Name:%s\nContact Phone:%s\nStore #:%s\nCustomer Name:%s\nIBase Address: %s\nStreet Address 1:%s\nCity:%s\nState/Province:%s\nPostal Code/Zip:%s\nCountry:%s\nEquipment Location:%s\nChange Effective On:%s\nAdditional Comments:%s\n",
                                $this->_form['ek_equip_component_id'],
				$this->_form['product_identifier'],
				$this->_form['ibase_firstname'].' '.$this->_form['ibase_lastname'],
				$this->_form['ibase_phone'],
				$this->_form['storenumber'],
				$this->_form['sitecustomername'],
                                $this->_form['ibase_address'],
				$this->_form['street'],
				$this->_form['city'],
				$this->_form['state'],
				$this->_form['zipcode'],
				$this->_form['ibase_country'],	
				$this->_form['equipment_location'],
				$this->_form['effective_date'],
				$this->_form['thread']);

				break;
			case "462":
				$thread_text = sprintf("Component Id: %s\nIbase Update Type: Entitlement Change\nEntitlement Type:%s\nAdditional Comments:%s\n",$this->_form['ek_equip_component_id'],$this->_form['entitlement_type'],$this->_form['thread']);
				$this->_task_category = 'Entitlement Request';
				break;
			case "463":
				$thread_text = sprintf("Component Id: %s\nIbase Update Type: Other\nEffective On:%s\nAdditional Comments:%s\n",$this->_form['ek_equip_component_id'],$this->_form['effective_date'],$this->_form['thread']);
				break;
		}	
		return $thread_text;	
	}
}
