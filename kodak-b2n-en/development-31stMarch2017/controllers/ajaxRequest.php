<?php
namespace Custom\Controllers;

class ajaxRequest extends \RightNow\Controllers\Base
{
    function __construct() {
        parent::__construct();

        parent::_setClickstreamMapping(array(
            "getReportData" => "report_data_service",
            "emailAnswer" => "email_answer",
            "submitFeedback" => "feedback",
            "submitSiteFeedback" => "site_feedback",
            "submitAnswerFeedback" => "answer_feedback",
            "submitAnswerRating" => "answer_rating",
            "answerNotification" => "answer_notification",
            "prodcatAddNotification" => "product_category_notification",
            "prodcatDeleteNotification" => "product_category_notification_delete",
            "prodcatRenewNotification" => "product_category_notification_update",
            "sendForm" => "incident_submit",
            "doLogin" => "account_login",
            "doLogout" => "account_logout",
            "getAnswer" => "answer_view"
        ));

        // Allow account creation, account recovery, and login stuff for users who aren't logged in if CP_CONTACT_LOGIN_REQUIRED is on.
        parent::_setMethodsExemptFromContactLoginRequired(array(
            'emailPassword',
            'emailUsername',
            'sendForm',
            'resetPassword',
            'checkForExistingContact', // Part of the account creation process.
            'doLogin',
        ));
    }

    /**
     * Special case to handle requests to getGuidedAssistanceTree when made from
     * the agent console.
     */
    function _ensureContactIsAllowed() {
        if($this->uri->router->fetch_method() === 'getGuidedAssistanceTree' && is_object($this->_getAgentAccount()))
            return true;
		else
			return parent::_ensureContactIsAllowed();
    }

    /**
     * Retrieves post data and gets report information
     * @return
     */
    function getReportData() {
        $filters = $this->input->post('filters');
        $filters = json_decode($filters);

        //2013.05.2 scott harris: logic to switch org id for the reporting incidents based on request for sap cust id
        $internal = $this->model('custom/custom_contact_model')->checkInternalContact($this->session->getProfileData('c_id'));
		if($internal == 'Y' && is_object($filters->CustomTextInput_14->filters->data) && is_object($filters->org->filters->data)) {
			if($filters->CustomTextInput_14->filters->data->val != "") {
				$requestedOrgID = $this->model('custom/custom_contact_model')->getOrgBySAPID($filters->CustomTextInput_14->filters->data->val);
				$filters->org->filters->data->val = $requestedOrgID;
			}
		}
  
        $corp_request = false;
        $corp_id = 0;
		if($internal == 'N' && $filters->org->filters->data->fltr_id == 13) {
			$corp_request = true;
			$corp_id = $filters->org->filters->data->val;
		}
		if($internal == 'N' && $filters->org->filters->data->fltr_id == 14) {
			$corp_request = true;
			$corp_id = $filters->org->filters->data->val;
		}
		if($internal == 'N' && $filters->org->filters->data->fltr_id == 15) {
			$corp_request = true;
			$corp_id = $filters->org->filters->data->val;
		}
		if($internal == 'N' && $filters->org->filters->data->fltr_id == 18) {
			$corp_request = true;
			$corp_id = $filters->org->filters->data->val;
		}
		//negate corp filter
		if($internal == 'Y' && $filters->org->filters->data->fltr_id == 18) {
			$filters->org->filters->data->val = "";
		}
		//negate c_id filter
		if($internal == 'Y' && $filters->org->filters->data->fltr_id == 'incidents.c_id') {
			$filters->org->filters->data->val = "";
		}

        $filters = get_object_vars($filters);
        logmessage($filters);

        $reportID = $this->input->post('report_id');
        $reportToken = $this->input->post('r_tok');
        $format = $this->input->post('format');
        $format = get_object_vars(json_decode($format));

        if($filters['search'] == 1)
            $this->model('standard/Report_model')->updateSessionforSearch();
		
        $results = $this->model('standard/Report_model')->getDataHTML($reportID, $reportToken, $filters, $format);
        /*
         * This request cannot be cached because not all rules that define how the page is rendered are in the POST data:
         * User search preferences, such as the number of results per page, are stored in the contacts table.
         * The Ask a Question tab may be hidden if the user has not searched enough times.
         * The user's profile is updated when they do a search.
         */
		if($corp_request) {
			for($i=0; $i<count($results['data']); $i++) {
				$results['data'][$i][9] = str_replace('service_request_detail',"service_request_detail/corp_id/$corp_id",$results['data'][$i][9]);
			} 
		}
		echo json_encode($results);
    }

