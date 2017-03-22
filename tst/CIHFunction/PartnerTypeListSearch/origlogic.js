RightNow.Widget.PartnerTypeListSearch = function (data, instanceID) {
    this.data = data;
    this.instanceID = instanceID;
    this._eo = new RightNow.Event.EventObject();
    this._parentForm = RightNow.UI.findParentForm('rn_' + this.instanceID + '_Options');
    this._fieldName = "rn_" + this.instanceID + "_Options";

    this._eo.data.form = this._parentForm;
    this._inputField = document.getElementById(this._fieldName);

    YAHOO.util.Event.addListener(this._inputField, "change", this._onSelectChange, null, this);
    RightNow.Event.subscribe('evt_formFieldValidateRequest', this.onValidate, this);

    this._setSelectedDropdownItem(this.data.js.defaultFilter);
    this._eo.data.initial_search = true;
    this._onSelectChange();
    this._eo.data.initial_search = false;

    RightNow.Event.subscribe('evt_resetForm', this._onResetForm, this);

    RightNow.Event.subscribe("on_before_ajax_request", this._ajaxIntercept, this);
    RightNow.Event.subscribe("evt_partnerTypeChange",this._onSelectChange,this)

    if (this.data.js.customer_type != 'direct') {
        RightNow.Event.subscribe("evt_getFiltersRequest", this._onGetFiltersRequest, this);
        this._setFilter();
    }
    //this.data.js.defaultValue = null;

};

RightNow.Widget.PartnerTypeListSearch.prototype = {


    /**
    * Responds to the filterReset event by setting the internal eventObject's data back to default
    * @param type String Event name
    * @param args Object Event object
    */
    _onResetRequest: function (type, args) {
        if (RightNow.Event.isSameReportID(args, this.data.attrs.report_id) && (args[0].data.name === this.data.js.searchName || args[0].data.name === "all")) {
            this._setSelectedDropdownItem(this.data.js.defaultValue);
            this._eo.filters.data.val = this.data.js.defaultValue;
        }
    },
    /**
    * sets the initial event object data
    *
    */
    _setFilter: function () {

        this._eo.w_id = this.instanceID;
        this._eo.filters = { "searchName": 'org', //'custom' + this.instanceID,
            "rnSearchType": 'org',
            "report_id": this.data.attrs.search_report_id,            
            "data": { "fltr_id": this._getFilterID(),
                "oper_id": this.data.attrs.search_operator_id,
                "val": this._getValue(),
                "w_id":this._fieldName
            }
        };

    },

    _setEmptyFilter: function () {
        this._eo.w_id = this.instanceID;
        this._eo.filters = { "searchName": 'org', //'custom' + this.instanceID,
            "rnSearchType": 'org',
            "report_id": this.data.attrs.search_report_id,
            "data": { "fltr_id": "16",
                "oper_id": this.data.attrs.search_operator_id,
                "val": this._getValue(),
                "w_id":this._fieldName
            }
        };

    },

    _getFilterID: function () {
        if (this.data.js.partner_filters.length > 0) {
            var currentText = this._getText();
            for (var i = 0; i < this.data.js.partner_filters.length; i++) {
                if (this.data.js.partner_filters[i].partner_type == currentText) {
                    return this.data.js.partner_filters[i].filter_id.toString();
                }
            }
        }
    },

    /**
    * Event handler executed when the select box is changed
    *
    * @param evt object Event
    */
    _onSelectChange: function (evt) {
        this._setSelected();
        var currentValue = this._getValue();


        this._eo.customer_type = currentValue == 'direct' ? 'direct' : 'non_direct';

        if (currentValue != 'direct') {


            RightNow.Event.unsubscribe("evt_getFiltersRequest", this._onGetFiltersRequest);
            RightNow.Event.subscribe("evt_getFiltersRequest", this._onGetFiltersRequest, this);

            this._setFilter();
        }
        else {
            RightNow.Event.unsubscribe("evt_getFiltersRequest", this._onGetFiltersRequest);
        }

        RightNow.Event.fire("evt_partnerTypeSearchChanged", this._eo);

    },

    /**
    * internal function to set the event object from the column select box value
    */
    _setSelected: function () {
        if (this._inputField) {
            this.data.js.value = this._getValue();
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
    /*
    onValidate: function (type, args) {
        this._eo.data.name = this.data.attrs.name;
        this._eo.data.value = this.data.js.value;
        RightNow.Event.fire('evt_formFieldValidateResponse', this._eo);

        RightNow.Event.fire('evt_formFieldCountRequest');
    },
    */
    onValidate: function (type, args) {

        this._eo.data.name = this.data.attrs.name;
        this._eo.data.value = this.data.js.value;

        if(this._eo.data.name == "partnertype")
          this._eo.data.value = "";
     
        this._formErrorLocation = args[0].data.error_location;

        if (this._validateRequirement()) {

            this._eo.w_id = this.data.info.w_id;
            RightNow.Event.fire("evt_formFieldValidateResponse", this._eo);
        }
        else {
            RightNow.UI.Form.formError = true;
        }

        RightNow.Event.fire("evt_formFieldCountRequest");
    },

    _validateRequirement: function () {
        if (this._inputField.value <= 0) {
                this._displayError(this.data.attrs.label_required);
                return false;
            }

        YAHOO.util.Dom.removeClass(this._inputField, "rn_ErrorField");
        YAHOO.util.Dom.removeClass("rn_" + this.instanceID + "_Label", "rn_ErrorLabel");
        return true;
    },

    _displayError: function (errorMessage) {
        alert('Please select a Partner Type');
    },

    _onResetForm: function (evt, args) {
        if (args[0].data.form === this._parentForm) {
            this._inputField.selectedIndex = 0;
        }
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
    },

    _getText: function () {
        return this._inputField.options[this._inputField.selectedIndex].text; ;
    },

    _onGetFiltersRequest: function (type, args) {
        this._setFilter();
        RightNow.Event.fire("evt_searchFiltersResponse", this._eo);
    },

    _ajaxIntercept: function (evt, args) {


        if (args[0].url == '/ci/ajaxRequest/getReportData' && args[0].post.report_id == this.data.attrs.search_report_id) {

             
            args[0].url = '/cc/ajaxRequest/getReportData';
        }
    }


};
