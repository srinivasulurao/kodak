RightNow.namespace('Custom.Widgets.search.ServiceRequestActivityAdvancedSearch');
Custom.Widgets.search.ServiceRequestActivityAdvancedSearch = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function(data , instanceID) {
        this.data= data;
        this.instanceID= instanceID;
        this._eo = new RightNow.Event.EventObject();
        this._ref_no = document.getElementsByName("ek_ibase_updt_type")[0];
        var RightNowEvent = RightNow.Event;
        this._setFilter();
        this._eo.data = this._getValue();
    },
 _setFilters: function(){
        
    },

    /**
    * sets the initial event object data
    *
    */
    _setFilter: function () {
        this._eo.w_id = this.instanceID;
        this._eo.filters = { "searchName": this.data.js.searchName,
            "rnSearchType": this.data.js.rnSearchType,
            "report_id": this.data.attrs.report_id,
            "data": { "fltr_id": this._getFilterID(),
                "oper_id": 1,
                "val": this._getValue()
            }
        };
    },


    _getValue: function () {
        return 'px909';
    },

    _getFilterID: function () {
        return 6;
    },
    /**
    * Event handler executed when search filters are requested - fires the event object
    *
    * @param type string Event type
    * @param args object Arguments passed with event
    */
    _onGetFiltersRequest: function (type, args) {
         RightNow.Event.fire("evt_searchFiltersResponse", this._eo);

    }
});