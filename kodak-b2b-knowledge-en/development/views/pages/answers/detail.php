<rn:meta title="#rn:php:SEO::getDynamicTitle('answer', getUrlParm('a_id'))#" template="kodak_b2b_template.php" answer_details="true" clickstream="answer_view"/>
<div id="rn_PageTitle" class="rn_AnswerDetail">
    <h1 id="rn_Summary"><rn:field name="answers.summary" highlight="true"/></h1>
    <div id="rn_AnswerInfo">
        #rn:msg:ANS_ID_LBL# <rn:field name="answers.a_id" />
        &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        #rn:msg:PUBLISHED_LBL# <rn:field name="answers.created" /> GMT
        &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        #rn:msg:UPDATED_LBL# <rn:field name="answers.updated" /> GMT
        
<script>
var cert = '<rn:field name="answers.c$ek_certification" />'
//alert ('certification is '+cert);
if (cert == 'Certified') {
    document.write('&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span style="color:green">'+cert+'</span>');
}
else if (cert == 'Not Certified') {
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
    <rn:widget path="knowledgebase/RelatedAnswers" label_title="#rn:php:$templ_msg_base_array['lbl_related_manual']#" relatedlinksonly="true" limit="20" />
    <rn:widget path="feedback/AnswerFeedback2" label_dialog_description="#rn:php:$templ_msg_base_array['lbl_tell_answer_useful']#" label_comment_box="My Feedback" />
    <br/>
    <rn:widget path="knowledgebase/RelatedAnswers"  label_title="#rn:php:$templ_msg_base_array['lbl_related_learned']#" relatedlinksonly="false" />
    <!--<rn:widget path="knowledgebase/PreviousAnswers" /> -->
    <rn:condition is_spider="false">
        <div id="rn_DetailTools">
            <img src='images/Print.png'><rn:widget path="utils/PrintPageLink" />
            <img src="images/Notification.png" alt=""><rn:widget path="notifications/AnswerNotificationIcon3" />
        </div>
    </rn:condition>
</div>
