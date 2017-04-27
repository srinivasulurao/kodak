<rn:meta title="#rn:msg:SHP_TITLE_HDG#" template="newkodak_b2b_template.php" clickstream="home" login_required="true" />
<?
//		$CI =& get_instance();  
//		$parmurllang = getURLParm("lang");
//		if (($parmurllang == "en") || ($parmurllang == "fr") || ($parmurllang == "es") || ($parmurllang == "pt")   ) {
//			$urllang = $parmurllang;
//  	        $CI->session->setSessionData(array("lang" => $urllang));
//		}
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

<div id="rn_PageTitle" class="rn_Home">
    <h1><? echo $cih_lang_msg_base_array['servicemyproducts']; ?></h1>
<a href="/app/answers/detail/a_id/66890" target="_blank"><? echo $cih_lang_msg_base_array['viewhelp']; ?></a>
</div>
<div id="rn_PageContent" class="rn_Home">
     <rn:widget path="custom/CIHSearch/SearchTabSet" />
</div>

<style type="text/css">
#rn_SideBar{
	display:none !important;
}
#rn_MainColumn{
	width: 98.5% !important;
}
/* .rn_Hidden{
  display: block !important;

}
#panelIbaseUpdate2 .rn_Hidden,#panelIbaseUpdate .rn_Hidden, #panelRepairRequest2 .rn_Hidden,#panelRepairRequest .rn_Hidden,#panelManageContacts2 .rn_Hidden, #panelManageContacts .rn_Hidden{
	display:none !important;
} */

tr{
	z-index:500 !important;
}
tr a{
	z-index:1000 !important;
}

</style>