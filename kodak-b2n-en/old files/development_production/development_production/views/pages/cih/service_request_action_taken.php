<?
        $this->CI = & get_instance();
        $this->CI->load->model('custom/custom_incident_model');


        if(getUrlParm('corp_id') && getUrlParm('corp_id') != '')
          $verify = $this->CI->custom_incident_model->verifyCorpAccess(getUrlParm('obj_id'), getUrlParm('corp_id'));
        else
          $verify = $this->CI->custom_incident_model->verifyAccess(getUrlParm('obj_id'));

        if($verify == "0")
          header('Location: /app/error/error_id/6');
?>

<rn:meta title="Service Request Actions Taken" template="kodak_b2b_template_fullwidth.php" login_required="true" clickstream="incident_view"/>
<rn:widget path="CIHFunction/SwapReportController" />
<div id="rn_PageTitle" class="rn_AskQuestion">
    <h1>Service Request Actions Taken</h1>
</div>
<div id="rn_PageContent" class="rn_QuestionDetail">
    <div class="rn_Padding">
      
		<div class="rn_Accordion_container">
			<rn:widget path="CIHFunction/Accordion" item_to_toggle="panelContent" label_header="Service Request Events" name="servicerequestevents" expanded="true"/>
			<div id="panelContent" style="padding:10px;">
				<rn:widget path="custom/reports/Grid2JSSort"  report_id="100823"/>
			</div>
		</div>
		
		<div  class="rn_Accordion_container">
			<rn:widget path="CIHFunction/Accordion" item_to_toggle="pnlActions" label_header="Actions" name="actions" expanded="true"/>
			<div id="pnlActions" style="padding:10px;">	
				<h2 class="rn_HeadingBar">Labor</h2>
				<div id="rn_QuestionThread">
					<rn:widget path="custom/reports/Grid2JSSort"  report_id="100821"/>					
				</div>
		
				<h2 class="rn_HeadingBar">Parts</h2>
				<div id="rn_QuestionThread">
					<rn:widget path="custom/reports/Grid2JSSort"  report_id="100822"/>
				</div>
		
				<h2 class="rn_HeadingBar">Description</h2>
				<div id="rn_QuestionThread">
					<rn:widget path="custom/reports/Grid2JSSort"  report_id="100820"/>
				</div>
			</div>
		
		</div>
        
        <div id="rn_DetailTools">
            <rn:widget path="utils/PrintPageLink" />
        </div>
    </div>
	
</div>


