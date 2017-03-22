RightNow.Widget.AjaxFormSubmit = function (data, instanceID) {
    this.data = data;
    this.instanceID = instanceID;
    this._requestInProgress = false;
    this._formButton = document.getElementById("rn_" + this.instanceID + "_Button");
    this._parentForm = RightNow.UI.findParentForm('rn_' + this.instanceID + '_Button');
    this._formSubmitFlag = document.getElementById("rn_" + this.instanceID + "_Submission");
    this._panelTrigger = false;
    this._challengeDivID = this.data.attrs.challenge_location;
    this._eo = new RightNow.Event.EventObject();
    if (!this._formButton || !this._formSubmitFlag) return;

    this._statusMessage = document.getElementById("rn_" + this.instanceID + "_StatusMessage");
    this._parentForm = RightNow.UI.findParentForm("rn_" + this.instanceID);

    RightNow.Event.subscribe("evt_resetForm", this._resetForm, this);

    //error message div is either attribute-specified or created above the button
    this._errorMessageDiv = document.getElementById(this.data.attrs.error_location);
    if (!this._errorMessageDiv) {
        var errorNode = document.createElement("div");
        errorNode.id = "rn_" + this.instanceID + "_ErrorLocation";
        this._errorMessageDiv = YAHOO.util.Dom.insertBefore(errorNode, this._formButton);
    }
    this._errorMessageDiv.tabIndex = -1;
    YAHOO.util.Dom.setAttribute(this._errorMessageDiv, "aria-live", "rude");

    if (this.data.js.challengeProvider) {
        try {
            this._challengeProvider = eval(data.js.challengeProvider);
        }
        catch (ex) {
            throw "Failed while trying to parse a challenge provider.  " + ex;
        }
        this._createChallengeDiv();
        this._challengeProvider.create(this._challengeDivID, RightNow.UI.AbuseDetection.options);
        RightNow.Event.subscribe("evt_formFieldValidateRequest", this._onValidateChallengeResponse, this);
    }

    //if value's been set, then the form's already been submitted
    if (this._formSubmitFlag.value === "true") {
        this._formButton.disabled = true;
        return;
    }

    if (this._formSubmitFlag.disabled == false) {
        this._disableEvents();
        this._enableEvents();
    }

    RightNow.Event.subscribe('evt_fieldVisibilityChanged', this._visibilityChanged, this);

};

