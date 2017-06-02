RightNow.namespace('Custom.Widgets.CIHFunction.CountryRegionSelector');
Custom.Widgets.CIHFunction.CountryRegionSelector = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
            this._eo = new RightNow.Event.EventObject();
			var optionsName = "rn_" + this.instanceID + "_CountryParam";
			this._inputField = document.getElementById(optionsName);
			this._validated = false;
			this._parentForm = RightNow.UI.findParentForm('rn_' + this.instanceID + '_CountryParam');

			this.Y.one("#"+this._inputField.id).on("change", this._onSelectChange,this);
			RightNow.Event.subscribe('evt_resetForm', this.onResetForm, this);
			RightNow.Event.subscribe('evt_formFieldValidateRequest', this._onValidate, this);

			this._setSelectedDropdownItem(this.data.js.defaultFilter);
    },
	_onSelectChange: function (evt) {
        this._setSelected();
    },

    /**
    * internal function to set the event object from the column select box value
    */
    _setSelected: function () {
        if (this._inputField) {
            var i = this._inputField.selectedIndex;
            i = Math.max(0, i);
            if (this._inputField.options[i]) {
                this._eo.filters.data = this._inputField.options[i].value;
                this.data.js.value = this._eo.filters.data;

                this._eo.data.instanceID = this.instanceID;  //testing

                RightNow.Event.fire("evt_country_filter", this._eo);
                RightNow.Event.fire("evt_refresh_provinces", this._eo);
                
            }
        }
    },

    /**
    * Sets the selected dropdown item to the one matching the passed-in value.
    * @param valueToSelect Int Value of item to select
    * @return Boolean Whether or not the operation was successful
    */
    _setSelectedDropdownItem: function (valueToSelect) {
        if (this._inputField) {

            if (valueToSelect == undefined) {
                this._inputField.selectedIndex = 0;
            }
            else {
                for (var i = 0; i < this._inputField.length; i++) {
                    if (this._inputField.options[i].value == valueToSelect) {
                        this._inputField.selectedIndex = i;
                        this._inputField.options[i].selected = true;
                        return true;
                    }
                }
            }
        }
        return false;
    },

    onResetForm: function (evt, args) {

        this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID + "_Options");
        if (args[0].data.form === this._parentForm) {
            YAHOO.util.Dom.removeClass(this._inputField, "rn_ErrorField");
            YAHOO.util.Dom.removeClass("rn_" + this.instanceID + "_Label", "rn_ErrorLabel");
            this._inputField.selectedIndex = 0;
        }
    },

    /**
    * Event handler for when form is being submitted
    *
    * @param {String} type Event name
    * @param {Object} args Event arguments
    */
    /*
    _onValidate: function (type, args) {
    var eo = new RightNow.Event.EventObject();
    eo.data.name = this.data.attrs.name;
    eo.data.value = this.data.js.value;
    eo.data.form = RightNow.UI.findParentForm('rn_' + this.instanceID + '_Options');
    RightNow.Event.fire('evt_formFieldValidateResponse', eo);

    RightNow.Event.fire('evt_formFieldCountRequest');
    }
    */

    _onValidate: function (type, args) {
        this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID);
        var eo = new RightNow.Event.EventObject();
        eo.data = { "name": this.data.attrs.name,
            "value": this._getValue(),
            "table": this.data.js.table,
            "required": (this.data.attrs.required ? true : false),
            "prev": this.data.js.prev,
            "form": this._parentForm
        };
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
        RightNow.Event.fire("evt_formFieldCountRequest");
    },

    /**
    * Validation routine to check if field is required, and if so, ensure it has a value
    * @return Boolean denoting if required check passed
    */
    _validateRequirement: function () {
        if (this.data.attrs.required && this._inputField.disabled == false) {
            if (this.data.js.type === RightNow.Interface.Constants.EUF_DT_RADIO) {
                if ((this._inputField[0] && this._inputField[1]) && (!this._inputField[0].checked && !this._inputField[1].checked)) {
                    this._displayError(this.data.attrs.label_required);
                    return false;
                }
            }
            else if (this._inputField.type === "checkbox" && !this._inputField.checked) {
                this._displayError(this.data.attrs.label_required);
                return false;
            }
            else if (this._inputField.value === "") {
                this._displayError(this.data.attrs.label_required);
                return false;
            }
        }
        this.Y.all("#"+this._inputField.id).removeClass("rn_ErrorField");
        this.Y.all("#rn_" + this.instanceID + "_Label").removeClass("rn_ErrorLabel");
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

                var elementId = (YAHOO.util.Lang.isArray(this._inputField)) ? this._inputField[0].id : this._inputField.id,
                    inputLabel = this.data.attrs.label_error || this.data.attrs.label_input,
                    label = (errorMessage.indexOf("%s") > -1) ? RightNow.Text.sprintf(errorMessage, inputLabel) : inputLabel + ' ' + errorMessage;

                commonErrorDiv.innerHTML += "<div><b><a href='javascript:void(0);' onclick='document.getElementById(\"" +
                    elementId + "\").focus(); return false;'>" + label + "</a></b></div> ";
            }
        }
        this.Y.all("#"+this._inputField.id).addClass("rn_ErrorField");
        this.Y.all("#rn_" + this.instanceID + "_Label").addClass("rn_ErrorLabel");
    },

    /**
    * Returns the String (Radio/Select) or Boolean value (Check) of the element.
    * @return String/Boolean that is the field value
    */
    _getValue: function () {
        if (this.data.js.type === RightNow.Interface.Constants.EUF_DT_RADIO) {
            if (this._inputField[0].checked)
                return this._inputField[0].value;
            if (this._inputField[1].checked)
                return this._inputField[1].value;
        }
        else if (this.data.js.type === RightNow.Interface.Constants.EUF_DT_CHECK) {
            if (this._inputField.type === "checkbox")
                return this._inputField.checked;
            return this._inputField.value === "1";
        }
        else {
            //select value
            if (this.data.attrs.usetextasvalue)
                return this._inputField.options[this._inputField.selectedIndex].text; ;
            return this._inputField.value;
        }
    }
});