    /**
    * Retrieves guided assistance tree specified by guide ID.
    * @return
    */
    function getGuidedAssistanceTree() {
        $guideID = intval($this->input->request('guideID'));
        $langID = intval($this->input->request('langID'));
        sendCachedContentExpiresHeader();
        echo json_encode($this->model('standard/Guidedassistance_model');->getAsArray($guideID, $langID));
    }
    
    /**
     * Retrieves intent guide and community search results.
     * @return JSON-encoded result set
     */
    function getCombinedSearchResults() {
        sendCachedContentExpiresHeader();

        if ($this->input->request('intentGuide') === 'true') {
            $intentGuideRequest = $this->_requestAsyncIntentGuideData();
        }
        if ($this->input->request('social') === 'true') {
            $socialRequest = $this->_requestAsyncCommunitySearch();
        }
        
        $results = array();
        if ($intentGuideRequest) {
            $results['intentGuide'] = $intentGuideRequest->getResponse();
        }
        if ($socialRequest) {
            $socialResults = $socialRequest->getResponse();
            $socialResults->searchResults = $this->model('standard/Social_model')->formatSearchResults(
                $socialResults->searchResults, $this->input->request('truncate'), $this->input->request('highlight') === 'true', 
                $this->input->request('keyword'), $this->input->request('baseUrl') ?: ''
            );
            $results['social'] = array(
                'data' => $socialResults,
                'ssoToken' => communitySsoToken(''),
            );
            
        }

        echo json_encode($results);
    }
    
    /**
     * Retrieves intent guide data.
     * @return JSON encoded result set
     */
    function getIntentGuideData() {
        $keyword = $this->input->request('keyword');
        $highlight = ($this->input->request('highlight') === 'true');
        $truncate = $this->input->request('truncate');
        $category = $this->input->request('category');
        $cutoff = $this->input->request('cutoff');
        $cutoff = min(max($cutoff, 1), 20);
        $siteref = $this->input->request('siteref');
        $referrer = $this->input->request('referrer');
        
        sendCachedContentExpiresHeader();
        
        echo json_encode($this->model('standard/Intentguide_model')->getSearchResults($keyword, array(
            'highlight' => $highlight, 
            'truncate' => $truncate, 
            'siteref' => $siteref, 
            'referrer' => $referrer, 
            'category' => $category,
            'callback' => function($result, $key) use ($keyword, $truncate, $highlight, $cutoff) {
                if(($key + 1) <= $cutoff && $result->question->type === 'Answer' && !$result->answers[0]->teaser) {
                    // if any of the cutoff results are answers, add its summary as the teaser
                    $CI = get_instance();
                    if($answer = $CI->model('standard/Answer_model')->get($result->answers[0]->answerID, new HTMLFormatter($keyword, true))) {
                        $result->answers[0]->teaser = truncateText($answer->description->value, $truncate);
                        if($highlight)
                            $result->answers[0]->teaser = emphasizeText($result->answers[0]->teaser, array('query' => $keyword));
                    }
                }
            },
        )));
    }

    /**
     * Records intent guide answer clicks.
     * @return 1
     */
    function recordIntentGuideInteraction() {
        echo json_encode(1); // don't wait for slow API call before responding
        $questionID = $this->input->post('questionID');
        $answerID = $this->input->post('answerID');
        $guideInteractionGuid = $this->input->post('guideInteractionGuid');
        $referrer = $this->input->post('referrer');
        $this->model('standard/Intentguide_model')->recordGuideInteraction($questionID, $answerID, $guideInteractionGuid, $referrer);
    }


