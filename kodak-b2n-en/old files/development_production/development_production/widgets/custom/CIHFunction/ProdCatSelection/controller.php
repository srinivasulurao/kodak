<?php /* Originating Release: February 2012 */

  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ProdCatSelection extends Widget
{
    function __construct()
    {
        parent::__construct();
        $this->attrs['default_value'] = new Attribute(getMessage(DEFAULT_VALUE_LBL), 'STRING', getMessage(CMMA_SEPARATED_IDS_COMMAS_DENOTING_MSG), '');
        $this->attrs['label_input'] = new Attribute(getMessage(INPUT_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_INPUT_CONTROL_LBL), getMessage(PRODUCT_LBL));
        $this->attrs['label_error'] = new Attribute(getMessage(ERROR_LABEL_LBL), 'STRING', sprintf(getMessage(PCT_S_IDENTIFY_FLD_NAME_ERR_MSGS_MSG), 'label_input'), '');
        $this->attrs['label_set_button'] = new Attribute(getMessage(SET_BUTTON_LABEL_CMD), 'STRING', getMessage(LABEL_DISPLAY_BTN_SET_BTN_ATTRIB_LBL), getMessage(PRODUCTS_LBL));
        $this->attrs['label_required'] = new Attribute(getMessage(REQUIRED_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_REQUIRED_LEVEL_MET_LBL), getMessage(PLEASE_SELECT_AN_ITEM_UNDER_PCT_S_MSG));
        $this->attrs['label_confirm_button'] = new Attribute(getMessage(CONFIRM_BUTTON_LABEL_MSG), 'STRING', sprintf(getMessage(LABEL_CONFIRMATION_BTN_PCT_S_ATTRIB_MSG), 'show_confirm_button_in_dialog'), getMessage(OK_LBL));
        $this->attrs['label_cancel_button'] = new Attribute(getMessage(CANCEL_BUTTON_LABEL_CMD), 'STRING', sprintf(getMessage(LABEL_CANCEL_BTN_PCT_S_ATTRIB_MSG), 'show_confirm_button_in_dialog'), getMessage(CANCEL_CMD));
        $this->attrs['label_nothing_selected'] = new Attribute(getMessage(NOTHING_SELECTED_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAY_VALUE_SELECTED_LBL), ''); // getMessage(SELECT_A_PRODUCT_LBL));
        $this->attrs['label_accessible_interface'] = new Attribute(getMessage(ACCESSIBLE_INTERFACE_LABEL_LBL), 'STRING', getMessage(LABEL_DISPLAYED_SCREEN_EFFECTIVELY_MSG), getMessage(BTN_SCREEN_READERS_PLS_PREV_LINK_MSG));
        $this->attrs['label_screen_reader_selected'] = new Attribute(getMessage(VALUES_SELECTED_LABEL_LBL), 'STRING', getMessage(LABEL_DISP_SCREEN_READERS_LBL), getMessage(VALUES_SELECTED_LBL));
        $this->attrs['label_screen_reader_accessible_option'] = new Attribute(getMessage(ACCESSIBLE_OPTION_LABEL_LBL), 'STRING', getMessage(TXT_LINK_DISPLAYED_SCREEN_READERS_MSG), getMessage(SCREEN_READER_USERS_PRESS_ENTER_SEL_MSG));
        
        $this->attrs['data_type'] = new Attribute(getMessage(DATA_TYPE_LBL), 'OPTION', getMessage(TYPE_INFO_DISP_SET_PRODUCTS_CAT_MSG), 'products');
        $this->attrs['data_type']->options =  array('products', 'categories');
        $this->attrs['table'] = new Attribute(getMessage(TABLE_LBL), 'OPTION', getMessage(DEFINES_TABLE_WIDGET_MSG), '');
        $this->attrs['table']->options = array('contacts', 'incidents');
        $this->attrs['table']->required = true;
        //$this->attrs['required_lvl'] = new Attribute(getMessage(REQUIRED_LEVEL_LBL), 'INT', getMessage(VAL_SPECIFIYING_LVLS_SEL_BEF_VAL_MSG), 0);
        $this->attrs['required_lvl'] = new Attribute(getMessage(REQUIRED_LEVEL_LBL), 'INT', '', 0);
        $this->attrs['required_lvl']->min = 0;
        $this->attrs['required_lvl']->max = 6;
        $this->attrs['max_lvl'] = new Attribute(getMessage(MAX_LEVEL_LBL), 'INT', sprintf(getMessage(VAL_SPECIFYING_MAX_LVLS_SHOWN_PCT_S_MSG), "required_lvl", "max_lvl", "max_lvl", "required_lvl"), 6);
        $this->attrs['max_lvl']->min = 1;
        $this->attrs['max_lvl']->max = 6;
        $this->attrs['linking_off'] = new Attribute(getMessage(PROD_SLASH_CAT_LINKING_OFF_LBL), 'BOOL', getMessage(VALUE_OVRRIDE_PROD_CAT_LINKING_SET_MSG), false);
        $this->attrs['set_button'] = new Attribute(getMessage(HIER_MENU_SET_BUTTON_LBL), 'BOOL', getMessage(SET_TRUE_LABEL_LABEL_BTN_ATTRIB_BTN_MSG), false);
        $this->attrs['allow_external_login_updates'] = new Attribute(getMessage(ALLOW_EXTERNAL_LOGIN_UPDATES_LBL), 'BOOL', getMessage(ALLOWS_USERS_AUTHENTICATE_CP_EXT_MSG), false);
        $this->attrs['treeview_css'] = new Attribute(getMessage(TREEVIEW_CSS_LBL), 'STRING', sprintf(getMessage(FILE_CONT_TREEVIEW_CSS_DISP_EXP_MSG), "/rnt/rnw/yui_2.7/treeview/assets/treeview-skin.css"), getYUICodePath('treeview/assets/treeview-menu.css'));
        $this->attrs['show_confirm_button_in_dialog'] = new Attribute(getMessage(SHOW_CONFIRM_BUTTONS_IN_DIALOG_MSG), 'BOOL', getMessage(ENABLED_TREE_POPUP_CONT_CANCEL_MSG), false);
        $this->attrs['hint'] = new Attribute(getMessage(HINT_LBL), 'STRING', getMessage(HINT_TXT_DISP_FLD_CF_VAL_OVRRIDE_MSG), '');
        $this->attrs['always_show_hint'] = new Attribute(getMessage(ALWAYS_SHOW_HINT_LBL), 'BOOL', getMessage(SET_TRUE_FLD_HINT_HINT_DISPLAYED_MSG), false);
        $this->attrs['vis'] = new Attribute("vis", 'STRING', "vis", '');
    }

    function generateWidgetInformation()
    {
        $this->info['notes'] =  getMessage(WDGET_DISP_DROPDOWN_MENU_MSG);
        $this->parms['i_id'] = new UrlParam(getMessage(INCIDENT_ID_LBL), 'i_id', false, getMessage(INCIDENT_ID_DISPLAY_INFORMATION_LBL), 'i_id/7');
        $this->parms['p'] = new UrlParam(getMessage(PRODUCT_LBL), 'p', false, getMessage(CMMA_SPARATED_IDS_COMMAS_DENOTING_MSG), 'p/1,2,3');
        $this->parms['c'] = new UrlParam(getMessage(CATEGORY_LBL), 'c', false, getMessage(COMMA_SEPARATED_IDS_COMMAS_DENOTING_MSG), 'c/1');
    }

    function getData()
    {

        if($this->data['attrs']['required_lvl'] > $this->data['attrs']['max_lvl']) {
            addErrorToPageAndHeader(sprintf(getMessage(VAL_PCT_S_EXCEEDS_PCT_S_PCT_S_SET_MSG), "required_lvl", "max_lvl", "max_lvl", "required_lvl", $this->data['attrs']['required_lvl']), true);
            $this->data['attrs']['max_lvl'] = $this->data['attrs']['required_lvl'];
        }
        
        if($this->data['attrs']['hint'] && strlen(trim($this->data['attrs']['hint']))){
            $this->data['js']['hint'] = $this->data['attrs']['hint'];
        }

        $trimmedTreeViewCss = trim($this->data['attrs']['treeview_css']);
        if ($trimmedTreeViewCss !== '')
            $this->addStylesheet($trimmedTreeViewCss);
        
        $this->CI->load->model('custom/Custom_CIH_Prodcat_model');
        //$this->CI->load->model('standard/Prodcat_model');
        $dataType = strtolower($this->data['attrs']['data_type']);
        $table = strtolower($this->data['attrs']['table']);

        $this->data['js']['linkingOn'] = 1;
        //$this->data['js']['linkingOn'] = $this->data['attrs']['linking_off'] ? 0 : $this->CI->Custom_CIH_Prodcat_model->getLinkingMode();
        //$this->data['js']['linkingOn'] = $this->data['attrs']['linking_off'] ? 0 : $this->CI->Prodcat_model->getLinkingMode();
        $hierValues = array();

        if($table === 'contacts')
        {
            if(isPta() && !$this->data['attrs']['allow_external_login_updates'])
            {
                $this->data['js']['readOnly'] = true;
                $this->data['attrs']['data_type'] = ($dataType === 'products') ? 'prod' : 'cat';
                return;
            }

            $profile = $this->CI->session->getProfile();
            if($profile)
            {
                //$profileProdValues = $this->CI->Prodcat_model->getEnduserVisibleHierarchy(array_filter($profile->prod->value));
                $profileProdValues = $this->CI->Custom_CIH_Prodcat_model->getEnduserVisibleHierarchy(array_filter($profile->prod->value));

                //$profileCatValues = $this->CI->Prodcat_model->getEnduserVisibleHierarchy(array_filter($profile->cat->value));
                $profileCatValues = $this->CI->Custom_CIH_Prodcat_model->getEnduserVisibleHierarchy(array_filter($profile->cat->value));
                if($dataType === 'products' && count($profileProdValues))
                {
                    $hierValues = $profileProdValues;
                }
                else if($dataType === 'categories')
                {
                    if($this->data['js']['linkingOn'] && count($profileProdValues))
                    {
                        $retrieveProdLinkingData = true;
                        $products = $profileProdValues;
                        $categories = $profileCatValues;
                    }
                    else if(count($profileCatValues))
                    {
                        $hierValues = $profileCatValues;
                    }
                }
            }
        }
        else
        {
            //incidents
            $incidentId = getUrlParm('i_id');

            if($incidentId)
            {
                $this->CI->load->model('standard/Incident_model');
                $incident = $this->CI->Incident_model->get($incidentId);
                if($dataType === 'products')
                {
                    $selectedHier = $incident->prod->value;
                    if(count($selectedHier))
                        $selectedHier = $selectedHier[count($selectedHier) - 1]['hier_list'];
                }
                else
                {
                    //categories
                    if($this->data['js']['linkingOn'] && count($incident->prod->value))
                    {
                        $retrieveProdLinkingData = true;
                        //rip out the final node's hier_list value and convert it to an array
                        $products = explode(',', $incident->prod->value[count($incident->prod->value) - 1]['hier_list']);
                        if(count($incident->cat->value))
                            $categories = explode(',', $incident->cat->value[count($incident->cat->value) - 1]['hier_list']);
                        else
                            $categories = array();
                    }
                    else
                    {
                        $selectedHier = $incident->cat->value;
                        if(count($selectedHier))
                            $selectedHier = $selectedHier[count($selectedHier) - 1]['hier_list'];
                    }
                }
                   //$hierValues = $this->CI->Prodcat_model->getEnduserVisibleHierarchy(explode(',', $selectedHier));

               if($selectedHier)
                 $hierValues = $this->CI->Custom_CIH_Prodcat_model->getEnduserVisibleHierarchy(explode(',', $selectedHier));

            }
            else
            {
                //Check for default values to prefill the field with. Order is POST parameter, old school URL parameter, new
                //school URL parameter, and then widget attribute
                
                //PHP replaces periods in POST parameters with underscores, so look for that syntax
                $defaultValue = ($dataType === 'products') ? $this->CI->input->post($table . '_prod') : $this->CI->input->post($table . '_cat');
                if($defaultValue === false || $defaultValue === "")
                {
                    $defaultValue = ($dataType === 'products') ? getUrlParm('p') : getUrlParm('c');
                    if($defaultValue === null || $defaultValue === '')
                    {
                        $defaultValue = ($dataType === 'products') ? getUrlParm("$table.prod") : getUrlParm("$table.cat");
                        if($defaultValue === null || $defaultValue === '')
                        {
                            $defaultValue = $this->data['attrs']['default_value'];
                        }
                    }
                }
                
                if($defaultValue !== false && $defaultValue !== null && $defaultValue !== '')
                {
                    //$hierValues = $this->CI->Prodcat_model->getEnduserVisibleHierarchy(explode(',', $defaultValue));
                    $hierValues = $this->CI->Custom_CIH_Prodcat_model->getEnduserVisibleHierarchy(explode(',', $defaultValue));
                    if(count($hierValues) && $dataType === 'products')
                    {
                        if(count($hierValues) > $this->data['attrs']['max_lvl'])
                            array_splice($hierValues, $this->data['attrs']['max_lvl']);
                        $this->CI->Custom_CIH_Prodcat_model->setDefaultProduct($hierValues);


                        //$this->CI->Prodcat_model->setDefaultProduct($hierValues);
                    }
                }
                if($this->data['js']['linkingOn'] && $dataType === 'categories')
                {
                    $products = $this->CI->Custom_CIH_Prodcat_model->getDefaultProduct();
                    //$products = $this->CI->Prodcat_model->getDefaultProduct();
                    if($products)
                    {
                        $retrieveProdLinkingData = true;
                        if(count($hierValues) > $this->data['attrs']['max_lvl'])
                            array_splice($hierValues, $this->data['attrs']['max_lvl']);
                        $categories = $hierValues;
                    }
                }
            }
        }
        
        if($retrieveProdLinkingData)
        {
            //Product linking's going on: This is a category widget and there's already product data set through
            //contacts / incidents table OR (1) url parm or (2) attribute
            $this->data['js']['defaultData'] = $this->_setProdLinkingDefaults($products, $categories);
            if(!$this->data['js']['defaultData'])
                return false;
        }
        else
        {
            //We either already have data from contacts or incidents table OR
            //specified through (1) url parm or (2) attribute for incidents.OR
            //none of the above (empty array): so just populate top-level
            $hierValues = $this->CI->Custom_CIH_Prodcat_model->getEnduserVisibleHierarchy($hierValues);
            //$hierValues = $this->CI->Prodcat_model->getEnduserVisibleHierarchy($hierValues);
            if(count($hierValues) > $this->data['attrs']['max_lvl'])
                array_splice($hierValues, $this->data['attrs']['max_lvl']);
            if(!$this->_setDefaults($hierValues))
                return false;
            if(count($hierValues))
                $this->data['js']['defaultData'] = true;
        }
        $this->data['js']['hm_type'] = $dataType === 'products' ? HM_PRODUCTS : HM_CATEGORIES;

        // even if no default product is selected, get a fresh set of categories
        // so that an initial category selection doesn't result in weird behavior
        // when the tree is reset
        if($this->data['js']['linkingOn'] && $dataType === 'categories')
            $this->_setNoneDefaults();

    }

    /**
     * Utility function to retrieve category hier menu and massage
     * the data for our usage when "No Value" is selected for the product.
     */
    private function _setNoneDefaults()
    {
        $filterName = $this->data['attrs']['data_type'];
        $hierData = $this->CI->Custom_CIH_Prodcat_model->hierMenuGet($filterName, 1, 0, $this->data['js']['linkingOn'], 3826);
        //$hierData = $this->CI->Prodcat_model->hierMenuGet($filterName, 1, 0, $this->data['js']['linkingOn']);
        $this->data['js']['hierDataNone'] = array(array());
        foreach($hierData[0] as $value)
        {
            $hasChildren = $value[3];
            //parent is the node's parent id; hasChildren is a flag denoting whether a node has children
            array_push($this->data['js']['hierDataNone'][0], array('value' => $value[0], 'label' => $value[1], 'parentID' => 0, 'selected' => false, 'hasChildren' => $hasChildren));
        }
        //add an additional 'no value' node to the front
        array_unshift($this->data['js']['hierDataNone'][0], array('value' => 0, 'label' => getMessage(NO_VAL_LBL)));
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
        $filterName = $this->data['attrs']['data_type'];
        $emptyReturns = 0;
        //iterate enough to retrieve the children (count + 1) of the selected node
        for($i = 0; $i < count($hierItems) + 1; $i++)
        {
            //don't bother trying to retrieve the children of a level 6 node
            if($i <= 5)
            {
                $arrayIndex = ($i === 0) ? 0 : $hierItems[$i - 1];
                $hierData = $this->CI->Custom_CIH_Prodcat_model->hierMenuGet($filterName, $i+1, $arrayIndex, $this->data['js']['linkingOn']);
                //$hierData = $this->CI->Prodcat_model->hierMenuGet($filterName, $i+1, $arrayIndex, $this->data['js']['linkingOn']);
                if(count($hierData[0]) === 0)
                    $emptyReturns++;
                $this->data['js']['hierData'][$i] = array();
                foreach($hierData[0] as $value)
                {
                    $selected = ($value[0] == $hierItems[$i]);
                    $hasChildren = $value[3];
                    //parent is the node's parent id; hasChildren is a flag denoting whether a node has children
                    array_push($this->data['js']['hierData'][$i], array('value' => $value[0], 'label' => $value[1], 'parentID' => $arrayIndex, 'selected' => $selected, 'hasChildren' => $hasChildren));
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
     * @param $productArray Array List of hier menu IDs
     * @param $categoryArray Array List of hier menu IDs
     * @return Boolean T if the hierarchy data was successfully populated
     *                          or F if no hierarchy data was found
     */
    private function _setProdLinkingDefaults($productArray, $categoryArray)
    {
        $lastProdId = $productArray[count($productArray) - 1];
        if($lastProdId)
        {
            $hierArray = $this->CI->Custom_CIH_Prodcat_model->hierMenuGetLinkingWithLevel($lastProdId);
            //$hierArray = $this->CI->Prodcat_model->hierMenuGetLinkingWithLevel($lastProdId);
            if(count($hierArray) === 0)
                return false;
            uasort($hierArray, function($a, $b) {
                if(!count($a))
                    return -1;
                if(!count($b))
                    return 1;
                // for each parent, compare the first child's (index 0) level value (index 3) 
                if($a[0][3] === $b[0][3])
                    return 0;
                return $a[0][3] < $b[0][3] ? -1 : 1;
            });
            $this->data['js']['hierData'][0] = array();
            $count = 0;
            $matchIndex = 0;
            foreach($hierArray as $parentId => $children)
            {
                $validChild = false;
                $this->data['js']['hierData'][$count] = array();
                foreach($children as $child)
                {
                    $validChild = true;
                    $value = $child[0];
                    $label = $child[1];
                    $hasChildren = $child[2];
                    $selected = ($categoryArray[$matchIndex] == $value);
                    if($selected)
                        $matchIndex++;
                    //parent is the node's parent id; hasChildren is a flag denoting whether a node has children
                    array_push($this->data['js']['hierData'][$count], array('value' => $value, 'label' => $label, 'parentID' => $parentId, 'selected' => $selected, 'hasChildren' => $hasChildren));
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
