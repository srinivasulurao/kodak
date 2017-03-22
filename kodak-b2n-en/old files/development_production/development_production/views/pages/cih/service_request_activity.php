<?	//		$CI =& get_instance();  //		$parmurllang = getURLParm("lang");//		if (($parmurllang == "en") || ($parmurllang == "fr") || ($parmurllang == "es") || ($parmurllang == "pt")   ) {//			$urllang = $parmurllang;//			$CI->session->setSessionData(array("lang" => $urllang));//		}		$sesslang = get_instance()->session->getSessionData("lang");		switch ($sesslang) {        case "en":			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 			break;        case "fr":			$cih_lang_msg_base_array=load_array("csv_cih_french_strings.php"); 			break;        case "es":			$cih_lang_msg_base_array=load_array("csv_cih_spanish_strings.php"); 			break;        case "pt":			$cih_lang_msg_base_array=load_array("csv_cih_portuguese_strings.php"); 			break;        default:			$cih_lang_msg_base_array=load_array("csv_cih_english_strings.php"); 			break;		}						?>	<rn:meta title="#rn:msg:SHP_TITLE_HDG#" template="kodak_b2b_template_fullwidth.php" clickstream="contact_create" login_required="true"/>
<div id="rn_PageTitle" class="rn_AskQuestion">
    <h1><? echo $cih_lang_msg_base_array['servicerequestactivity']; ?></h1>
<a href="/app/answers/detail/a_id/66890" target="_blank"><? echo $cih_lang_msg_base_array['viewhelp']; ?></a>
</div>
<br/>
<div id="rn_PageContent" class="rn_QuestionDetail">
    <div class="rn_Padding">
		<rn:widget path="custom/CIHFunction/ServiceRequestActivity" report_id="#rn:php:$cih_lang_msg_base_array['sra_repideksupporthistory']#" />
		<rn:widget path="custom/CIHFunction/ServiceRequestActivityHydrateFields" report_id="#rn:php:$cih_lang_msg_base_array['sra_repideksupporthistory']#" />	
	</div>
</div>

