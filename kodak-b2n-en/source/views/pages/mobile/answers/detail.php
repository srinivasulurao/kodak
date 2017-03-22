<rn:meta title="#rn:php:\RightNow\Libraries\SEO::getDynamicTitle('answer', \RightNow\Utils\Url::getParameter('a_id'))#" template="mobile.php" answer_details="true" clickstream="answer_view"/>
<section id="rn_PageTitle" class="rn_AnswerDetail">
    <h1 id="rn_Summary"><rn:field name="Answer.Summary" highlight="true"/></h1>
    <div id="rn_AnswerInfo">
        #rn:msg:PUBLISHED_LBL# <rn:field name="Answer.CreatedTime" />
        <br/>
        #rn:msg:UPDATED_LBL# <rn:field name="Answer.UpdatedTime" />
    </div>
    <rn:field name="Answer.Question" highlight="true"/>
</section>
<section id="rn_PageContent" class="rn_AnswerDetail">
    <div id="rn_AnswerText">
        <rn:field name="Answer.Solution" highlight="true"/>
    </div>
    <div id="rn_FileAttach">
        <rn:widget path="output/DataDisplay" name="Answer.FileAttachments" label="#rn:msg:ATTACHMENTS_LBL#"/>
    </div>
    <rn:widget path="knowledgebase/GuidedAssistant" popup_window_url="/app/utils/guided_assistant" label_text_result="#rn:msg:PLEASE_READ_THIS_RESPONSE_MSG#"/>
    <br/>
    <rn:widget path="feedback/MobileAnswerFeedback"/>
    <rn:widget path="knowledgebase/RelatedAnswers"/>
    <rn:widget path="utils/EmailAnswerLink"/>
</section>
