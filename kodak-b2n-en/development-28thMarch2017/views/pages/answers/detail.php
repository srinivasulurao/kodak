<rn:meta title="#rn:php:SEO::getDynamicTitle('answer', getUrlParm('a_id'))#" template="kodak_b2b_template.php" answer_details="true" clickstream="answer_view"/>

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
    <rn:widget path="knowledgebase/RelatedAnswers" />
    <!--    <rn:widget path="knowledgebase/PreviousAnswers" />-->
    <rn:condition is_spider="false">
        <div id="rn_DetailTools">
            <img src="images/Share.png" alt=""> <rn:widget path="utils/SocialBookmarkLink" sites="
				Delicious > Post to Delicious > http://del.icio.us/post?url=|URL|&title=|TITLE|, 
				Digg > Post to Digg > http://digg.com/submit?url=|URL|&title=|TITLE|,
				Facebook > Post to Facebook > http://facebook.com/sharer.php?u=|URL|,
				Reddit > Post to Reddit > http://reddit.com/submit?url=|URL|&title=|TITLE|, 
				StumbleUpon > Post to StumbleUpon > http://stumbleupon.com/submit?url=|URL|&title=|TITLE|, 
				Twitter > Tweet this > http://twitter.com/home?status=|TITLE| |URL| /">
         <img src="images/Print.png" alt="">    <rn:widget path="utils/PrintPageLink" />
           <img src="images/Email.png" alt="">  <rn:widget path="utils/EmailAnswerLink" />
			 <img src="images/Notification.png" alt=""> <rn:condition logged_in="true">
				<rn:widget path="notifications/AnswerNotificationIcon3" />
			</rn:condition>	
        </div>
    </rn:condition>
</div>