    /**
     * Retrieves community data based the post parameters
     * passed in
     * @return JSON encoded result set
     */
    function getCommunityData() {
        $keyword = $this->input->request('keyword');
        if($keyword){
            incrementNumberOfSearchesPerformed();
        }
        $limit = $this->input->request('limit');
        $highlight = ($this->input->request('highlight') === 'true') ? true : false;
        $truncateSize = $this->input->request('truncate');
        $resourceID = $this->input->request('resource') ?: null;
        $start = $this->input->request('start') ?: null;
        $baseUrl = $this->input->request('baseUrl') ?: '';
        $paginateResults = $this->input->request('paginateResults') ?: false;
        $results = array('ssoToken' => communitySsoToken(''));
        $modelResults = $this->model('standard/Social_model')->getCachedSearchResults($keyword, $limit, null, $resourceID, null, $start, $paginateResults);
        if($paginateResults) {
            $modelResults->searchResults = $this->model('standard/Social_model')->formatSearchResults($modelResults->searchResults, $truncateSize, $highlight, $keyword, $baseUrl);
            $results['data'] = $modelResults;
        } else {
            $results['data'] = $this->model('standard/Social_model')->formatSearchResults($modelResults, $truncateSize, $highlight, $keyword, $baseUrl);
        }
        sendCachedContentExpiresHeader();
        echo json_encode($results);
    }
    
    /**
    * Retrieves an answer (containing all business object fields) specified by the answer id.
    * @return JSON encoded answer
    */
    function getAnswer() {
        AbuseDetection::check();
        $answerID = $this->input->post('answerID');
        $this->session->setSessionData(array('answersViewed' => $this->session->getSessionData('answersViewed') + 1));
        sendCachedContentExpiresHeader();
        // This request cannot be cached because of session tracking and conditional sections
        echo json_encode($this->model('standard/Answer_model')->get($answerID, new HTMLFormatter()));
    }

    /**
    * Retrieves a community post specified by the post id.
    * @return JSON encoded post
    */
    function getCommunityPost() {
        AbuseDetection::check();
        $postID = $this->input->request('postID');
        sendCachedContentExpiresHeader();
        echo json_encode($this->model('standard/Social_model')->getCachedCommunityPost($postID));
    }
    
    /**
    * Retrieves a community post specified by the post id.
    * @return JSON encoded post
    */
    function getPostComments() {
        AbuseDetection::check();
        $postID = $this->input->request('postID');
        sendCachedContentExpiresHeader();
        echo json_encode($this->model('standard/Social_model')->getCachedPostComments($postID));
    }
    
    /**
     * Submits an action on an answer comment
     * @return string JSON encoded results
     */
    function submitAnswerCommentAction() {
        // Ernie and I talked through this and decided to track all of the answer comment actions.  
        // You might think that we'd only need to track actions which post content, 
        // but a griefer could cause a lot of work by flagging or rating down a bunch of posts.
        AbuseDetection::check();
        $action = $this->input->post('action');
        $data = json_decode($this->input->post('data'));
        $answerID = $this->input->post('answerID');
        echo json_encode($this->model('standard/Social_model')->performAnswerCommentAction($answerID, $action, $data));
    }
    
    /**
     * Submits an action on a post comment
     * @return string JSON encoded results
     */
    function submitPostCommentAction() {
        AbuseDetection::check();
        $action = $this->input->post('action');
        $data = json_decode($this->input->post('data'));
        $postID = $this->input->post('postID');
        echo json_encode($this->model('standard/Social_model')->performPostCommentAction($postID, $action, $data));
    }
    
    /**
     * Submits the contents of a community post
     * @return string JSON encoded results
     */
    function submitCommunityPost() {
        AbuseDetection::check();
        $title = json_decode($this->input->post('title'));
        $body = json_decode($this->input->post('body'));
        $postTypeID = $this->input->post('postTypeID');
        $resourceHash = $this->input->post('resourceHash');
        echo json_encode($this->model('standard/Social_model')->submitPost($postTypeID, $resourceHash, $title, $body));
    }

