<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class PartnerTypeList extends Widget
{
    function __construct()
    {
        parent::__construct();
		$this->attrs['label'] = new Attribute('', 'STRING', '', '');
		$this->attrs['name'] = new Attribute('', 'STRING', '', '');
		$this->attrs['selected_value'] = new Attribute('Selected Value','Int','The selected value of the select box when the page loads.',0);
		
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] = '';
    }

    function getData()
    {
		$this->CI->load->model('custom/custom_org_model');
		$org_id = $this->CI->session->getProfileData('org_id');
		if($org_id < 0) $org_id = 0;

		$partnerTypes = $this->CI->custom_org_model->getPartnerTypeList($org_id);

		$this->data['partnertypes'] = $partnerTypes;
		if($this->data['attrs']['selected_value'] > 0)
		{
			$this->data['js']['defaultFilter'] = $this->data['attrs']['selected_value'];
		}
		else
		{
			$i = 0;


			while ($i < count($partnerTypes))
			{
				if($partnerTypes[$i]['selected'] == 'selected')
				{
					//$this->data['js']['defaultFilter'] =$partnerTypes[$i]['ID'];
					$this->data['js']['defaultFilter'] =$partnerTypes[$i]['Type'];
					break;
				}
				if($partnerTypes[$i]['selected'] == 'multiple')
				{
					//$this->data['js']['defaultFilter'] = 0;
					$this->data['js']['defaultFilter'] = '--';
					break;
				}
                                $i++;  //2012.10.11 scott harris: this loop was never ending
				
			}

		}
    }
}
