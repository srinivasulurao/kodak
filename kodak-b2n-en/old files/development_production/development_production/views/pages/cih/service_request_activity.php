<?	
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
