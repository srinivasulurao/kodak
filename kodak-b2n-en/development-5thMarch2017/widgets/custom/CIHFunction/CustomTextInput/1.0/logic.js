RightNow.namespace('Custom.Widgets.CIHFunction.CustomTextInput');
Custom.Widgets.CIHFunction.CustomTextInput = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function(data, instanceID) {
        this.data=data;
        this.instanceID= instanceID;
        this._formErrorLocation = null;
		this._validated = false;
		this._required = false;
		this._eo = new RightNow.Event.EventObject();
		this._fieldName = 'rn_' + this.instanceID + '_TextInput';
		this._parentForm = RightNow.UI.findParentForm(this._fieldName);
		this._inputField = document.getElementById(this._fieldName);
		this._previousValue = "";
    

    RightNow.Event.subscribe('evt_fieldVisibilityChanged', this._visibilityChanged, this);
    RightNow.Event.subscribe('evt_resetForm', this.onResetForm, this);
    RightNow.Event.subscribe('evt_formFieldValidatePass', this._onValidate, this);
    //this.searchSource().on('submit', this._onValidate, this);
    RightNow.Event.subscribe('evt_toggleRequired', this._toggleRequired, this);
	
		if (this._inputField.disabled != true) {
			this._enableEvents();
		}
		
		this.Y.one(this._inputField).on("change",this._onChange,this);
	
    },

    /**
     * Sample widget method.
     */
    _onChange: function(evt,args){
        if(this._inputField.savedFilter)
            this._previousValue = this._inputField.savedFilter;
    },

    _enableEvents: function () {

        RightNow.Event.subscribe('evt_setTextField', this.onSetTextField, this);
        
        if (this.data.attrs.is_search_filter == true) {
            RightNow.Event.subscribe("evt_getFiltersRequest", this._onGetFiltersRequest, this);
//            this.searchSource().on("search",this._onGetFiltersRequest,this);
            this._setFilter();
        }
        
    },


    _disableEvents: function () {
        //RightNow.Event.unsubscribe('evt_formFieldValidateRequest', this._onValidate);
        RightNow.Event.unsubscribe('evt_setTextField', this.onSetTextField);
        //RightNow.Event.unsubscribe('evt_resetForm', this.onResetForm);

        if (this.data.attrs.is_search_filter == true) {
            RightNow.Event.unsubscribe("evt_getFiltersRequest", this._onGetFiltersRequest);
//            this.searchSource().initialFilters={};
        }

        //RightNow.Event.unsubscribe('evt_fieldVisibilityChanged', this._visibilityChanged, this);

        if (this.data.attrs.required_listener == true) {
            RightNow.Event.unsubscribe('evt_RequiredFieldChanged', this._toggleRequired);
        }
    },

    _visibilityChanged: function (evt, args) {
        //console.log("Custom text input _visibility changed");
        if (args[0].id == this._inputField.id) {
            if (this._inputField.disabled == true) {
                this._disableEvents();
            }
            else {
                this._enableEvents();
            }
        }
    },

    _toggleEvents: function (status) {
        if (status == 'add') {

        }
        if (status == 'remove') {

        }
    },

    /**
    * sets the initial event object data
    *
    */

    _setFilter: function () {
        this._eo.w_id = this.instanceID;
        this._eo.filters = {"searchName": this.instanceID,
            "rnSearchType": 'filter',
            "report_id": this.data.attrs.search_report_id,
            "data": { "fltr_id": this.data.attrs.search_filter_id,
                "oper_id": this.data.attrs.search_operator_id,
                "val": this._getValue(),
                "w_id": this._fieldName
            }

        };
    },

    /*
    * Event handler for setting the value.
    *
    * @param event evt  Event
    * @param array args Args
    */
    onSetTextField: function (evt, args) {
        var eo = args[0];

        if (eo.name != this.data.attrs.name)
            return;

        this.data.js.value = eo.value;
    },

    /**
    * Validation routine to check if field is required, and if so, ensure it has a value
    * @return Boolean denoting if required check passed
    */
    _checkRequired: function () {
        if (this.data.attrs.required) {
            if (this._inputField.value === "" && this._inputField.disabled == false) {
                this._displayError(this.data.attrs.label_required);
                return false;
            }
            else if(this._parentForm.indexOf("rn_ServiceRequestActivity") > -1) {
              var commonErrorDiv = document.getElementById("rn_search_ErrorLocation");
              commonErrorDiv.innerHTML = "";
            }
        }
        return true;
    },

    _toggleRequired: function (evt, args) {
        //console.log("Custom text input _Togglerequired");
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

        var commonErrorDiv = document.getElementById(this._formErrorLocation);

        if(this._parentForm.indexOf("rn_ServiceRequestActivity") > -1)
          commonErrorDiv = document.getElementById("rn_search_ErrorLocation");

        if (commonErrorDiv) {
            RightNow.UI.Form.errorCount++;
            if (RightNow.UI.Form.chatSubmit && RightNow.UI.Form.errorCount === 1)
                commonErrorDiv.innerHTML = "";

            var errorLink = "<div><b><a href='javascript:void(0);' onclick='document.getElementById(\"" + this._inputField.id +
                "\").focus(); return false;'>" + this.data.attrs.label_input + " ";

            if (errorMessage.indexOf("%s") > -1)
                errorLink = RightNow.Text.sprintf(errorMessage, errorLink);
            else
                errorLink = errorLink + errorMessage;

            errorLink += "</a></b></div> ";
            if(this._parentForm.indexOf("rn_ServiceRequestActivity") > -1)
              commonErrorDiv.innerHTML = errorLink;
            else
              commonErrorDiv.innerHTML += errorLink;
        }
        this.Y.one("#"+this._inputField.id).addClass("rn_ErrorField");
        if(document.getElementById("rn_"+ this.instanceID+"_Label")!=null)
        this.Y.one("#rn_"+ this.instanceID+"_Label").addClass("rn_ErrorLabel");
    },

    onResetForm: function (evt, args) {
        //console.log("Custom text input onResetForm");
        if (this._parentForm == args[0].data.form) {
            this._blurValidate();

            this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID + "_TextInput");
            if (args[0].data.form === this._parentForm) {
                this.Y.one(this._inputField).removeClass("rn_ErrorField");
                if(document.getElementById("rn_"+ this.instanceID+"_Label")!=null)
                this.Y.one("#rn_"+ this.instanceID +"_Label").removeClass("rn_ErrorLabel");
                this._inputField.value = '';
            }
        }
    },

    /**
    * Event handler for when form is being submitted
    *
    * @param {String} type Event name
    * @param {Object} args Event arguments
    */

    _onValidate: function (type, args) {
        //console.log("Custom text input _onValidate");
        this._validated = true;
        this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID);
        var eo = new RightNow.Event.EventObject();
        eo.data = { "name": (this._inputField.disabled == true ? '' : this.data.attrs.name),
            "value": this._getValue(),
            "table": this.data.js.table,
            "required": (this.data.attrs.required ? true : false),
            "prev": this.data.js.prev,
            "form": this._parentForm
        };
        if (RightNow.UI.Form.form === this._parentForm || this._parentForm.indexOf("rn_ServiceRequestActivity") > -1 ) {
            this._formErrorLocation = args[0].data.error_location;
            this._trimField();

            if (this._checkRequired()) {
  
                 this.Y.one(this._inputField).removeClass("rn_ErrorField");
                this.Y.one("#rn_"+ this.instanceID +"_Label").removeClass("rn_ErrorLabel");
                if (this.data.attrs.require_validation) {
                     this.Y.one(this._validationField).removeClass("rn_ErrorField");
                     this.Y.one("#rn_"+ this.instanceID +"_LabelValidate").removeClass("rn_ErrorLabel");
                }

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
                if (this.data.js.channelID) {
                    eo.data.channelID = this.data.js.channelID;
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
        this._validated = false;
        RightNow.Event.fire("evt_formFieldCountRequest");
    },

    _blurValidate: function () {

        this._trimField();
        if (this._checkRequired()) {
            var eo = new RightNow.Event.EventObject();
            eo.data.name = this.data.attrs.name;
            eo.data.value = this._inputField.value;
            eo.data.form = RightNow.UI.findParentForm('rn_' + this.instanceID + '_TextInput');

             this.Y.one(this._inputField).removeClass("rn_ErrorField");
                if(document.getElementById("rn_"+ this.instanceID+"_Label")!=null)
                this.Y.one("#rn_"+ this.instanceID +"_Label").removeClass("rn_ErrorLabel");
            RightNow.Event.fire('evt_formFieldValidateResponse', eo);
            return true;
        }
        return false;

    },


    /**
    * Returns the field's value
    * @return Mixed String or Int (for Int data type)
    */
    _getValue: function () {
        if (this.data.js.type === RightNow.Interface.Constants.EUF_DT_INT) {
            if (this._inputField.value !== "")
                return parseInt(this._inputField.value);
        }
        if (this.data.js.mask)
            return this._stripMaskFromFieldValue();
        return this._inputField.value;

    },

    _trimField: function () {
        if (this._inputField.value !== "" && this.data.js.type !== RightNow.Interface.Constants.EUF_DT_PASSWORD)
            this._inputField.value = this.Y.Lang.trim(this._inputField.value);
        return true;
    },

    _onGetFiltersRequest: function (type, args) {
        this._setFilter();
        
        if ((this._getValue() != "" ) || (this._getValue() == "" && this._previousValue != this._getValue())) {
            RightNow.Event.fire("evt_searchFiltersResponse", this._eo);
        }
        else {
            RightNow.Event.fire("evt_searchFiltersResponse", this._eo);
        
        }
        this._previousValue = this._getValue();
    }

});