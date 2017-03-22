<rn:meta title="#rn:php:SEO::getDynamicTitle('answer', getUrlParm('a_id'))#" template="newkodak_b2b_template.php" answer_details="true" clickstream="answer_view"/>

<div id="rn_PageTitle" class="rn_AnswerDetail">
    <h1 id="rn_Summary"><rn:field name="answers.summary" highlight="true"/></h1>
    <div id="rn_AnswerInfo">
        #rn:msg:ANS_ID_LBL# <rn:field name="answers.a_id" />
        &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        #rn:msg:PUBLISHED_LBL# <script>var_created = '<rn:field name="answers.created" />'; document.write(var_created.substr(0,11));</script>
        &nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
        #rn:msg:UPDATED_LBL# <script>var_created = '<rn:field name="answers.updated" />'; document.write(var_created.substr(0,11));</script>
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
    <rn:widget path="feedback/AnswerFeedback2" />
    <br/>
    <rn:widget path="knowledgebase/RelatedAnswers2" />
    <rn:widget path="knowledgebase/PreviousAnswers2" />
    <rn:condition is_spider="false">
        <div id="rn_DetailTools">
            <rn:widget path="utils/SocialBookmarkLink" />
            <rn:widget path="utils/PrintPageLink" />
            <rn:widget path="utils/EmailAnswerLink" />
			<rn:condition logged_in="true">
				<rn:widget path="notifications/AnswerNotificationIcon3" />
			</rn:condition>	
        </div>
    </rn:condition>
</div>
