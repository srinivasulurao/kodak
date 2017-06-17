RightNow.namespace('Custom.Widgets.CIHFunction.AutoSearch');
Custom.Widgets.CIHFunction.AutoSearch = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function(data, instanceID) {
                this.data = data;
    this.instanceID = instanceID;
    this._searchButton = document.getElementsByName("rnServiceRequestSearchButton")[0];// document.getElementById('rn_SearchButton2_7_SubmitButton');
    
    if (this.data.js.enhanced_customer_type == 'direct' || this.data.js.enhanced_customer_type == 'direct_plus_one' || this.data.js.enhanced_customer_type == 'one_non_direct')
        this._runSearch();
    },
_runSearch: function (parameter) {
        //this._searchButton.click();
        //setTimeout('RightNow.Event.fire("evt_autoSearch")',1);

    }
});