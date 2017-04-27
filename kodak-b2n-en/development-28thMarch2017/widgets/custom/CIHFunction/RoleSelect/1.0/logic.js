RightNow.namespace('Custom.Widgets.CIHFunction.RoleSelect');
Custom.Widgets.CIHFunction.RoleSelect = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
            this._eo = new RightNow.Event.EventObject();
            this._fieldName = "rn_" + this.instanceID + "_Options";

            this._parentForm = RightNow.UI.findParentForm('rn_' + this.instanceID + '_Options');

            this._eo.data.form = RightNow.UI.findParentForm("rn_" + this.instanceID);
            this._optionsSelect = document.getElementById(this._fieldName);

            this.Y.one("#"+this._optionsSelect.id).on("change", this._onSelectChange, null, this);
            RightNow.Event.subscribe('evt_formFieldValidateRequest', this.onValidate, this);
                
            this._setSelectedDropdownItem(this.data.js.defaultFilter);

            RightNow.Event.subscribe('evt_resetForm', this._onResetForm, this);

            this._setSelectedDropdownItem(this.data.js.defaultFilter);
            RightNow.Event.subscribe('evt_toggleRequired', this._toggleRequired, this);
    },
    _onSelectChange: function (evt) {
        this._setSelected();

    },

    /**
    * internal function to set the event object from the column select box value
    */
    _setSelected: function () {
        if (this._optionsSelect) {
            var i = this._optionsSelect.selectedIndex;
            i = Math.max(0, i);
            if (this._optionsSelect.options[i]) {
                this._eo.filters.data = this._optionsSelect.options[i].value;
                this.data.js.value = this._eo.filters.data;
            }
        }
    },

    /**
    * Sets the selected dropdown item to the one matching the passed-in value.
    * @param valueToSelect Int Value of item to select
    * @return Boolean Whether or not the operation was successful
    */
    _setSelectedDropdownItem: function (valueToSelect) {
        if (this._optionsSelect) {

            if (valueToSelect == undefined) {
                this._optionsSelect.selectedIndex = 0;
            }
            else {
                for (var i = 0; i < this._optionsSelect.length; i++) {
                    if (this._optionsSelect.options[i].value == valueToSelect) {
                        this._optionsSelect.selectedIndex = i;
                        this._optionsSelect.options[i].selected = true;
                        return true;
                    }
                }
            }
        }
        return false;
    },

    onValidate: function (type, args) {
        var eo = new RightNow.Event.EventObject();
        eo.data.name = this.data.attrs.name;
        eo.data.value = this.data.js.value;
        eo.data.form = RightNow.UI.findParentForm('rn_' + this.instanceID + '_Options');
        ///RightNow.Event.fire('evt_formFieldValidateResponse', eo);
        if (RightNow.UI.Form.form === this._parentForm) {
            this._formErrorLocation = args[0].data.error_location;

            if (this._validateRequirement()) {
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
        RightNow.Event.fire('evt_formFieldCountRequest');
    },

    /**
    * Validation routine to check if field is required, and if so, ensure it has a value
    * @return Boolean denoting if required check passed
    */
    _validateRequirement: function () {
        if (this.data.attrs.required && this._optionsSelect.disabled == false) {
            if (this.data.js.type === RightNow.Interface.Constants.EUF_DT_RADIO) {
                if ((this._optionsSelect[0] && this._optionsSelect[1]) && (!this._optionsSelect[0].checked && !this._optionsSelect[1].checked)) {
                    this._displayError(this.data.attrs.label_required);
                    return false;
                }
            }
            else if (this._optionsSelect.type === "checkbox" && !this._optionsSelect.checked) {
                this._displayError(this.data.attrs.label_required);
                return false;
            }
            else if (this._optionsSelect.value === "") {
                this._displayError(this.data.attrs.label_required);
                return false;
            }
        }
        YAHOO.util.Dom.removeClass(this._optionsSelect, "rn_ErrorField");
        YAHOO.util.Dom.removeClass("rn_" + this.instanceID + "_Label", "rn_ErrorLabel");
        return true;
    },

    /**
    * Displays error by appending message above submit button
    * @param errorMessage String Message to display
    */
    _displayError: function (errorMessage) {
        var Form = RightNow.UI.Form;
        Form.errorCount++;
        if (this._formErrorLocation) {
            var commonErrorDiv = document.getElementById(this._formErrorLocation);
            if (commonErrorDiv) {
                if (Form.chatSubmit && Form.errorCount === 1)
                    commonErrorDiv.innerHTML = "";

                var elementId = (YAHOO.util.Lang.isArray(this._optionsSelect)) ? this._optionsSelect[0].id : this._optionsSelect.id,
                    inputLabel = '';
                label = (errorMessage.indexOf("%s") > -1) ? RightNow.Text.sprintf(errorMessage, inputLabel) : inputLabel + ' ' + errorMessage;

                commonErrorDiv.innerHTML += "<div><b><a href='javascript:void(0);' onclick='document.getElementById(\"" +
                    elementId + "\").focus(); return false;'>" + label + "</a></b></div> ";
            }
        }
        YAHOO.util.Dom.addClass(this._optionsSelect, "rn_ErrorField");
        YAHOO.util.Dom.addClass("rn_" + this.instanceID + "_Label", "rn_ErrorLabel");
    },

    _toggleRequired: function (evt, args) {
        if (args[0].data.form != this._parentForm)
            return;
        var fields = args[0].data.toggleRequired.fields;
        var toggleValue = args[0].data.toggleRequired.value;

        for (i = 0; i < fields.length; i++) {
            if (fields[i] == this._fieldName) {
                this.data.attrs.required = toggleValue;
            }
        }
    },

    _onResetForm: function (evt, args) {
        if (args[0].data.form === this._parentForm) {
            this._optionsSelect.selectedIndex = 0;
        }
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});