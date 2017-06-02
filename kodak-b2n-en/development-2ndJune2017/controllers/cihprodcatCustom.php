<?php
namespace Custom\Controllers;

class cihprodcatCustom extends \RightNow\Controllers\Base {
    //This is the constructor for the custom controller. Do not modify anything within
    //this function.
    function __construct() {
        parent::__construct();
    }

    /**
     * Sample function for ajaxCustom controller. This function can be called by sending
     * a request to /ci/ajaxCustom/ajaxFunctionHandler.
     */
    function cihprodcatFunctionHandler() {
        $postData = $this->input->post('post_data_name');
        //Perform logic on post data here
        echo $returnedInformation;
    }
	
	function getFiredProductCategoryLinks(){
		$product_id=$this->input->post('fired_products');
	    $results=$this->model('custom/custom_cih_prodcat_model')->getProductCategoryLinking($product_id);
		echo json_encode($results);
	}

    function getHierValues() {
        $filter = $this->input->request('filter');
        $level = $this->input->request('lvl');
        $id = $this->input->request('id');
        $linking = $this->input->request('linking');
        $prodid = $this->input->request('prodid');
        $results = $this->model('custom/custom_cih_prodcat_model')->hierMenuGet($filter, $level, $id, $linking, $prodid);
        sendCachedContentExpiresHeader();
        echo json_encode($results);
    }

    function getHierValues2() {
        $filter = $this->input->request('filter');
        $level = $this->input->request('lvl');
        $id = $this->input->request('id');
        $linking = $this->input->request('linking');
        $prodid = $this->input->request('prodid');
        $results = $this->model('custom/custom_cih_prodcat_model')->hierMenuGet($filter, $level, $id, $linking, $prodid);

        $hierDataNone = array(array());
        foreach($results[0] as $value) {
            $hasChildren = $value[3];
            //parent is the node's parent id; hasChildren is a flag denoting whether a node has children
            array_push($hierDataNone[0], array('value' => $value[0], 'label' => $value[1], 'parentID' => 0, 'selected' => false, 'hasChildren' => $hasChildren));
        }
        //add an additional 'no value' node to the front
        array_unshift($hierDataNone[0], array('value' => 0, 'label' => getMessage(NO_VAL_LBL)));

        sendCachedContentExpiresHeader();
        echo json_encode($hierDataNone);
    }
	
    function sendForm() {
        AbuseDetection::check($this->input->post('f_tok'));
        $data = json_decode($this->input->post('form'));
        if(!$data)
            writeContentWithLengthAndExit(json_encode(getMessage(JAVASCRIPT_ENABLED_FEATURE_MSG)));
        $incidentID = $this->input->post('i_id');
        $smartAssistant = $this->input->post('smrt_asst');
        $results = $this->model('custom/Field_model')->sendForm($data, $incidentID, $smartAssistant);
        echo json_encode($results);
    }

    function getCatHier() {
        $product_list = $this->input->request('product_list');
        $hierItems = explode(",", $product_list);
        $hierData = array();
        $results = $this->model('custom/custom_cih_prodcat_model')->hierCatByProd($hierData[0]);

        sendCachedContentExpiresHeader();
        echo json_encode($results);
    }

    function getIbaseProduct() {
		$result = array();
		$filterName = 'prod';
		$emptyReturns = 0;
		$product_list = $this->input->request('product_list');
		$hierItems = explode(",", $product_list);
		$hierData = array();

		//iterate enough to retrieve the children (count + 1) of the selected node
		for($i = 0; $i < count($hierItems) + 1; $i++) {
			//don't bother trying to retrieve the children of a level 6 node
			if($i <= 5) {
				$arrayIndex = ($i === 0) ? 0 : $hierItems[$i - 1];
				$hierData = $this->model('custom/custom_cih_prodcat_model')->hierMenuGet($filterName, $i+1, $arrayIndex, 0);
				if(count($hierData[0]) === 0)
					$emptyReturns++;
				
				$result[$i] = array();
				foreach($hierData[0] as $value) {
					$selected = ($value[0] == $hierItems[$i]);
					$hasChildren = $value[3];
					//parent is the node's parent id; hasChildren is a flag denoting whether a node has children
					array_push($result[$i], array('value' => $value[0], 'label' => $value[1], 'parentID' => $arrayIndex, 'selected' => $selected, 'hasChildren' => $hasChildren));
				}
			}
		}  //end outer for

		array_unshift($result[0], array('value' => 0, 'label' => "No Value"));
		echo json_encode($result);
    }

    function setDefaultProduct() {
        $this->model('custom/custom_cih_prodcat_model')->setDefaultProduct($prodArray);
    }
 
	function getMap() {
        $id = $this->input->request('id');
        $results = $this->model('custom/custom_cih_prodcat_model')->hierMenuGetLinking($id);
        sendCachedContentExpiresHeader();
        echo json_encode($results);
    }
}