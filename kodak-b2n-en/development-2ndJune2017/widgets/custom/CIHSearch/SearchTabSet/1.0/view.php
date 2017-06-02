<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
<?php
		$sesslang = get_instance()->session->getSessionData("lang");
		switch ($sesslang) {
        case "en":
			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
        case "fr":
			$cih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 
			break;
        case "es":
			$cih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 
			break;
        case "pt":
			$cih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 
			break;
        default:
			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 
			break;
		}						
?>
<div id="searchtabset" class="yui-navset">
  <ul class="yui-nav">
        <li id="left" class="selected"><a href="javascript:void(0)"><em><? echo $cih_lang_msg_base_array['bycustomer']; ?></em></a></li>
        <li id="right"><a href="javascript:void(0)"><em><? echo $cih_lang_msg_base_array['byproduct']; ?></em></a></li>
  </ul>            
  <div class="yui-content">
        <div id="custSearch">
          <rn:widget path="custom/CIHSearch/CustomerSearch" alt_text"Product ID" label_hint="Enter ID" report_page_url="/app/cih/search_home" />
        </div>
        <div id="prodSearch" style='display:none'>
        <rn:widget path="custom/CIHSearch/SimpleProductSearch" alt_text"Product ID" label_hint="#rn:php:$cih_lang_msg_base_array['entersearchvalue']#" report_page_url="/app/cih/search_home" />
        </div>
  </div>
</div>
<div id="scroll_target"></div>
</div>