    /**
     * Request to email current answer
     * @return
     */
    function emailAnswer() {
        AbuseDetection::check();
        $to = $this->input->post('to');
        $from = $this->input->post('from');
        $answerID = $this->input->post('a_id');
        $name = $this->input->post('name');
        echo json_encode($this->model('standard/Field_model')->emailToFriend($to, $name, $from, $answerID));
    }

    /**
     * Request to reset password given a username
     * @return
     */
    function emailPassword() {
        AbuseDetection::check();
        $login = $this->input->post('login');
        $result = $this->model('standard/Contact_model')->emailContactPassword($login);
        echo json_encode($result);
    }

    /**
     * Request to email username to user given a email address
     * @return
     */
    function emailUsername() {
        AbuseDetection::check();
        $email = $this->input->post('email');
        $result = $this->model('standard/Contact_model')->emailContactLogin($email);
        echo json_encode($result);
    }

    /**
     * Request for when feedback is submitted
     * @return
     * @deprecated Nov 2009
     */
    function submitFeedback() {
        AbuseDetection::check();
        $answerID = $this->input->post('a_id');
        if($answerID == "null")
            $answerID = null;
        $rate = $this->input->post('rate');
        $name = $this->input->post('name');
        $message = $this->input->post('message');
        $givenEmail = $this->input->post('email');
        $threshold = $this->input->post('threshold');
        $submitfeedback = $this->input->post('submitfeedback');
        //only create the incident if the user actually submitted the email/message
        if($submitfeedback) {
            $results = $this->model('standard/Incident_model')->submitFeedback($answerID, $rate, $threshold, $name, $message, $givenEmail);
            $results = json_encode($results);
        }
        echo $results;
    }

    /**
     * Site feedback request.
     * @return
     */
    function submitSiteFeedback() {
        AbuseDetection::check();
        $answerID = null;
        $rate = $this->input->post('rate');
        $name = null;
        $message = $this->input->post('message');
        $givenEmail = $this->input->post('email');
        // Do not submit if post data is missing
        if (count($_POST))
            $results = $this->model('standard/Incident_model')->submitFeedback($answerID, $rate, $threshold, $name, $message, $givenEmail);
        echo json_encode($results);
    }

    /**
     * Answer feedback request.
     * @return
     */
    function submitAnswerFeedback() {
        AbuseDetection::check();
        $answerID = $this->input->post('a_id');
        if($answerID === 'null')
            $answerID = null;
        $rate = $this->input->post('rate');
        $name = null;
        $message = $this->input->post('message');
        $givenEmail = $this->input->post('email');
        $threshold = $this->input->post('threshold');
        $optionsCount = $this->input->post('options_count');
        // Do not submit if post data is missing
        if (count($_POST))
            $results = $this->model('standard/Incident_model')->submitFeedback($answerID, $rate, $threshold, $name, $message, $givenEmail, $optionsCount);
        echo json_encode($results);
    }

    /**
     * Answer rating request
     * Function provides a place for catching clickstreams data.
     * @return
     */
    function submitAnswerRating() {
        echo json_encode(1);
    }

    /**
     * Request to create a new answer notification
     * @return
     */
    function answerNotification() {
        AbuseDetection::check();
        $answerID = $this->input->post('a_id');
        $status = $this->input->post('status');
        //user isn't logged in--coming from email link
        $cid = $this->input->post('cid');
        $results = $this->model('standard/Contact_model')->answerNotification($answerID, $status, $cid);
        echo json_encode($results);
    }

    /**
     * Request to create a new prod/cat notification
     * @return
     */
    function prodcatAddNotification() {
        AbuseDetection::check();
        $values = $this->input->post('chain');
        $filter = $this->input->post('filter_type');
        //user isn't logged in--coming from email link
        $cid = $this->input->post('cid');
        $results = $this->model('standard/Notification_model')->add($filter, $values, $cid);
        echo json_encode($results);
    }

