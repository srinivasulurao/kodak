<?
use RightNow\Connect\v1 as RNCPHP;

class ibase_search extends ControllerBase
{
	private $_form;

	/*
	* Constructor
	*/
	public function __construct()
	{
		parent::__construct();

		$CI =& get_instance();
		
		$this->CI = $CI;
		$this->CI->load->model('custom/ibase_product_model');

		require_once(get_cfg_var('doc_root') . '/include/ConnectPHP/Connect_init.phph');
		
		initConnectAPI();

	}
	
        public function get_ibase_list() {

          $list = array();
          $city = ltrim($this->input->post('city'));
          $street = ltrim($this->input->post('street'));
          $name = ltrim($this->input->post('name'));
          $country = ltrim($this->input->post('country'));
          $region = ltrim($this->input->post('province'));
          $zip = ltrim($this->input->post('zip'));
          $sap_partner_id = ltrim($this->input->post('sap_partner_id'));
          $partner_type = ltrim($this->input->post('partner_type'));
          $material_id = ltrim($this->input->post('material_id'));
          $ibase_partner_id = ltrim($this->input->post('ibase_partner_id'));
          $internal = ltrim($this->input->post('internal'));

          if($sap_partner_id == "" || $partner_type == "") {
            $list = array('status'=>0);
            print(json_encode($list));
	    return;
          }

          $list = $this->CI->ibase_product_model->getIbaseList("shipto", $partner_type, $sap_partner_id, $name, $city, $street, $zip, $country, $region, $material_id, $ibase_partner_id, $internal);

          if(array_key_exists('error', $list)) {
            $list['status'] = 99;
            $list['message'] = $list['error'];
          }
          else
            $list['status'] = 1;
          
          print(json_encode($list));
          return;

        }

        public function get_province_list() {

          $list = array();
          $country = $this->input->post('country');

          $list = $this->CI->ibase_product_model->getStateList($country);

          $list['status'] = 1;
          print(json_encode($list));
          return;

        }

        public function get_ibase()
        {

          $ibase = array();
          $partnerFunction = $this->input->post('partner_function');
          $partnerID = $this->input->post('partner_id');
          $ibaseID = $this->input->post('ibase_id');

          $ibase = $this->CI->ibase_product_model->getIbaseResult($partnerFunction, $partnerID, $ibaseID);
      
          if(count($ibase) == 0)
            $ibase['status'] = 0;
          else
            $ibase['status'] = 1;
   
          print(json_encode($ibase));
          return;

        }

        public function sendMeterUpdate()
        {

          $counterID = $this->input->post('meterID');
          $counterValue = $this->input->post('meterValue');
          $readDate = $this->input->post('meterDate');

          $productID = ltrim($this->input->post('productID'));
          $sapid = ltrim($this->input->post('sapid'));

          //call to make meter count update
          $meterResult = $this->CI->ibase_product_model->sendMeterUpdate($counterID, $counterValue, $readDate);

          $product = array();
          if($meterResult['error'] == 1) {
            $product['status'] = 0;
            $product['error'] = $meterResult['message'];
          }
          else {
            $product = $this->CI->ibase_product_model->getProducts($productID, 'compid', $sapid, '00000002');
            $product['status'] = 1;

            $site = $this->CI->ibase_product_model->getSite($product[0]['SAPID']);

            $payer = $this->CI->ibase_product_model->getSite($product[0]['PAYERID']);
            $payer['SAPID'] = $product[0]['PAYERID'];

            $product['Site'][0] = $site;

            $product['Payer'] = $payer;
          }
          print(json_encode($product));

          return;

        }

	public function get_product()
	{

		//$orgID = $this->session->getProfileData('org_id');
                $product = array();

                $productID = ltrim($this->input->post('product_id'));
                $searchBy = $this->input->post('ibase_search');
                $sapPartnerID = $this->input->post('sap_partner_id');
                $partnerType = $this->input->post('partner_type');

                if($sapPartnerID == "" || $partnerType == "") {
                  $product = array('status'=>0);
                  print(json_encode($product));
		  return;
                }
                logmessage("controller::getProducts($productID, $searchBy, $sapPartnerID, $partnerType)");
		$product = $this->CI->ibase_product_model->getProducts($productID, $searchBy, $sapPartnerID, $partnerType);

                //2013.04.30 scott harris: if error for multiples, then do other search

                if($product[0]['error'] && $product[0]['error']['message'] == 'MULTIPLES FOUND') {

		  $product = $this->CI->ibase_product_model->getIbaseListByProduct($searchBy, $productID); 

                }
        
                if($product == null)  //when no result is found a null is returned
		  $product['status'] = 0;
                else    
		  $product['status'] = 1;

                $site = $this->CI->ibase_product_model->getSite($product[0]['SAPID']);

                $payer = $this->CI->ibase_product_model->getSite($product[0]['PAYERID']);
                $payer['SAPID'] = $product[0]['PAYERID'];

                $product['Site'][0] = $site;

		$product['Payer'] = $payer;

                print(json_encode($product));

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


}