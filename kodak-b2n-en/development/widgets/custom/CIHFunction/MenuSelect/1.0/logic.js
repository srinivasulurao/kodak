RightNow.namespace('Custom.Widgets.CIHFunction.MenuSelect');
Custom.Widgets.CIHFunction.MenuSelect = RightNow.SearchFilter.extend({ 
   overrides:
    {
    constructor: function(data, instanceID) {
        this.parent();
        this.data = data;
        this.instanceID = instanceID;
        this._eo = new RightNow.Event.EventObject();
        this._fieldName = "rn_" + this.instanceID + "_Options";
        this._inputField = document.getElementById(this._fieldName);
        this._validated = false;
        this._parentForm = RightNow.UI.findParentForm('rn_' + this.instanceID + '_Options');
        this._eo.data.form = this._parentForm;
        //this.Y.on( "change", this._test,this._inputField);
        this.Y.on("change", this._onSelectChange, this._inputField, this);
		
		if(this._inputField!=null){
			
         if (this._inputField.disabled != true) {
           this._enableEvents();
          }
		  
		}

    RightNow.Event.subscribe('evt_resetForm', this.onResetForm, this);
    RightNow.Event.subscribe('evt_formFieldValidateRequest', this._onValidate, this);
    //this.searchSource().on("search",this._onValidate,this);
    RightNow.Event.subscribe('evt_fieldVisibilityChanged', this._visibilityChanged, this);
    RightNow.Event.subscribe('evt_toggleRequired', this._toggleRequired, this);

    if (this.data.attrs.selected_value >= 0)
        this._setSelectedDropdownItem(this.data.attrs.selected_value);


        //this._test();
    }
    },
   _enableEvents: function () {


        this._setSelectedDropdownItem(this.data.js.defaultFilter);
        if (this.data.attrs.is_search_filter == true) {
            RightNow.Event.subscribe("evt_getFiltersRequest", this._onGetFiltersRequest, this);
            this._setFilter();
        }
    },

    _disableEvents: function () {
        this._setSelectedDropdownItem(this.data.js.defaultFilter);
        if (this.data.attrs.is_search_filter == true) {
            RightNow.Event.unsubscribe("evt_getFiltersRequest", this._onGetFiltersRequest);
        }
    },

    _visibilityChanged: function (evt, args) {
        if (args[0].id == this._inputField.id) {
            if (this._inputField.disabled == true) {
                this._disableEvents();
            }
            else {
                this._enableEvents();
            }
        }
    },

    /**
    * sets the initial event object data
    *
    */

    _setFilter: function () {
        this._eo.w_id = this.instanceID;
        this._eo.filters = { "searchName": this._fieldName,
            "rnSearchType": 'filter',
            "report_id": this.data.attrs.search_report_id,
            "data": { "fltr_id": this.data.attrs.search_filter_id,
                "oper_id": this.data.attrs.search_operator_id,
                "val": this._getValue(),
                "w_id":this._fieldName
            }

        };
        this._eo.filters.data.val = this._getValue();
    },
    /**
    * Event handler executed when the select box is changed
    *
    * @param evt object Event
    */
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
            this.Y.one(this._inputField).removeClass("rn_ErrorField");
			if(document.getElementById('rn_'+ this.instanceID +"_Label")!=null)
                this.Y.one("#rn_"+ this.instanceID +"_Label").removeClass("rn_ErrorLabel");
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
        if ("rn_ServiceRequestActivity_2_form" === this._parentForm) {
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
                RightNow.Event.fire("evt_formFieldValidationPass", eo);
            }
            else {
                RightNow.UI.Form.formError = true;
            }
        }
        else {
            RightNow.Event.fire("evt_formFieldValidationFailure", eo);
        }
        RightNow.Event.fire("evt_formFieldValidationPass", eo);
    },

    /**
    * Validation routine to check if field is required, and if so, ensure it has a value
    * @return Boolean denoting if required check passed
    */
    _validateRequirement: function () {
        if (this.data.attrs.required && this._inputField.disabled == false) {
            if (this.data.js.type === "Boolean") {
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
        this.Y.one(this._inputField).removeClass("rn_ErrorField");
        //this.Y.one("#rn_"+this.instanceID+"_Label").removeClass("rn_ErrorLabel");
        return true;
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

                var elementId = (this.Y.Lang.isArray(this._inputField)) ? this._inputField[0].id : this._inputField.id,
                    inputLabel = this.data.attrs.label_error || this.data.attrs.label_input,
                    label = (errorMessage.indexOf("%s") > -1) ? RightNow.Text.sprintf(errorMessage, inputLabel) : inputLabel + ' ' + errorMessage;

                commonErrorDiv.innerHTML += "<div><b><a href='javascript:void(0);' onclick='document.getElementById(\"" +
                    elementId + "\").focus(); return false;'>" + label + "</a></b></div> ";
            }
        }
        this.Y.one(this._inputField).addClass("rn_ErrorField");
        this.Y.one("#rn_"+this.instanceID+"_Label").addClass("rn_ErrorLabel");
    },

    /**
    * Returns the String (Radio/Select) or Boolean value (Check) of the element.
    * @return String/Boolean that is the field value
    */
    _getValue: function () {
        if (this.data.js.type === "Boolean") {
            if (this._inputField[0].checked)
                return this._inputField[0].value;
            if (this._inputField[1].checked)
                return this._inputField[1].value;
        }
        else if (this.data.js.type === "Checkbox") {
            if (this._inputField.type === "checkbox")
                return this._inputField.checked;
            return this._inputField.value === "1";
        }
        else {
            //select value
            //See if this is being used as a search option and if the value is currently blank
            if (this.data.attrs.is_search_filter == true && this._inputField.options[this._inputField.selectedIndex].value == '')
                return '~any~';

            if (this.data.attrs.usetextasvalue)
                return this._inputField.options[this._inputField.selectedIndex].text; ;
            return this._inputField.value;
        }
    },

    _onGetFiltersRequest: function (type, args) {
        this._setFilter();
        RightNow.Event.fire("evt_searchFiltersResponse", this._eo);

    }
    
});