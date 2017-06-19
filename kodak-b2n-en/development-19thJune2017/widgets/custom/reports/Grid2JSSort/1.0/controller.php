<?php
namespace Custom\Widgets\reports;
use RightNow\Connect\v1_2 as RNCPHP;
//require_once( get_cfg_var( 'doc_root' ).'/include/ConnectPHP/Connect_init.phph' );
//initConnectAPI();
use RightNow\Utils\Connect,
    RightNow\Utils\Framework,
    RightNow\Utils\Text,
    RightNow\Utils\Config,
    RightNow\Api;


class Grid2JSSort extends  \RightNow\Libraries\Widget\Base {
	
    public function __construct($attrs) {
        parent::__construct($attrs);
    }

    public function getData() {

        //$this->data['js']['initialValue']=$this->attrs['default_value']->value;
		$report_id=$this->attrs['report_id']->value;
		$i_id=getUrlParm('obj_id')?getUrlParm('obj_id'):getUrlParm('i_id');
		
		    $filters = new RNCPHP\AnalyticsReportSearchFilterArray;
			$filter = new RNCPHP\AnalyticsReportSearchFilter;
			$filter->Name = "obj_id";
			$filter->Operator = new RNCPHP\NamedIDOptList();
			$filter->Operator->ID = 1; // this the equal operator.
			$filter->Values = array($i_id);
			$filters[] = $filter;

			$ar= RNCPHP\AnalyticsReport::fetch($report_id);
			$arr= $ar->run(0,$filters);
			$this->data['js']['cols']=array();
			$this->data['js']['rows']=array();
			$col_counter=0;
			while($rec=$arr->next()){
				$row_counter=0;
				foreach($rec as $key=>$value):
				  $this->data['js']['cols'][$row_counter]=$key;
				  $this->data['js']['rows'][$col_counter][$key]=$value;
				  $row_counter++;
				endforeach;
			$col_counter++;
		     break;
			}
			
			/* echo "<pre>";
			print_r($this->data); */

        return parent::getData();

    }
	
}