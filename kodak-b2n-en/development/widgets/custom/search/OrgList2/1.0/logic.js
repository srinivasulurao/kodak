RightNow.namespace('Custom.Widgets.search.OrgList2');
Custom.Widgets.search.OrgList2 = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function(data,instanceID) {
        this.data = data;
        this.instanceID = instanceID;
        this._eo = new RightNow.Event.EventObject();
        this._fieldName = "rn_" + this.instanceID + "_Options";
        this._optionsSelect = document.getElementById(this._fieldName);

        var RightNowEvent = RightNow.Event;
        RightNow.Event.subscribe("evt_orgTypeResponse", this._onChangedResponse, this);
        RightNow.Event.subscribe("evt_reportResponse", this._onChangedResponse, this);
        
        if (this._optionsSelect.disabled == false) {
            RightNow.Event.subscribe("evt_getFiltersRequest", this._onGetFiltersRequest, this);
        }
        
        RightNowEvent.subscribe("evt_resetFilterRequest", this._onResetRequest, this);
        RightNow.Event.subscribe("evt_partnerTypeSearchChanged", this._partnerTypeChanged, this);
        this.Y.one(this._optionsSelect).on("change",this._onSelectChange,this);
        this._setFilter();
        },

    _partnerTypeChanged: function (evt, args) {
        console.log("OrgList2 Partnertypechanged");
        if (args[0].customer_type == 'direct') {
            RightNow.Event.subscribe("evt_getFiltersRequest", this._onGetFiltersRequest, this);
        }
        else {
            RightNow.Event.unsubscribe("evt_getFiltersRequest", this._onGetFiltersRequest);
        }

    },

    /**
    * Event handler executed when drop down is changed
    *
    * @param evt object Event
    */
    _onSelectChange: function (evt) {
        this._setSelectedFilters();
        RightNow.Event.fire("evt_orgTypeRequest", this._eo);
        if (this.data.attrs.search_on_select) {
            this._eo.filters.reportPage = this.data.attrs.report_page_url;
            //RightNow.Event.fire("evt_searchRequest", this._eo);
        }
    },

    /**
    * Sets the selected dropdown item to the one matching the passed-in value.
    * @param valueToSelect Int Value of item to select
    * @return Boolean Whether or not the operation was successful
    */
    _setSelectedDropdownItem: function (valueToSelect) {
        if (this._optionsSelect) {
            for (var i = 0; i < this._optionsSelect.length; i++) {
                if (this._optionsSelect.options[i].value == valueToSelect) {
                    this._optionsSelect.selectedIndex = i;
                    return true;
                }
            }
        }
        return false;
    },

    /**
    * Internal function to set the event object values
    */
    _setSelectedFilters: function () {
        if (this._optionsSelect) {
            var index = Math.max(0, this._optionsSelect.selectedIndex);
            if (this._optionsSelect.options[index]) {
                var value = this._optionsSelect.options[index].value,
                    label = this._optionsSelect.options[index].text;
            }
        }
        else {
            value = this.data.js.defaultIndex;
        }

        this._eo.w_id = this.instanceID;

        this._eo.filters.data = {
            val: this.data.js[value].val,
            fltr_id: this.data.js[value].fltr_id,
            oper_id: this.data.js[value].oper_id,
            selected: value,
            selected_index: this._optionsSelect.selectedIndex,
            w_id:this._fieldName
        };

        if (label)
            this._eo.filters.data.label = label;
    },

    /**
    * internal event to set the initial event object values
    *
    */
    _setFilter: function () {
        this._eo.w_id = this.instanceID;
        this._eo.filters = { "rnSearchType": this.data.js.rnSearchType,
            "report_id": this.data.attrs.report_id,
            "searchName": this.data.js.searchName,
            "data": { "fltr_id": this.data.js[this.data.js.defaultIndex].fltr_id,
                "oper_id": this.data.js[this.data.js.defaultIndex].oper_id,
                "val": this.data.js[this.data.js.defaultIndex].val,
                "selected": this.data.js.defaultIndex,
                "w_id":this._fieldName
            }
        };
        this._setSelectedFilters();
    },

    _getValue: function () {

    },

    /**
    * Event handler executed when the org type data is updated
    *
    * @param type string Event type
    * @param args object Arguments passed with event
    */
    _onChangedResponse: function (type, args) {
        console.log("OrgList2 onchangedresponse");
        var data = RightNow.Event.getDataFromFiltersEventResponse(args, this.data.js.searchName, this.data.attrs.report_id);
        var newValue;
        if ((data == null || data.selected == null) && this.data.js.defaultIndex != null)
            newValue = this.data.js.defaultIndex;
        else if (data == null && data.selected == null)
            newValue = 0;
        else
            newValue = data.selected;

        this._setSelectedDropdownItem(newValue);
        this._setSelectedFilters();
    },

    /**
    * Responds to the filterReset event by setting the internal eventObject's data back to default
    * @param type String Event name
    * @param args Object Event object
    */
    _onResetRequest: function (type, args) {
        console.log("OrgList2 onresetrequest");
        if (RightNow.Event.isSameReportID(args, this.data.attrs.report_id) && (args[0].data.name === this.data.js.searchName || args[0].data.name === "all")) {
            this._setSelectedDropdownItem(this.data.js.defaultIndex);
            this._setFilter();
        }
    },

    /**
    * Event handler executed when search filters are requested - fires the event object
    *
    * @param type string Event type
    * @param args object Arguments passed with event
    */
    _onGetFiltersRequest: function (type, args) {
        console.log("OrgList2 ongetfiltersrequest ajax");
        this._setSelectedFilters();
        if (this._optionsSelect.disabled == false) {
            RightNow.Event.fire("evt_searchFiltersResponse", this._eo);
        }
        else {
            RightNow.Event.fire("evt_searchFiltersResponse", null);
        }
    }
});