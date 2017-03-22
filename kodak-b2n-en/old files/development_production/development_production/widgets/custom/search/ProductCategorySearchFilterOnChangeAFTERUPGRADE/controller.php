<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ProductCategorySearchFilter extends Widget
{
    function __construct()
    {
        parent::__construct();

        $this->attrs['label_input'] = new Attribute(getMessage(INPUT_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_INPUT_CONTROL_LBL), getMessage(LIMIT_BY_PRODUCT_LBL));
        $this->attrs['report_id'] = new Attribute(getMessage(REPORT_LBL), 'INT', getMessage(ID_RPT_DISP_DATA_SEARCH_RESULTS_MSG), CP_NOV09_ANSWERS_DEFAULT);
        $this->attrs['report_id']->min = 1;
        $this->attrs['report_id']->optlistId = OPTL_CURR_INTF_PUBLIC_REPORTS;
        $this->attrs['filter_type'] = new Attribute(getMessage(FILTER_TYPE_LBL), 'OPTION', getMessage(FILTER_DISP_DROPDOWN_INFORMATION_LBL), 'products');
        $this->attrs['filter_type']->options = array('products', 'categories');
        $this->attrs['linking_off'] = new Attribute(getMessage(PROD_SLASH_CAT_LINKING_OFF_LBL), 'BOOL', getMessage(SET_TRUE_PROD_CAT_LINKING_DISABLED_MSG), false);
        $this->attrs['label_nothing_selected'] = new Attribute(getMessage(NOTHING_SELECTED_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_VALUE_SELECTED_LBL), getMessage(SELECT_A_PRODUCT_LBL));
        $this->attrs['label_screen_reader_selected'] = new Attribute(getMessage(VALUES_SELECTED_LABEL_LBL), 'STRING', getMessage(LABEL_DISP_SCREEN_READERS_LBL), getMessage(VALUES_SELECTED_LBL));
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] =  getMessage(WIDGET_DISP_DROPDOWN_MENU_MSG);
        $this->parms['p'] = new UrlParam(getMessage(PRODUCT_LBL), 'p', false, getMessage(CMMA_SPARATED_IDS_COMMAS_DENOTING_MSG), 'p/1,2,3');
        $this->parms['c'] = new UrlParam(getMessage(CATEGORY_LBL), 'c', false, getMessage(COMMA_SEPARATED_IDS_COMMAS_DENOTING_MSG), 'c/1');
    }

    function getData()
    {
        $this->CI->load->model('standard/Report_model');
        setFiltersFromUrl($this->data['attrs']['report_id'], $filters);

        $filterType = ($this->data['attrs']['filter_type'] === 'products') ? 'p' : 'c';

        $defaultValue = $filters[$filterType]->filters->data[0];
        if($defaultValue)
            $defaultValue = explode(',', $defaultValue);
        else
            $defaultValue = array();

        $optlistID = $filters[$filterType]->filters->optlist_id;
        if(!$optlistID)
        {
            echo $this->reportError(sprintf(getMessage(FILTER_PCT_S_EXIST_REPORT_PCT_D_LBL), $this->data['attrs']['filter_type'], $this->data['attrs']['report_id']));
            return false;
        }

        $this->CI->load->model('standard/Prodcat_model');
        $this->data['js'] = array('name' => $filters[$filterType]->filters->name,
                                            'oper_id' => $filters[$filterType]->filters->oper_id,
                                            'fltr_id' => $filters[$filterType]->filters->fltr_id,
                                            'linkingOn' => $this->data['attrs']['linking_off'] ? 0 : $this->CI->Prodcat_model->getLinkingMode(),
                                            'report_def' => $filters[$filterType]->report_default,
                                            'searchName' => $filterType,
                                            'defaultData' => (count($defaultValue)) ? true : false,
                                            'hierData' => array());

        //if linking is on we need to get all values for prods as well as cats
        if($filterType === 'c' && $this->data['js']['linkingOn'])
            $selectedProds = ($filters['p']) ? $filters['p']->filters->data[0] : null;

        if($selectedProds)
        {
            //if categories and linking on
            $selectedProds = explode(',', $selectedProds);
            if(!$this->_getProdLinkingDefaults($selectedProds, $defaultValue))
                return false;
        }
        else
        {
            if(!$this->_setDefaults($defaultValue))
                return false;
        }
        $this->data['js']['initial'] = $defaultValue;
        $this->data['js']['hm_type'] = $this->data['attrs']['filter_type'] === 'products' ? HM_PRODUCTS : HM_CATEGORIES;
    }

    /**
     * Utility function to retrieve hier menus and massage
     * the data for our usage.
     * @param $hierItems Array List of hier menu IDs
     * @return Boolean T if the hierarchy data was successfully populated
     *                          or F if no hierarchy data was found
     */
    private function _setDefaults($hierItems)
    {
        $emptyReturns = 0;
        for($i = 0; $i < count($hierItems) + 1; $i++)
        {
            if($i <= 5)
            {
                $arrayIndex = ($i === 0) ? 0 : $hierItems[$i - 1];
                $hierData = $this->CI->Prodcat_model->hierMenuGet($this->data['attrs']['filter_type'], $i+1, $arrayIndex, $this->data['js']['linking_on']);
                if(!count($hierData[0]))
                    $emptyReturns++;
                $this->data['js']['hierData'][$i] = array();
                foreach($hierData[0] as $value)
                {
                    $selected = ($value[0] == $hierItems[$i]) ? true : false;
                    //parent is the node's parent id; hasChildren is a flag denoting whether a node has children
                    array_push($this->data['js']['hierData'][$i], array('value' => $value[0], 'label' => $value[1], 'parentID' => $arrayIndex, 'selected' => $selected, 'hasChildren' => $value[3]));
                }
            }
        }
        if($emptyReturns > count($hierItems))
            return false;
        //add an additional 'no value' node to the front
        array_unshift($this->data['js']['hierData'][0], array('value' => 0, 'label' => getMessage(NO_VAL_LBL)));
        return true;
    }
    /**
     * Utility function to retrieve hier menus for prod linking
     * and massage the data for our usage.
     * @param $hierItems Array List of hier menu IDs
     * @param $catArray Array List of hier menu IDs
     * @return Boolean T if the hierarchy data was successfully populated
     *                          or F if no hierarchy data was found
     */
    private function _getProdLinkingDefaults($productArray, $catArray)
    {
        //selectedProds is an array of 0 - 5
        //find the last product in selectedProds
        $productArray = array_filter($productArray);
        $lastProdId = end($productArray);
        if($lastProdId)
        {
            $hierArray = $this->CI->Prodcat_model->hierMenuGetLinking($lastProdId);
            if(!count($hierArray))
                return false;
            ksort($hierArray);
            $this->data['js']['hierData'][0] = array();
            $count = 0;
            foreach($hierArray as $parentId => $children)
            {
                $validChild = false;
                $this->data['js']['hierData'][$count] = array();
                foreach($children as $value)
                {
                    $validChild = true;
                    $selected = ($catArray[$count] == $value[0]) ? true : false;
                    //parent is the node's parent id; hasChildren is a flag denoting whether a node has children
                    array_push($this->data['js']['hierData'][$count], array('value' => $value[0], 'label' => $value[1], 'parentID' => $parentId, 'selected' => $selected, 'hasChildren' => $value[2]));
                }
                if($validChild)
                    $count++;
            }
            $this->data['js']['link_map'] = $hierArray;
            //add an additional 'no value' node to the front
            array_unshift($this->data['js']['hierData'][0], array('value' => 0, 'label' => getMessage(NO_VAL_LBL)));
            return true;
        }
    }
}
