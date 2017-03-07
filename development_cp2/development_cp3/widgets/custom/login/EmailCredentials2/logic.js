RightNow.Widget.EmailCredentials2 = function(data, instanceID)
{
    this.data = data;
    this.instanceID = instanceID;
    this._submitting = false;
    YAHOO.util.Event.addListener("rn_" + this.instanceID + "_Form", "submit", this._onSubmit, null, this);
    if(this.data.attrs.credential_type === 'password')
        RightNow.Event.subscribe("evt_emailPasswordResponse", this._onRequestSent, this);
    else
        RightNow.Event.subscribe("evt_emailUsernameResponse", this._onRequestSent, this);
    if(this.data.attrs.initial_focus)
    {
        var inputField = document.getElementById("rn_" + this.instanceID + "_" + this.data.attrs.credential_type + "_Input");
        if(inputField && inputField.focus)
            inputField.focus();
    }
};

RightNow.Widget.EmailCredentials2.prototype = {
    /**
     * Event handler for when response has been sent from server
     * @param {String} type Event name
     * @param {Object} arg Event arguments
     */
    _onRequestSent: function(type, arg)
    {
        this._submitting = false;
        var dialogBody = document.createElement("div");
        dialogBody.innerHTML = arg[0].message;
        var successDialog = RightNow.UI.Dialog.actionDialog(RightNow.Interface.getMessage("INFORMATION_LBL"), dialogBody, {"width" : '450px'});
        successDialog.show();
        if(successDialog.firstElement && successDialog.firstElement.id !== "")
            document.getElementById(successDialog.firstElement.id).focus();
        YAHOO.util.Dom.addClass("rn_" + this.instanceID + "_LoadingIcon", "rn_Hidden");
    },

    /**
     * Event when submit button has been clicked
     */
    _onSubmit: function(event, arg)
    {
        if(this._submitting)
            return false;
        var submitElement = document.getElementById("rn_" + this.instanceID + "_" + this.data.attrs.credential_type + "_Input");
        if(submitElement)
        {
            var value = submitElement.value = YAHOO.lang.trim(submitElement.value),
                eventToFire;
            if(value.length > 0)
            {
                YAHOO.util.Dom.addClass(this._errorMessageDiv, "rn_Hidden");
                //reset password field
                if(this.data.attrs.credential_type === 'password')
                {
                    var errorMessage = "";
                    //check spaces, quotes and brackets
                    if(value.indexOf(' ') > -1)
                        errorMessage = RightNow.Interface.getMessage("USERNAME_FIELD_CONTAIN_SPACES_MSG");
                    else if(value.indexOf("'") > -1 || value.indexOf('"') > -1)
                        errorMessage = RightNow.Interface.getMessage("USERNAME_FIELD_CONT_QUOTE_CHARS_MSG");
                    else if(value.indexOf("<") > -1 || value.indexOf(">") > -1)
                        errorMessage = RightNow.Interface.getMessage("USERNAME_FIELD_CONTAIN_THAN_CHARS_MSG");
                    if(errorMessage !== "")
                    {
                        this._displayErrorMessage(errorMessage, submitElement);
                        return false;
                    }
                    eventToFire = "evt_emailPasswordRequest";
                }
                //send username field
                else
                {
                    if(!RightNow.Text.isValidEmailAddress(value))
                    {
                        this._displayErrorMessage(RightNow.Interface.getMessage("EMAIL_IS_NOT_VALID_MSG"), submitElement);
                        return false;
                    }
                    eventToFire = "evt_emailUsernameRequest";
                }
                this._submitting = true;
                var eo = new RightNow.Event.EventObject();
                eo.w_id = this.instanceID;
                eo.data.value = value;
                RightNow.Event.fire(eventToFire, eo);
                YAHOO.util.Dom.removeClass("rn_" + this.instanceID + "_LoadingIcon", "rn_Hidden");
            }
            else
            {
                this._displayErrorMessage(this.data.js.field_required, submitElement);
            }
        }
    },

    /**
    * Displays an error message in a div above the form.
    * @param errorMessage String The error message
    * @param inputField HTMLElement The input field
    */
    _displayErrorMessage: function(errorMessage, inputField)
    {
        if(!this._errorMessageDiv)
        {
            this._errorMessageDiv = document.createElement("div");
            this._errorMessageDiv = YAHOO.util.Dom.insertBefore(this._errorMessageDiv, "rn_" + this.instanceID + "_Form");
            YAHOO.util.Dom.addClass(this._errorMessageDiv, "rn_MessageBox");
            YAHOO.util.Dom.addClass(this._errorMessageDiv, "rn_ErrorMessage");
        }
        this._errorMessageDiv.innerHTML = "<b><a href='javascript:void(0);' onclick='document.getElementById(\"" + inputField.id +
                "\").focus(); return false;'>" + errorMessage + "</a></b>";
        YAHOO.util.Dom.removeClass(this._errorMessageDiv, "rn_Hidden");
        var errorLink = YAHOO.util.Dom.getElementBy(function(){return true;}, "A", this._errorMessageDiv);
        if(errorLink)
            errorLink.focus();
    }
};