    /**
     * Request to delete a prod/cat notification
     * @return
     */
    function prodcatDeleteNotification() {
        AbuseDetection::check();
        $values = $this->input->post('chain');
        $filter = $this->input->post('filter_type');
        $time = $this->input->post('timestamp');
        $hm = $filter . ":" . $values . ":" . $time;
        //user isn't logged in--coming from email link
        $cid = $this->input->post('cid');
        $results = $this->model('standard/Notification_model')->del($hm, $cid);
        echo json_encode($results);
    }

    /**
     * Request to renew a notification
     * @return
     */
    function prodcatRenewNotification() {
        AbuseDetection::check();
        $values = $this->input->post('chain');
        $filter = $this->input->post('filter_type');
        $time = $this->input->post('timestamp');
        $hm = $filter . ":" . $values . ":" . $time;
        $results = $this->model('standard/Notification_model')->renew($hm);
        echo json_encode($results);
    }

    /**
     * Generic form submission handler for submitting incidents
     * and contacts
     * @return
     */
    function sendForm() {
        AbuseDetection::check($this->input->post('f_tok'));
        $data = json_decode($this->input->post('form'));
        if(!$data)
            writeContentWithLengthAndExit(json_encode(getMessage(JAVASCRIPT_ENABLED_FEATURE_MSG)));
        $incidentID = $this->input->post('i_id');
        $smartAssistant = $this->input->post('smrt_asst');
        $results = $this->model('standard/Field_model')->sendForm($data, $incidentID, $smartAssistant);
        echo json_encode($results);
    }
    
    /**
    * Retrieves a new form token.
    * @return JSON-encoded object
    * @private
    */
    function getNewFormToken() {
        if($formToken = $this->input->post('formToken')) {
            echo json_encode(array(
                'newToken' => cpCreateTokenExp(0, doesTokenRequireChallenge($formToken))
            ));
        }
    }

    /**
    * Handles case where non-logged in user is resetting their password
    * @return
    */
    function resetPassword() {
        AbuseDetection::check();
        $data = json_decode($this->input->post('form'));
        if(!$data)
            writeContentWithLengthAndExit(json_encode(getMessage(JAVASCRIPT_ENABLED_FEATURE_MSG)));
        $passwordParameter = $this->input->post('pw_reset');
        $results = $this->model('standard/Field_model')->resetPassword($data, $passwordParameter);
        echo json_encode($results);
    }

    /**
    * Checks that a contact doesn't already exist with the specified email or login
    * @return
    */
    function checkForExistingContact() {
        // This usually gets called from a blur handler when the user tabs out of a form field.  
        // That'd be a really awkward time to show a CAPTCHA dialog. Instead, I just report that the 
        // contact doesn't exist. Server-side validation will report the error when the form is 
        // submitted. This appproach not only avoids annoying users, but also limits the ability of a 
        // bad guy to launch a dictionary attack to determine the content of our contacts database. The
        // scenario where this is called is during the modified AAQ workflow. In that case, we really do
        // want a real answer, and are willing to show a CAPTCHA to get it. To do that, we post an 
        // additional field to say we really want an abuse check to be returned.
        if($this->input->post('checkForChallenge')){
            AbuseDetection::check();
        } else if (AbuseDetection::isAbuse()) {
            writeContentWithLengthAndExit(json_encode(false));
        }
        $pwReset = $this->input->post('pwReset');
        if($email = $this->input->post('email')) {
            $paramType = 'email';
            $param = $email;
        } elseif(!is_null($login = $this->input->post('login'))) {
            $paramType = 'login';
            $param = $login;
        }
        $results = $this->model('standard/Contact_model')->contactAlreadyExists($paramType, $param, $pwReset);
        echo json_encode($results);
    }

    /**
     * Handle user logins
     * @return
     */
    function doLogin() {
        AbuseDetection::check();
        $userID = $this->input->post('login');
        $password = $this->input->post('password');
        $sessionID = $this->session->getSessionData('sessionID');
        $widgetID  = $this->input->post('w_id');
        $url = $this->input->post('url');
        $result = $this->model('standard/Contact_model')->doLogin($userID, $password, $sessionID, $widgetID, $url);
        echo json_encode($result);
    }

