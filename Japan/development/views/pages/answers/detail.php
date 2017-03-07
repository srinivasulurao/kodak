<rn:meta title="#rn:php:SEO::getDynamicTitle('answer', getUrlParm('a_id'))#" template="kodak_b2b_template.php" answer_details="true" clickstream="answer_view"/>
<div id="rn_PageTitle" class="rn_AnswerDetail">
    <h1 id="rn_Summary"><rn:field name="answers.summary" highlight="true"/></h1>
    <div id="rn_AnswerInfo">
        #rn:msg:ANS_ID_LBL# <rn:field name="answers.a_id" />
        &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        #rn:msg:PUBLISHED_LBL# <rn:field name="answers.created" />
        &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        #rn:msg:UPDATED_LBL# <rn:field name="answers.updated" />
        
<script>
var cert = '<rn:field name="answers.c$ek_certification" />'
//alert ('certification is '+cert);
if (cert == '承認済み') {
//	var lbl_cert = '<? echo $templ_msg_base_array['certified']; ?>';
    document.write('&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span style="color:green">'+cert+'</span>');
}
else if (cert == '未承認') {
//	var lbl_cert = '<? echo $templ_msg_base_array['notcertified']; ?>';
    document.write('&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span style="color:red">'+cert+'</span>');
}
</script> 
    </div>
    <rn:field name="answers.description" highlight="true"/>
</div>
<div id="rn_PageContent" class="rn_AnswerDetail">
    <div id="rn_AnswerText">
        <rn:field name="answers.solution" highlight="true"/>
    </div>
    <rn:widget path="knowledgebase/GuidedAssistant"/>
    <div id="rn_FileAttach">
        <rn:widget path="output/DataDisplay" name="answers.fattach" />
    </div>
    <rn:widget path="feedback/AnswerFeedback" label_dialog_description="#rn:php:$templ_msg_base_array['lbl_tell_answer_useful']#" label_comment_box="フィードバックを入力" />
    <br/>

    <rn:widget path="knowledgebase/RelatedAnswers" />
   <rn:widget path="knowledgebase/PreviousAnswers" /> 

    <rn:condition is_spider="false">
        <div id="rn_DetailTools">
            <rn:widget path="utils/PrintPageLink" />
        <!--    <rn:widget path="notifications/AnswerNotificationIcon" /> -->
        <rn:widget path="notifications/AnswerNotificationIcon" />
        </div>
    </rn:condition>
</div>

<!-- Changes -->
