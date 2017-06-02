<?php
namespace Custom\Widgets\CIHFunction;

use RightNow\Utils\Url,
    RightNow\Utils\Text,
    RightNow\Connect\v1_1 as RNCPHP;
require_once(get_cfg_var('doc_root').'/include/ConnectPHP/Connect_init.phph');
initConnectAPI('srinivasulu','Tirupati$wami123');
class ProblemFound extends \RightNow\Libraries\Widget\Base{
    public $AllCategories;
    function __construct($attrs) {
        $this->AllCategories=array();
        $this->initCategories();
        parent::__construct($attrs);
    }

    function getData() {

        // parent::getData();
        // $dataType = $this->data['js']['data_type'] = (Text::stringContains(strtolower($this->fieldName), 'prod'))
        //     ? self::PRODUCT
        //     : self::CATEGORY;
        // $isProduct = ($dataType === self::PRODUCT);
        // if(!$isProduct):
        //     $this->data['js']['hierData']=$this->ShowAllCats();
        //     $this->data['js']['hierDataNone']=$this->ShowAllCats();
        //     $this->data['js']['link_map']=$this->ShowAllCats();
        // endif;

        // $product_ids=trim("1481,2232,2947");
        // $analytics_report_id=102684;

        // $product_array=explode(",",$product_ids);
        
        
        // $filter = new RNCPHP\AnalyticsReportSearchFilter;
        // $filter->Name = "SelectedProduct";
        // $filter->Operator = new RNCPHP\NamedIDOptList();
        // $filter->Operator->ID = 2; // this the OR operator.
        // $filter->Values = $product_array;

        // $filters = new RNCPHP\AnalyticsReportSearchFilterArray;
        // $filters[] = $filter;

        // $ar= RNCPHP\AnalyticsReport::fetch($analytics_report_id);
        // $arr= $ar->run(0,$filters);
        // $total_records=0;
        // $cat_data=array();
        // while($res=$arr->next()){   
        //     //$parent=$this->AllCategories[$res['cat_id']];
        //     //$this->d($parent);
        //     $cat_data[]=$res;
        //     $total_records++;
        // }

        // $this->d($cat_data);


        //Lets build a tree by our self.

        $cat_data=array();
        foreach($this->AllCategories as $key=>$value):
            $parent=($value['Parent']=="")?"empty":$value['Parent'];
            $cat_data[$parent][]=$value;
        endforeach;

         // total 6 levels are there.
        //Level_1
        $categories=array();

        foreach($cat_data['empty'] as $key=>$value):
            $categories['lvl_1'][]=$value;
        endforeach;    

        //level 2.
        foreach($categories['lvl_1'] as $key1=>$value1):
            $categories['lvl_1'][$key1]['lvl_2']=$cat_data[$value1['ID']];
        endforeach;     

        //level 3.
        foreach($categories['lvl_1'] as $key1=>$value1):
            if(sizeof($value1['lvl_2'])):
            foreach($value1['lvl_2'] as $key2=>$value2):
                $categories['lvl_1'][$key1]['lvl_2'][$key2]['lvl_3']=$cat_data[$value2['ID']];
            endforeach;    
            endif;
        endforeach;    

        //level 4.
        foreach($categories['lvl_1'] as $key1=>$value1):
            if(sizeof($value1['lvl_2'])):
            foreach($value1['lvl_2'] as $key2=>$value2):
                 if(sizeof($value2['lvl_3'])):
                 foreach($value2['lvl_3'] as $key3=>$value3):
                    $categories['lvl_1'][$key1]['lvl_2'][$key2]['lvl_3'][$key3]['lvl_4']=$cat_data[$value3['ID']];
                 endforeach;   
                 endif;   
            endforeach;    
            endif;
        endforeach;

        //level 5.
        foreach($categories['lvl_1'] as $key1=>$value1):
            if(sizeof($value1['lvl_2'])):
            foreach($value1['lvl_2'] as $key2=>$value2):
                 if(sizeof($value2['lvl_3'])):
                 foreach($value2['lvl_3'] as $key3=>$value3):
                      if(sizeof($value3['lvl_4'])):
                      foreach($value3['lvl_4'] as $key4=>$value4):
                          $categories['lvl_1'][$key1]['lvl_2'][$key2]['lvl_3'][$key3]['lvl_4'][$key4]['lvl_5']=$cat_data[$value4['ID']];
                      endforeach;   
                      endif;        
                 endforeach;   
                 endif;   
            endforeach;    
            endif;
        endforeach;
        
        //Usually no one uses this, but still.
        //Level 6,
        foreach($categories['lvl_1'] as $key1=>$value1):
            if(sizeof($value1['lvl_2'])):
            foreach($value1['lvl_2'] as $key2=>$value2):
                 if(sizeof($value2['lvl_3'])):
                 foreach($value2['lvl_3'] as $key3=>$value3):
                      if(sizeof($value3['lvl_4'])):
                      foreach($value3['lvl_4'] as $key4=>$value4):
                           if(sizeof($value4['lvl_5'])):
                           foreach($value4['lvl_5'] as $key5=>$value5):
                                $categories['lvl_1'][$key1]['lvl_2'][$key2]['lvl_3'][$key3]['lvl_4'][$key4]['lvl_5'][$key5]['lvl_6']=$cat_data[$value5['ID']];
                            endforeach;   
                           endif;
                      endforeach;   
                      endif;        
                 endforeach;   
                 endif;   
            endforeach;    
            endif;
        endforeach;

        $this->data['categories']=$categories;

    }

