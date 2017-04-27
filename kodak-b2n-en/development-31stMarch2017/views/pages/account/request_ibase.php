<rn:meta title="#rn:msg:ACCOUNT_SETTINGS_LBL#" template="kodak_b2b_template.php" login_required="true" />
<div id="rn_PageContent" class="rn_Profile">
<!-- <rn:widget path="custom/output/WaitingPanel" /> -->
<rn:widget path="standard/utils/AnnouncementText2" label_heading="Product Support" file_path="/euf/assets/RequestService.html" />

<?php 
$CI = &get_instance();
 $profile = $CI->session->getProfile();
 $c_id = $profile->c_id->value;
 $org_id = $profile->org_id->value;
 
 ?>
<rn:widget path="reports/Grid"  report_id="100309"/>
<rn:widget path="reports/paginator" report_id="100309" />
</div>
