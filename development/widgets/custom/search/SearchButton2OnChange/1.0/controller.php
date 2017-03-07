<?php
namespace Custom\Widgets\search;

class SearchButton2OnChange extends \RightNow\Widgets\SearchButton {
    function __construct($attrs) {
        parent::__construct($attrs);
    }

    function getData() {

    	$ci=get_instance();
		$contact=$ci->model('custom/profile_manager_model')->getContact();
		$prod_id=(getUrlParm('p'))?getUrlParm('p'):$contact->CustomFields->c->prod_id;
		$cat_id=(getUrlParm('c'))?getUrlParm('c'):$contact->CustomFields->c->cat_id;
		$search_text=(getUrlParm('kw'))?getUrlParm('kw'):$contact->CustomFields->c->search_text;
		$search_type=5;  //Excepted values are 6=>"Phrases",7=>"Similar Phrases",8=>"Exact Search",
		$st=$contact->CustomFields->c->search_type;
		
		 if($st->ID=889)
		  $search_type=6;
		 if($st->ID=890) 
		  $search_type=7;
		 if($st->ID=891) 
		  $search_type=6;
		 if($st->ID==892)
		  $search_type=5;
		   	
		$lines_per_page=(int)$contact->CustomFields->c->lines_per_page;

		$current_url=$_SERVER['REQUEST_URI'];
		if($current_url=="/app/answers/list"):
		    $url_params="/app/answers/list/st/".$search_type;
		    if($search_text)
		        $url_params.="/kw/$search_text";
		    if($prod_id)
		        $url_params.="/p/$prod_id";
		    if($cat_id)
		        $url_params.="/c/$cat_id";
		    if($search_text or $prod_id or $cat_id)
		        $url_params.="/page/2";
		header("Location:$url_params");
		exit;
		endif; 

        return parent::getData();

    }
}