    /**
     * Handle user logouts
     */
    function doLogout() {
        $currentUrl = $this->input->post('url');
        $redirectUrl = $this->input->post('redirectUrl');
        $result = $this->model('standard/Contact_model')->doLogout($currentUrl, $redirectUrl);
        echo json_encode($result);
    }

    /**
    * Redirects a chat request to the chat server and returns the response
    */
    function doChatRequest() {
        $result = $this->model('standard/Chat_model')->makeChatRequest();
        if($result)
            echo $result;
    }
    
    /**
    * inserts into the widget_stats table
    */
    function insertWidgetStats() {
        $type = $this->input->post('type');
        $widget = $this->input->post('widget');
        $column = $this->input->post('column');
        $action = (object)array('w'=>$widget.'', $column=>1);
        $this->model('standard/Clickstream_model')->insertWidgetStats($type, $action);
    }

    /**
    * submits a poll and returns the result data
    */
    function submitPoll() {
        $flowID = $this->input->post('flow_id');
        $questionID = $this->input->post('question_id');
        $testMode = $this->input->post('test') === true || $this->input->post('test') === 'true';

        if ($this->input->post('results_only')) {
            $result = $this->model('standard/Polling_model')->getPollResults($flowID, $questionID, $testMode);
            echo json_encode($result);
        } else {
            AbuseDetection::check();
            $docID = $this->input->post('doc_id');
            $responses = $this->input->post('responses');
            $chartType = $this->input->post('chart_type');
            $includeResults = ($this->input->post('include_results') === 'true') ? true : false;
            $questionType = $this->input->post('question_type');
            if (!$testMode)
                $this->model('standard/Polling_model')->submitPoll($flowID, $docID, $questionID, $responses, $questionType, false);
            
            if ($includeResults)
                echo json_encode($this->model('standard/Polling_model')->getPollResults($flowID, $questionID, $testMode));
        }
    }
    
    /**
     * Initiates an async request for intent guide search results
     * @private
     * @return Object AsyncDataModelRequest instance
     */
    private function _requestAsyncIntentGuideData() {
        $keyword = $this->input->request('keyword');
        $highlight = ($this->input->request('highlight') === 'true');
        $truncate = $this->input->request('truncate');
        $category = $this->input->request('category');
        $cutoff = $this->input->request('cutoff');
        $cutoff = min(max($cutoff, 1), 20);
        $siteref = $this->input->request('siteref');
        $referrer = $this->input->request('referrer');
                
        try {
            return $this->model('standard/Intentguide_model')->request('getSearchResults', $keyword, array(
                'highlight' => $highlight, 
                'truncate' => $truncate, 
                'siteref' => $siteref, 
                'referrer' => $referrer, 
                'category' => $category,
                'callback' => function($result, $key) use ($keyword, $truncate, $highlight, $cutoff) {
                    if(($key + 1) <= $cutoff && $result->question->type === 'Answer' && !$result->answers[0]->teaser) {
                        // if any of the cutoff results are answers, add its summary as the teaser
                        $CI = get_instance();
                        if($answer = $CI->model('standard/Answer_model')->get($result->answers[0]->answerID, new HTMLFormatter($keyword, true))) {
                            $result->answers[0]->teaser = truncateText($answer->description->value, $truncate);
                            if($highlight)
                                $result->answers[0]->teaser = emphasizeText($result->answers[0]->teaser, array('query' => $keyword));
                        }
                    }
                },
            ));
        }
        catch (Exception $e) {}
    }
    
    /**
     * Initiates an async request for community search results
     * @private
     * @return Object AsyncDataModelRequest instance
     */
    private function _requestAsyncCommunitySearch() {
        $keyword = $this->input->request('keyword');
        $limit = $this->input->request('limit');
        $start = $this->input->request('start') ?: null;
        $resourceID = $this->input->request('resource') ?: null;

        return $this->model('standard/Social_model')->request('performSearch', $keyword, $limit, null, $resourceID, null, $start, true);
    }
}