    function initCategories(){
        $cat_query=RNCPHP\ROQL::query("SELECT * FROM ServiceCategory")->next();
        $categories=array();

        while($res=$cat_query->next()):
            $this->AllCategories[]=$res;
        endwhile;   
    }

    function d($ao){
        echo "<pre>";
        print_r($ao);
        echo "</pre>";
    }

    /**
     * Overridable methods from ProductCategoryInput:
     */
    // protected function getDefaultChain()
    // protected function pruneEmptyPaths(array $hierMap, array $defaultChain = array())
    // protected function buildListOfPermissionedProdcatIds()
    // protected function getProdcatInfoFromPermissionedHierarchies(array $prodcatHierarchies)
    // protected function updateProdcatsForReadPermissions(array &$prodcats, array $readableProdcatIds, array $readableProdcatIdsWithChildren)

    /**
     * New methods:
     */

    private function ShowAllCats(){
        //Srini's List for Category,i will load all the categories.
        $defaultHierMap=array();
        $defaultHierMap[0][0]=array('id'=>"","label"=>"Select Problem");
        $cat_query=RNCPHP\ROQL::query("SELECT * FROM ServiceCategory ORDER BY LookupName")->next();
        $parent_array=array();
        while($res=$cat_query->next()){
           $pushArray=array();
           $pushArray['id']=$res['ID'];
           $pushArray['label']=$res['LookupName'];
           $pushArray['selected']="";
           if($res['Parent'])
            $parent_array[]=$res['Parent'];
           $defaultHierMap[0][]=$pushArray;
        }
        
        $defaultHierMap[0]=$this->hasChildren($defaultHierMap[0],$parent_array);

        return $defaultHierMap; 
    }

    private function hasChildren($defaultHierMap,$parent_array){
      //Need to make the query faster hence ROQL is not a good idea.
        $new_data=array();
        $new_data[0]=array('id' => 0, 'label' => $this->data['attrs']['label_all_values']);
        $counter=1;
      foreach($defaultHierMap as $key=>$value):
        //if(in_array($value['id'],$parent_array)):
            $value['hasChildren']=0;
            $new_data[$counter]=$value; 
            $counter++;
        //endif;
      endforeach; 

      return $new_data;

    }

} //Class Ends here.