RightNow.Widget.AjaxFormSubmit.prototype = {

    _enableEvents: function () {

        if (this._parentForm) {

            this._enableClickListener();

            RightNow.Event.subscribe("evt_formButtonSubmitResponse", this.onFormSubmitResult, this);
            RightNow.Event.subscribe("evt_formValidatedResponse", this._onFormValidated, this);
            RightNow.Event.subscribe("evt_formFailValidationResponse", this._onFormValidationFail, this);
            RightNow.Event.subscribe("evt_fileUploadRequest", this._disableClickListener, this);
            RightNow.Event.subscribe("evt_fileUploadResponse", this._enableClickListener, this);
            RightNow.Event.subscribe("evt_formTokenUpdate", function (type, args) {
                var newToken = args[0].data.newToken;
                if (newToken)
                    this.data.js.f_tok = newToken;
            }, this);
        }
        else {
            RightNow.UI.addDevelopmentHeaderError(RightNow.Interface.getMessage('FORMSUBMIT_PLACED_FORM_UNIQUE_ID_MSG'));
        }
    },

    _disableEvents: function () {
        if (this._parentForm) {

            this._disableClickListener();

            RightNow.Event.unsubscribe("evt_formButtonSubmitResponse", this.onFormSubmitResult);
            RightNow.Event.unsubscribe("evt_formValidatedResponse", this._onFormValidated);
            RightNow.Event.unsubscribe("evt_formFailValidationResponse", this._onFormValidationFail);
            RightNow.Event.unsubscribe("evt_fileUploadRequest", this._disableClickListene);
            RightNow.Event.unsubscribe("evt_fileUploadResponse", this._enableClickListener);
            RightNow.Event.unsubscribe("evt_formTokenUpdate", function (type, args) {
                var newToken = args[0].data.newToken;
                if (newToken)
                    this.data.js.f_tok = newToken;
            });
        }
    },

    _visibilityChanged: function (evt, args) {
        if (args[0].id == this._formButton.id) {
            if (this._formButton.disabled == true) {
                this._disableEvents();
            }
            else {
                this._disableEvents();
                this._enableEvents();
            }
        }
    },

    /**
    * Handles when user clicks submit button
    * @param type string Event name
    * @param args object Event arguments
    */
    _onButtonClick: function (type, args) {
        this._disableEvents();
        this._enableEvents();
        this._panelTrigger = true;
        if (this._requestInProgress) return false;

        this._disableClickListener();

        //Reset form errors & status message
        this._statusMessage.innerHTML = "";
        YAHOO.util.Dom.addClass(this._errorMessageDiv, "rn_Hidden");
        this._errorMessageDiv.innerHTML = "";

        //var eo = new RightNow.Event.EventObject();
        this._eo.w_id = this.instanceID;
        this._eo.data = { "form": this._parentForm, "error_location": this._errorMessageDiv.id, "f_tok": this.data.js.f_tok };

        if (this._challengeDivID) {
            this._eo.challengeHandler = RightNow.Event.createDelegate(this, this._challengeHandler);
        }

        //since the form is submitted by script, deliberately tell IE to do auto completion of the form data
        if (YAHOO.env.ua.ie !== 0 && window.external && "AutoCompleteSaveForm" in window.external)
            window.external.AutoCompleteSaveForm(document.getElementById(this._parentForm));

        RightNow.Event.fire("evt_formButtonSubmitRequest", this._eo);
    },

    /** 
    * Report an incorrect or absent abuse challenge response.
    * @param errorMessage string Error message to display.
    */
    _reportChallengeError: function (errorMessage) {
        if (!errorMessage) {
            errorMessage = RightNow.Interface.getMessage("PLS_VERIFY_REQ_ENTERING_TEXT_IMG_MSG");
        }
        var errorLinkAnchorID = "rn_ChallengeErrorLink",
            errorLink = "<div><b><a id ='" + errorLinkAnchorID + "' href='javascript:void(0);'>" + errorMessage + "</a></b></div>";

        RightNow.UI.Form.errorCount++;
        if (RightNow.UI.Form.chatSubmit && RightNow.UI.Form.errorCount === 1)
            this._errorMessageDiv.innerHTML = "";

        this._errorMessageDiv.innerHTML += errorLink;
        document.getElementById(errorLinkAnchorID).onclick = RightNow.Event.createDelegate(this, function () {
            this._challengeProvider.focus();
            return false;
        });
    },

    /**
    * Ensure that a div exists to display the abuse challenge in.
    */
    _createChallengeDiv: function () {
        var challengeDiv = document.getElementById(this._challengeDivID);
        if (!challengeDiv) {
            challengeDiv = document.createElement("div");
            challengeDiv.id = this._challengeDivID;
            YAHOO.util.Dom.insertBefore(challengeDiv, this._formButton);
        }
    },

    /** 
    * Called back by the RightNow.Ajax layer when it determines that the server responded that a challenge is required.
    * @param abuseResponse An object returned by the server containing the challenge provider script.
    * @param requestObject The original request object
    * @param isRetry A boolean indicating if the the server said that the request contained an incorrect challenge response.
    */
    _challengeHandler: function (abuseResponse, requestObject, isRetry) {
        this._createChallengeDiv();

        if (!this._challengeProvider) {
            this._challengeProvider = RightNow.UI.AbuseDetection.getChallengeProvider(abuseResponse);
            RightNow.Event.subscribe("evt_formFieldValidateRequest", this._onValidateChallengeResponse, this);
        }

        this._clearLoadingIndicators();

        this._challengeProvider.create(this._challengeDivID, RightNow.UI.AbuseDetection.options);
        this._reportChallengeError(RightNow.UI.AbuseDetection.getDialogCaption(abuseResponse));
        this._onFormValidationFail();
    },

    /**
    * Event handler for form validation.  
    */
    _onValidateChallengeResponse: function () {
        var eo = new RightNow.Event.EventObject();
        //Challenges have no data to be passed with the form
        eo.data = { form: false };
        if (RightNow.UI.Form.form === this._parentForm) {
            var inputs = this._challengeProvider.getInputs(this._challengeDivID);
            if (inputs.abuse_challenge_response) {
                for (var key in inputs) {
                    if (inputs.hasOwnProperty(key)) {
                        RightNow.Ajax.addRequestData(key, inputs[key]);
                    }
                }
                RightNow.Event.fire("evt_formFieldValidateResponse", eo);
            }
            else {
                this._reportChallengeError();
                RightNow.UI.Form.formError = true;
            }
        }
        else {
            RightNow.Event.fire("evt_formFieldValidateResponse", eo);
        }
        RightNow.Event.fire("evt_formFieldCountRequest");
    },

    /**
    * Event handler for when form has been validated
    */
    _onFormValidated: function () {
        if (RightNow.UI.Form.form === this._parentForm && RightNow.UI.Form.formFields.length > 0) {

            if (this._requestInProgress == true)
                this._overrideAjaxMethod();

            //Show the loading icon and status message
            YAHOO.util.Dom.removeClass("rn_" + this.instanceID + "_LoadingIcon", "rn_Hidden");
            if (this._statusMessage)
//                this._statusMessage.innerHTML = RightNow.Interface.getMessage('SUBMITTING_ELLIPSIS_MSG');                this._statusMessage.innerHTML = this.data.js.submitmsg;
        }
    },

    /**
    * Event handler for when form fails validation check
    */
    _onFormValidationFail: function () {
        if (RightNow.UI.Form.form === this._parentForm) {
            //give error div a common error message CSS class
            YAHOO.util.Dom.addClass(this._errorMessageDiv, "rn_MessageBox");
            YAHOO.util.Dom.addClass(this._errorMessageDiv, "rn_ErrorMessage");
            YAHOO.util.Dom.removeClass(this._errorMessageDiv, "rn_Hidden");
            if (this._errorMessageDiv.tabIndex === 0) {
                window.scrollTo(0, this._errorMessageDiv.offsetTop - 20); //20px buffer above error div
                //focusing half a second later helps screen readers anounce the message correctly
                YAHOO.lang.later(500, this, function () { this._errorMessageDiv.tabIndex = 0; this._errorMessageDiv.focus(); this._errorMessageDiv.tabIndex = 1; });
            }
            else {
                //focus first link in the error box and scroll to it
                var firstField = YAHOO.util.Dom.getElementBy(function (e) { return true; }, "A", this._errorMessageDiv);
                if (firstField && firstField.focus)
                    firstField.focus();
                window.scrollTo(0, this._errorMessageDiv.offsetTop - 20); //20px buffer above error div
            }
            this._enableClickListener();
        }
    },

    /**
    * Event handler for when form submission returns from the server
    * @param type string Event name
    * @param args object Event arguments
    */
    _formSubmitResponse: function (type, args) {
        if (RightNow.UI.Form.form !== this._parentForm)
            return;

        var result = args[0];
        //RightNow.Event.fire('evt_formSubmitResult', result);

        if (this.data.attrs.disable_result_handler)
            return;

        if (!result) {
            if (this._statusMessage) {
                this._statusMessage.innerHTML = RightNow.Interface.getMessage('ERROR_REQUEST_ACTION_COMPLETED_MSG');
            }
            RightNow.UI.Dialog.messageDialog(RightNow.Interface.getMessage('ERROR_REQUEST_ACTION_COMPLETED_MSG'), { icon: "WARN" });
        }
        else if (result.sa) {
            //Check if a new form token was passed back and use it during the
            //next time we submit the form.
            if (result.newFormToken) {
                var formTokenEO = new RightNow.Event.EventObject();
                formTokenEO.data.newToken = result.newFormToken
                RightNow.Event.fire("evt_formTokenUpdate", formTokenEO);
            }
            //SmartAssistantDialog handles SmartAssistant response
            //but we still need to check if the incident shouldn't be created according to a rule (meaning the submit button should be removed)
            for (var i in result.sa) {
                if (typeof result.sa[i].add_flag !== "undefined" && result.sa[i].add_flag == false) {
                    this._disableClickListener();
                    document.getElementById("rn_" + this.instanceID).innerHTML = "";
                    return;
                }
            }
        }
        else if (result.status === 1) {
            //success
            this._formSubmitFlag.value = "true";
            this._navigateToUrl = function () {
                if (result.redirectOverride) {
                    RightNow.Url.navigate(result.redirectOverride + result.sessionParm);
                }
                else if (this.data.attrs.on_success_url) {
                    if (result.i_id) {
                        RightNow.Url.navigate(this.data.attrs.on_success_url + "/i_id/" + result.i_id + result.sessionParm);
                    }
                    else if (result.refno) {
                        RightNow.Url.navigate(this.data.attrs.on_success_url + '/refno/' + result.refno + result.sessionParm);
                    }
                    else {
                        var sessionValue = result.sessionParm.substr(result.sessionParm.lastIndexOf("/") + 1);
                        if (!sessionValue && this.data.js.redirectSession)
                            sessionValue = this.data.js.redirectSession;
                        RightNow.Url.navigate(RightNow.Url.addParameter(this.data.attrs.on_success_url, 'session', sessionValue));
                    }
                }
                else {
                    RightNow.Url.navigate(window.location + result.sessionParm);
                }
            };
            //either create confirmation dialog
            if (this.data.attrs.label_confirm_dialog !== '') {
                RightNow.UI.Dialog.messageDialog(this.data.attrs.label_confirm_dialog, { exitCallback: { fn: this._navigateToUrl, scope: this }, width: '250px' });
            }
            //or go directly to the next page
            else {
                this._navigateToUrl();
                return;
            }
        }
        else if (result.status == -1) {
            //Security token error
            var errorUrl = (window.location.pathname.indexOf("/cx/facebook") === -1) ? "/app/error/error_id/5" : "/cx/facebook/error/error_id/5";
            RightNow.Url.navigate(errorUrl + result.sessionParm);
        }
        else {
            if (result.message) {
                this._errorMessageDiv.innerHTML += "<div><b>" + result.message + "</b></div>";
                this._errorMessageDiv.tabIndex = 0;
                this._onFormValidationFail();
                this._errorMessageDiv.tabIndex = -1;
            }
            else {
                RightNow.UI.Dialog.messageDialog(RightNow.Interface.getMessage('ERROR_PAGE_PLEASE_S_TRY_MSG'), { icon: "WARN" });
            }
        }
    },

    /** 
    * Re-enable the button, hide the loading icon and status message
    */
    _clearLoadingIndicators: function () {
        this._enableClickListener();
        YAHOO.util.Dom.addClass("rn_" + this.instanceID + "_LoadingIcon", "rn_Hidden");
        if (this._statusMessage)
            this._statusMessage.innerHTML = "";
    },

    /**
    * Enable the form submit control by enabling button and adding an onClick listener.
    */
    _enableClickListener: function () {
        this._formButton.disabled = this._requestInProgress = false;
        YAHOO.util.Event.addListener(this._formButton, "click", this._onButtonClick, null, this);
    },

    /**
    * Disable the form submit control by disabling button and removing the onClick listener.
    */
    _disableClickListener: function () {
        this._formButton.disabled = this._requestInProgress = true;
        YAHOO.util.Event.removeListener(this._formButton, "click", this._onButtonClick);
    },

    /*
    * Override the submission controller ajax method
    */
    _overrideAjaxMethod: function () {

        RightNow.Event.unsubscribe('on_before_ajax_request', function (evt, eo) {
            if (this.data.attrs.ajax_method)
                eo[0].url = '/cc/' + this.data.attrs.ajax_method;
        });

        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
            if (this.data.attrs.ajax_method)
            {
                eo[0].url = '/cc/' + this.data.attrs.ajax_method;
            }
        }, this);
    },

    onFormSubmitResult: function (evt, args) {
        if (this._panelTrigger == true) {
            this._clearLoadingIndicators();
            var dialogTitle = args[0].dialog_title || '';
            if (args[0].status == 1) {
                //var eo = new RightNow.Event.EventObject();
                this._eo.data.trigger = this._formButton.id;
                this._eo.data.form = this._parentForm;

                this._showDialog(args[0].message, 'TIP', dialogTitle);
                RightNow.Event.fire('evt_resetForm', this._eo);

            }
            else if (args[0].status == 0) {
                //We have an error that was handled
                this._showDialog(args[0].message, 'ALARM', dialogTitle);
            }
            else {
                if (args[0])
                    this._showDialog('An error occurred while performing the last request.', 'ALARM', dialogTitle);
            }
        }

    },

    _showDialog: function (message, icon, title) {
        var config =
        {
            'title': title,
            'width': "250px",
            'icon': icon
        };

        this.dialog = RightNow.UI.Dialog.messageDialog(message, config);
        this.dialog.show();
    },

    _resetForm: function (evt, args) {
        if (args[0].data.form == this._parentForm && this._formSubmitFlag.disabled == false) {
            this._disableClickListener();
            this._enableClickListener();
            YAHOO.util.Dom.addClass(this._errorMessageDiv, "rn_Hidden");
            this._errorMessageDiv.innerHTML = "";
        }
    }
};
