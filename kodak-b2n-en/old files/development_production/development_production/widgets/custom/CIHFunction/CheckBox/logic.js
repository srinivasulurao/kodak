RightNow.Widget.CheckBox = function (dat, instanceID) {
    this.data = dat;
    this.instanceID = instanceID;
    this._eo = new RightNow.Event.EventObject();
    this._checkBox = document.getElementById('rn_' + this.instanceID + '_CheckBox');
    this._parentForm = RightNow.UI.findParentForm('rn_' + this.instanceID + '_CheckBox');
    RightNow.Event.subscribe('evt_formFieldValidateRequest', this._onValidate, this);
    RightNow.Event.subscribe('evt_resetForm', this.onResetForm, this);

}

RightNow.Widget.CheckBox.prototype =
{

    onResetForm: function (evt, args) {

        this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID + "_CheckBox");
        if (args[0].data.form === this._parentForm) {
            this._checkBox.checked = this.data.attrs.checked;
        }
    },

    /**
    * Validation routine to check if field is required, and if so, ensure it has a value
    * @return Boolean denoting if required check passed
    */
    _checkRequired: function () {
        if (this.data.attrs.required) {
            if (this._checkBox.checked ==false) {
                this._displayError(this.data.attrs.label_required);
                return false;
            }
        }
        return true;
    },

    /**
    * Displays error by appending message above submit button
    * @param errorMessage String Message to display
    */
    _displayError: function (errorMessage) {
        var commonErrorDiv = document.getElementById(this._formErrorLocation);
        if (commonErrorDiv) {
            RightNow.UI.Form.errorCount++;
            if (RightNow.UI.Form.chatSubmit && RightNow.UI.Form.errorCount === 1)
                commonErrorDiv.innerHTML = "";

            var errorLink = "<div><b><a href='javascript:void(0);' onclick='document.getElementById(\"" + this._checkBox.id +
                "\").focus(); return false;'>" + this.data.attrs.label_required + " ";

            if (errorMessage.indexOf("%s") > -1)
                errorLink = RightNow.Text.sprintf(errorMessage, errorLink);
            else
                errorLink = errorLink;

            errorLink += "</a></b></div> ";
            commonErrorDiv.innerHTML += errorLink;
        }
        YAHOO.util.Dom.addClass(this._checkBox, "rn_ErrorField");
        YAHOO.util.Dom.addClass("rn_" + this.instanceID + "_Label", "rn_ErrorLabel");
    },


    /**
    * Event handler for when form is being submitted
    *
    * @param {String} type Event name
    * @param {Object} args Event arguments
    */
    onValidate: function (type, args) {
        var eo = new RightNow.Event.EventObject();
        eo.data.name = this.data.attrs.name;
        eo.data.value = this._checkBox.checked;
        eo.data.form = RightNow.UI.findParentForm('rn_' + this.instanceID + '_CheckBox');
        RightNow.Event.fire('evt_formFieldValidateResponse', eo);

        RightNow.Event.fire('evt_formFieldCountRequest');
    },

    _onValidate: function (type, args) {
        this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID);
        var eo = new RightNow.Event.EventObject();
        eo.data = { "name": this.data.attrs.name,
            "value": this._checkBox.checked,
            "table": this.data.js.table,
            "required": (this.data.attrs.required ? true : false),
            "prev": this.data.js.prev,
            "form": this._parentForm
        };
        if (RightNow.UI.Form.form === this._parentForm) {
            this._formErrorLocation = args[0].data.error_location;

            if (this._checkRequired()) {
                if (this.data.js.profile)
                    eo.data.profile = true;
                if (this.data.js.customID) {
                    eo.data.custom = true;
                    eo.data.customID = this.data.js.customID;
                    eo.data.customType = this.data.js.type;
                }
                else {
                    eo.data.custom = false;
                }
                eo.w_id = this.data.info.w_id;
                RightNow.Event.fire("evt_formFieldValidateResponse", eo);
            }
            else {
                RightNow.UI.Form.formError = true;
            }
        }
        else {
            RightNow.Event.fire("evt_formFieldValidateResponse", eo);
        }
        RightNow.Event.fire("evt_formFieldCountRequest");
    }
}
