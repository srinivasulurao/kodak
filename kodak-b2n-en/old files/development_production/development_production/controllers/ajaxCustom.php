<?php

class ajaxCustom extends ControllerBase
{
    //This is the constructor for the custom controller. Do not modify anything within
    //this function.
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Sample function for ajaxCustom controller. This function can be called by sending
     * a request to /ci/ajaxCustom/ajaxFunctionHandler.
     */
    function ajaxFunctionHandler()
    {
        $postData = $this->input->post('post_data_name');
        //Perform logic on post data here
        echo $returnedInformation;
    }


    function getProductHier()
    {
        $this->load->model('custom/custom_prodcat_model');
        $product_list = $this->input->request('product_list');
        $prod_arr = explode(",", $product_list);
        $results = $this->custom_prodcat_model->getEnduserVisibleHierarchy($prod_arr);
        sendCachedContentExpiresHeader();
        echo json_encode($results);
    }


    function getHierValues()
    {
        $this->load->model('custom/custom_prodcat_model');
        $filter = $this->input->request('filter');
        $level = $this->input->request('lvl');
        $id = $this->input->request('id');
        $linking = $this->input->request('linking');
        $results = $this->custom_prodcat_model->hierMenuGet($filter, $level, $id, $linking);
        logmessage("filter: $filter, level: $level, id: $id, linking: $linking");
        sendCachedContentExpiresHeader();
        echo json_encode($results);
    }
	
	
    function sendForm()
    {
        AbuseDetection::check($this->input->post('f_tok'));
        $data = json_decode($this->input->post('form'));
        if(!$data)
        {
            writeContentWithLengthAndExit(json_encode(getMessage(JAVASCRIPT_ENABLED_FEATURE_MSG)));
        }
        $incidentID = $this->input->post('i_id');
        $smartAssistant = $this->input->post('smrt_asst');
        $this->load->model('custom/Field_model');
        $results = $this->Field_model->sendForm($data, $incidentID, $smartAssistant);
        echo json_encode($results);
    }


    function getIbaseProduct() 
    {
      $this->load->model('custom/custom_prodcat_model');

      $result = array();
      $filterName = 'prod';
      $emptyReturns = 0;
      $product_list = $this->input->request('product_list');
      $hierItems = explode(",", $product_list);
      $hierData = array();

      //iterate enough to retrieve the children (count + 1) of the selected node
      for($i = 0; $i < count($hierItems) + 1; $i++)
      {
            //don't bother trying to retrieve the children of a level 6 node
            if($i <= 5)
            {
                $arrayIndex = ($i === 0) ? 0 : $hierItems[$i - 1];

                $hierData = $this->custom_prodcat_model->hierMenuGet($filterName, $i+1, $arrayIndex, 0);
                
                if(count($hierData[0]) === 0)
                    $emptyReturns++;
                
                $result[$i] = array();

                foreach($hierData[0] as $value)
                {
                    //$result['hierData'][$i] = array();

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

    function setDefaultProduct()
    {
        $this->custom_prodcat_model->setDefaultProduct($prodArray);
    }
 
	function getMap()
    {
        $this->load->model('custom/custom_prodcat_model');
        $id = $this->input->request('id');
        $results = $this->custom_prodcat_model->hierMenuGetLinking($id);
        sendCachedContentExpiresHeader();
        echo json_encode($results);
    }
 
}

