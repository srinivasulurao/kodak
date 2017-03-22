<rn:meta title="#rn:php:\RightNow\Libraries\SEO::getDynamicTitle('answer', \RightNow\Utils\Url::getParameter('a_id'))#" template="standard.php" answer_details="true" clickstream="answer_view"/>

<div id="rn_PageTitle" class="rn_AnswerDetail">
    <h1 id="rn_Summary"><rn:field name="Answer.Summary" highlight="true"/></h1>
    <div id="rn_AnswerInfo">
        #rn:msg:PUBLISHED_LBL# <rn:field name="Answer.CreatedTime" />
        &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        #rn:msg:UPDATED_LBL# <rn:field name="Answer.UpdatedTime" />
    </div>
    <rn:field name="Answer.Question" highlight="true"/>
</div>
<div id="rn_PageContent" class="rn_AnswerDetail">
    <div id="rn_AnswerText">
        <rn:field name="Answer.Solution" highlight="true"/>
    </div>
    <rn:widget path="knowledgebase/GuidedAssistant"/>
    <div id="rn_FileAttach">
        <rn:widget path="output/DataDisplay" name="Answer.FileAttachments" label="#rn:msg:ATTACHMENTS_LBL#"/>
    </div>
    <rn:widget path="feedback/AnswerFeedback"/>
    <br/>
    <rn:widget path="knowledgebase/RelatedAnswers" />
    <rn:widget path="knowledgebase/PreviousAnswers" />
    <div id="rn_DetailTools">
        <rn:widget path="utils/SocialBookmarkLink" />
        <rn:widget path="utils/PrintPageLink" />
        <rn:widget path="utils/EmailAnswerLink" />
        <rn:condition logged_in="true">
            <rn:widget path="notifications/AnswerNotificationIcon" />
        </rn:condition>
    </div>
</div>
