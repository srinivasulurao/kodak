 /* Originating Release: February 2012 */
RightNow.Widget.AnswerFeedback2 = function(data, instanceID)
{
    this.data = data;
    this.instanceID = instanceID;
    this._rate = 0;

    if (!document.getElementById("rn_"+ this.instanceID + "_FeedbackTextarea"))
    {
        //feedback text area is required; email input is optional.
        RightNow.UI.DevelopmentHeader.addJavascriptError(RightNow.Text.sprintf(RightNow.Interface.getMessage("ANSWERFEEDBACK2_DIALOG_MISSING_REQD_MSG"), "rn_"+ this.instanceID + "_FeedbackTextarea"));
        return;
    }

    RightNow.Event.subscribe("evt_answerFeedbackSubmitResponse", this._onResponseReceived, this);

    var Event = YAHOO.util.Event;

    if (this.data.js.buttonView)
    {
        var noButton = document.getElementById("rn_" + this.instanceID + "_RatingNoButton"),
            yesButton = document.getElementById("rn_" + this.instanceID + "_RatingYesButton");
        Event.addListener(noButton, "click", this._onClick, 1, this);
        Event.addListener(yesButton,  "click", this._onClick, 2, this);
        Event.addListener([noButton, yesButton],  "mouseover", function(){this._wasMouse = true;}, null, this);
        Event.addListener([noButton, yesButton],  "mouseout", function(){this._wasMouse = false;}, null, this);
    }
    else if (this.data.attrs.use_rank_labels)
    {
        var ratingButton = "rn_" + this.instanceID + "_RatingButton_";
        for(var i = 1; i <= this.data.attrs.options_count; ++i)
        {
            Event.addListener(ratingButton + i, "click", this._onClick, i, this);
            Event.addListener(ratingButton + i,  "mouseover", function(){this._wasMouse = true;}, null, this);
            Event.addListener(ratingButton + i,  "mouseout", function(){this._wasMouse = false;}, null, this);
        }
    }
    else
    {
        var ratingCell = "rn_" + this.instanceID + "_RatingCell_";
        for(var i = 1; i <= this.data.attrs.options_count; ++i)
        {
            Event.addListener(ratingCell + i, "mouseover", this._onCellOver, i, this);
            Event.addListener(ratingCell + i, "focus", this._onCellOver, i, this);
            Event.addListener(ratingCell + i, "mouseout", this._onCellOut, i, this);
            Event.addListener(ratingCell + i, "blur", this._onCellOut, i, this);
            Event.addListener(ratingCell + i, "click", this._onClick, i, this);
        }
    }
};

