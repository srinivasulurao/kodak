RightNow.namespace('Custom.Widgets.CIHFunction.ServiceRequestActivityHydrateFields');
Custom.Widgets.CIHFunction.ServiceRequestActivityHydrateFields = RightNow.ResultsDisplay.extend({ 
    /**
     * Widget constructor.
     */
    overrides:
    {
    constructor: function(data, instanceID) {
        this.parent();
        this.data = data;
        this.instanceID = instanceID;
        this._eo = new RightNow.Event.EventObject();
        this._searchFilters;
        this._searchHistory = location.href.substring(location.href.lastIndexOf("s=") + 2, location.href.length);
        this._filtersCookieName = 'cookie_service_request_search_filters';

//        RightNow.Event.subscribe("evt_reportResponse", this._reportResponse, this);
        this.searchSource().on('response', this._reportResponse, this);
        this._partnerTypeSelect;
        this._selectControls = document.getElementsByTagName('SELECT');
        for (var sel in this._selectControls) {
            if(this._selectControls[sel].id)
            {
                if (this._selectControls[sel].id.indexOf('PartnerType') > -1) {
                    this._partnerTypeSelect = this._selectControls[sel];
                    break;
                }
            }
        }

        if (this._readCookie(this._filtersCookieName) && window.location.hash != "") {
            var jsonString = this._readCookie(this._filtersCookieName);
            if (jsonString) {
                var results = RightNow.JSON.parse(jsonString);
                this._populateFieldValues(results);
                this._deleteCookie(this._filtersCookieName);
        }
    }
    }
    },
 _reportResponse: function (evt, args) {

        if (args[0].filters.allFilters.filters) {
            this._jsonFilters = RightNow.JSON.stringify(args[0].filters.allFilters.filters);
            this._createCookie(this._filtersCookieName, this._jsonFilters,1);
        }

    },
    _populateFieldValues: function (filterArray) {
        this._searchFilters = filterArray;
        this.fieldFilterData;
        for (var field in this._searchFilters) {
            if (this._searchFilters.hasOwnProperty(field)) {
                this.fieldFilterDataObject = this._searchFilters[field];
                this.fieldFilterData = undefined;
                try {
                    this.fieldFilterData = this._searchFilters[field].filters.data;
                }
                catch (e) { }
                if (this.fieldFilterData != undefined && this.fieldFilterData.w_id != undefined) {
                    var fieldobj = document.getElementById(this.fieldFilterData.w_id);
                    if (fieldobj.tagName) {
                        switch (fieldobj.tagName) {
                            case "SELECT":
                                this._setSelectedDropdownItem(this.fieldFilterData.val, fieldobj, this.fieldFilterData.selected_index);
                                if (this.fieldFilterData.fltr_id == 'incidents.org_id' || this.fieldFilterData.fltr_id == 'incidents.c_id') {
                                    //Get the partner type dropdown and select the Direct option
                                    for (var i = 0; i < this._partnerTypeSelect.length; i++) {
                                        if (this._partnerTypeSelect.options[i].text == 'Direct') {
                                            this._partnerTypeSelect.selectedIndex = i;
                                            this._partnerTypeSelect.options[i].selected = true;
                                            RightNow.Event.fire("evt_partnerTypeChange");
                                        }
                                    }
                                }
                                break;
                            case "INPUT":
                                fieldobj.value = this.fieldFilterData.val;
                                fieldobj.savedFilter = this.fieldFilterData.val;
                                break;
                            case "DIV":
                                this._setCalendarFields(this.fieldFilterData);
                        }
                    }

                }
            }
        }

    },

    _setCalendarFields: function (filterData) {
        var calFields = filterData.cal_fields.split("|");
        var calValues = filterData.val.split("|");
        var fromDate = document.getElementById(calFields[0]);
        var toDate = document.getElementById(calFields[1]);
        var fromVal = calValues[0];
        var toVal = calValues[1];

        if (fromVal > 0) {
            fromDate.value = this._timestampToDate(fromVal);
        }
        if (toVal > 0) {
            toDate.value = this._timestampToDate(toVal);
        }

    },

    _timestampToDate: function (timestamp) {
        var date = new Date(timestamp * 1000);
        return (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();

    },

    _setSelectedDropdownItem: function (valueToSelect, selectbox, selectedIndex) {
        if (selectbox) {
            //The value from the filter will be ~any~ instead of a number when no value is selected for a menu field
            if (valueToSelect == "~any~")
            {
                selectbox.selectedIndex = 0;
                return true;
            }
            if (selectedIndex) {
                selectbox.selectedIndex = selectedIndex;
                return true;
            }
            if (valueToSelect == undefined) {
                selectbox.selectedIndex = 0;
                return true;
            }
            else {
                for (var i = 0; i < selectbox.length; i++) {
                    if (selectbox.options[i].value == valueToSelect) {
                        selectbox.selectedIndex = i;
                        selectbox.options[i].selected = true;
                        return true;
                    }
                }
            }
        }
        return false;
    },

    _createCookie: function (name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        }
        else var expires = "";
        document.cookie = name + "=" + value + expires + "; path=/";
    },

    _readCookie: function (name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },

    _deleteCookie: function (name) {
        document.cookie = name + '=; path=/';
    }


});