RightNow.Widget.AnswerFeedback2.prototype =
{
    /**
     * Event handler for when a user clicks on an answer rating
     * @param type String Event name
     * @param args Object Event arguments
     */
    _onClick: function(type, args)
    {
        //disable the control
        if (this.data.js.buttonView || this.data.attrs.use_rank_labels)
        {
            var ratingButtons = document.getElementById("rn_" + this.instanceID + "_RatingButtons");
            if(ratingButtons)
                YAHOO.util.Event.purgeElement(ratingButtons, true);
        }
        else
        {
           this._onCellOver(0, args);
           YAHOO.util.Event.preventDefault(type);
           var rateMeter = document.getElementById("rn_" + this.instanceID + "_RatingMeter");
           if (rateMeter)
               YAHOO.util.Event.purgeElement(rateMeter, true);

           //change hidden alt text on each star to match the visual cues we give to a non-disabled user
            for(var cell, i=0; i<=this.data.attrs.options_count; ++i)
            {
                cell = document.getElementById("rn_" + this.instanceID + "_RatingCell_" + i);
                if(cell)
                {
                    for(var j=0; j < cell.childNodes.length; j++)
                    {
                        if(cell.childNodes[j].tagName && cell.childNodes[j].tagName.toLowerCase() === "span" && YAHOO.util.Dom.hasClass(cell.childNodes[j], "rn_ScreenReaderOnly"))
                            cell.childNodes[j].innerHTML = RightNow.Text.sprintf(RightNow.Interface.getMessage("PCT_D_OF_PCT_D_SELECTED_LBL"), args, this.data.attrs.options_count);
                    }
                }
            }
        }

        this._rate = args;  // Get rating value;

        // Submit answer rating
        this._submitAnswerRating();

        // Show feedback dialog if indicated.
        if (this._rate <= this.data.attrs.dialog_threshold)
        {
            //if url attrib. don't display widget but rather show popup page
            if(this.data.attrs.feedback_page_url)
            {
                var pageString = this.data.attrs.feedback_page_url;
                pageString = RightNow.Url.addParameter(pageString, "a_id", this.data.js.answerID);
                pageString = RightNow.Url.addParameter(pageString, "session", RightNow.Url.getSession());
                window.open(pageString, '', "resizable, scrollbars, width=630, height=400");
            }
            else
            {
                this._showDialog();
            }
        }
    },

    /**
     * Constructs and shows the dialog
     * @return None
     */
    _showDialog: function()
    {
        // If the dialog doesn't exist, create it.  (Happens on first click).
        if (!this._dialog)
        {
            var buttons = [ { text: this.data.attrs.label_send_button, handler: {fn: this._onSubmit, scope: this}, isDefault: true},
                     { text: this.data.attrs.label_cancel_button, handler: {fn: this._onCancel, scope: this}, isDefault: false}],
                 dialogForm = document.getElementById("rn_" + this.instanceID + "_AnswerFeedback2Form");
            this._dialog = RightNow.UI.Dialog.actionDialog(this.data.attrs.label_dialog_title, dialogForm, {"buttons" : buttons, "dialogDescription" : "rn_" + this.instanceID + "_DialogDescription", width:this.data.attrs.dialog_width});
            YAHOO.util.Dom.removeClass(dialogForm, "rn_Hidden");
            // Give the dialog a specific css class
            YAHOO.util.Dom.addClass(this._dialog.id, 'rn_AnswerFeedback2Dialog');
        }
        
        this._emailField = this._emailField || document.getElementById("rn_" + this.instanceID + "_EmailInput");
        this._errorDisplay = this._errorDisplay || document.getElementById("rn_" + this.instanceID + "_ErrorMessage");
        this._feedbackField = this._feedbackField || document.getElementById("rn_"+ this.instanceID + "_FeedbackTextarea");
        
        if(this._errorDisplay)
        {
            this._errorDisplay.innerHTML = "";
            YAHOO.util.Dom.removeClass(this._errorDisplay, 'rn_MessageBox rn_ErrorMessage');
        }

        this._dialog.show();

        // Enable controls, focus the first input element
        var focusElement;
        if(this._emailField && this._emailField.value === '')
            focusElement = this._emailField;
        else
            focusElement = this._feedbackField;

        focusElement.focus();
        this._dialog.enableButtons();
    },

    /**
     * Event handler for click of submit buttons.
    */
    _onSubmit: function()
    {
        this._dialog.disableButtons();
        if (!this._validateDialogData())
        {
            this._dialog.enableButtons();
            return;
        }
        this._incidentCreateFlag = true;  //Keep track that we're creating an incident.
        this._submitFeedback();
    },

    /**
    * Event handler for click of cancel button.
    */
    _onCancel: function()
    {
        this._dialog.disableButtons();
        this._closeDialog(true);
    },

    /**
     * Validates dlg data.
     */
    _validateDialogData: function()
    {
        YAHOO.util.Dom.removeClass(this._errorDisplay, 'rn_MessageBox rn_ErrorMessage');  // clear error box
        this._errorDisplay.innerHTML = "";
        
        var returnValue = true;
        if (this._emailField)
        {
            this._emailField.value = YAHOO.lang.trim(this._emailField.value);
            if (this._emailField.value === "")
            {
                this._addErrorMessage(RightNow.Text.sprintf(RightNow.Interface.getMessage("PCT_S_IS_REQUIRED_MSG"), this.data.attrs.label_email_address), this._emailField.id);
                returnValue =  false;
            }
            else if (!RightNow.Text.isValidEmailAddress(this._emailField.value))
            {
                this._addErrorMessage(this.data.attrs.label_email_address + ' ' + RightNow.Interface.getMessage("FIELD_IS_NOT_A_VALID_EMAIL_ADDRESS_MSG"), this._emailField.id);
                returnValue = false;
            }
        }
        // Examine feedback text.
        this._feedbackField.value = YAHOO.lang.trim(this._feedbackField.value);
        if (this._feedbackField.value === "")
        {
            this._addErrorMessage(RightNow.Text.sprintf(RightNow.Interface.getMessage("PCT_S_IS_REQUIRED_MSG"), this.data.attrs.label_comment_box), this._feedbackField.id);
            returnValue = false;
        }
        return returnValue;
    },

    /**
     *  Close the dialog.
     * @param cancelled Boolean T if the dialog was canceled
    */
    _closeDialog: function(cancelled)
    {
        if(!cancelled)
        {
            //Feedback submitted: clear existing data if dialog is reopened
            this._feedbackField.value = "";
        }
        // Get rid of any existing error message, so it's gone if the user opens the dialog again.
        if(this._errorDisplay)
        {
            this._errorDisplay.innerHTML = "";
            YAHOO.util.Dom.removeClass(this._errorDisplay, 'rn_MessageBox rn_ErrorMessage');
        }

        if (this._dialog)
            this._dialog.hide();
    },

    /**
     * Submit data to the server.
     */
    _submitFeedback: function()
    {
        var eventObject = new RightNow.Event.EventObject();
        eventObject.w_id = this.instanceID;

        eventObject.data = {
                "summary" : this.data.js.summary,
                "a_id"    : this.data.js.answerID,
                "rate"    : this._rate,
                "dialog_threshold" : this.data.attrs.dialog_threshold,
                "options_count"    : this.data.attrs.options_count,
                "message" : this._feedbackField.value
        };
        if (this.data.js.isProfile)
            eventObject.data.email = this.data.js.email;
        else if (this._emailField)
            eventObject.data.email = this._emailField.value;

        RightNow.Event.fire("evt_answerFeedbackRequest", eventObject);
        return false;
    },

    /**
     * Event handler for server sends response.
     * @param type String Event name
     * @param arg Object Event arguments
     */
    _onResponseReceived: function(type, arg)
    {
        // If this widget's request created an incident, show confirmation dialog.
        if(this._incidentCreateFlag && arg[1][0].w_id === this.instanceID)
        {
            this._incidentCreateFlag = false;
            if(typeof(arg[0]) === "string")
            {
                RightNow.UI.Dialog.messageDialog(arg[0], {icon : "WARN", exitCallback : {fn:this._enableDialog, scope:this}});
            }
            else
            {
                //Show a messageDialog to confirm that feedback was sent.
                RightNow.UI.Dialog.messageDialog(RightNow.Interface.getMessage("THANKS_FOR_YOUR_FEEDBACK_MSG"), {exitCallback: {fn: this._closeDialog, scope:this}});
            }
        }
        else
        {
            this._closeDialog();
        }
    },

    /**
     * Submit answer rating for clicktrack record.
     * @return boolean
     */
    _submitAnswerRating: function()
    {
        var eventObject = new RightNow.Event.EventObject();
        eventObject.w_id = this.instanceID;
        eventObject.data = {
                "a_id"    : this.data.js.answerID,
                "rate"    : this._rate,
                "options_count": this.data.attrs.options_count,
                "dialog_threshold" : this.data.attrs.dialog_threshold
        };
        
        RightNow.ActionCapture.record('answer', 'rate', this.data.js.answerID);
        //Record the rating as a percentage. To get it, subtract 1 off both the count and the rating to support the 0% case
        RightNow.ActionCapture.record('answer', 'rated', ((this._rate-1)/(this.data.attrs.options_count-1)) * 100);
        RightNow.Event.fire("evt_answerRatingRequest", eventObject);

        var thanksLabel = document.getElementById("rn_" + this.instanceID + "_ThanksLabel");
        if (thanksLabel)
        {
            //provide some feedback to user either by innerHTML (if activated by mouse) or
            //by dialog (if activated by keyboard) don't display the alert dialog if
            //the rating is below the threshold because then we assume the feedback
            //dialog which will pop up will be adequate feedback. This Avoids
            //popping the feedback dialog and a message dialog
            if(this._wasMouse)
            {
                thanksLabel.innerHTML = this.data.attrs.label_feedback_submit;
            }
            else if(this._rate > this.data.attrs.dialog_threshold)
            {
                RightNow.UI.Dialog.messageDialog(this.data.attrs.label_feedback_submit);
            }
        }
    },

    
    /**
     * Adds an error message to the page and adds the correct CSS classes
     * @param message string The error message to display
     * @param focusElement HTMLElement The HTML element to focus on when the error message link is clicked
     */
    _addErrorMessage: function(message, focusElement)
    {
        if(this._errorDisplay)
        {
            YAHOO.util.Dom.addClass(this._errorDisplay, 'rn_MessageBox rn_ErrorMessage');
            //add link to message so that it can receive focus for accessibility reasons
            var newMessage = '<a href="javascript:void(0);" onclick="document.getElementById(\'' + focusElement + '\').focus(); return false;">' + message + '</a>'; 
            var oldMessage = this._errorDisplay.innerHTML;
            if (oldMessage === "")
            {
                this._errorDisplay.innerHTML = newMessage;
            }
            else
            {
                this._errorDisplay.innerHTML = oldMessage + '<br/>' + newMessage;
            }
            this._errorDisplay.firstChild.focus();
        }
    },

    /*-----------------  UI Handling Routines -----------------*/

    /**
     * Event handler for when the cursor is over a rating cell
     * @param type String Event name
     * @param chosenRating Integer Index of chosen control.
     */
    _onCellOver: function(type, chosenRating)
    {
        if(type.type === "mouseover")
            this._wasMouse = true;
        this._updateCellClass(1, chosenRating + 1, "rn_RatingCellOver", "add");
        this._updateCellClass(chosenRating + 1, this.data.attrs.options_count + 1, "rn_RatingCellOver", "remove");
    },
    
    /**
    * Adds or removes a CSS class from a range of rating cells.
    * @param minBound Int Starting point of first index into rating cells
    * @param maxBound Int Ending point of last index into rating cells
    * @param cssClass String The CSS class to add or remove
    * @param removeOrAddClass String 'add' or 'remove'
    */
    _updateCellClass: function(minBound, maxBound, cssClass, removeOrAddClass)
    {
        var cssFunc = (removeOrAddClass === "add") ? YAHOO.util.Dom.addClass : YAHOO.util.Dom.removeClass;
        for(var i = minBound; i < maxBound; i++)
            cssFunc("rn_" + this.instanceID + "_RatingCell_" + i, cssClass);
    },

    /**
     * Event handler for when the cursor leaves a rating cell
     * @param type String Event name
     * @param args Object Event arguments
     */
    _onCellOut: function(type, args)
    {
        if(type.type === "mouseout")
            this._wasMouse = false;
        this._updateCellClass(1, this.data.attrs.options_count + 1, "rn_RatingCellOver", "remove");
    }
};
