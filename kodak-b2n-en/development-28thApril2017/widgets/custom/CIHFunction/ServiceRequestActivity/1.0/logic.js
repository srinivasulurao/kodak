RightNow.namespace('Custom.Widgets.CIHFunction.ServiceRequestActivity');
Custom.Widgets.CIHFunction.ServiceRequestActivity = RightNow.SearchFilter.extend({ 
    /**
     * Widget constructor.
     */
   overrides:
    {
    constructor: function(data,instanceID) {
        this.parent();
        this.data=data;
        this.instanceID=instanceID;
        this._eo = new RightNow.Event.EventObject();
        this._inc_selector = document.getElementById("inc_selector");
        this._searchResultsContainer = document.getElementById("searchResultsContainer");
        this._advanceSearchPanel = document.getElementById("rn_"+ this.instanceID+"_advSearchPanel");
        this._advanceSearchPanelTrigger= document.getElementById("rn_"+ this.instanceID+ "_advSearchPanelTrigger");
        if(this.data.js.customer_type != 'direct')
            this._disablePanel(this._inc_selector,true);
        
        if(this.data.js.enhanced_customer_type == 'direct' || this.data.js.enhanced_customer_type == 'direct_plus_one' || this.data.js.enhanced_custome_type == 'one_non_direct')
            this._searchRequest();
        
        RightNow.Event.subscribe("evt_partnerTypeSearchChanged", this._partnerTypeChanged, this);
        RightNow.Event.subscribe("evt_searchRequest", this. _searchRequest, this);
//        this.searchSource().on("search",this._searchRequest,this);
//        this.searchSource().on("search", this._searchRequest,this);
        this.Y.one(this._advanceSearchPanelTrigger).on("click", this._toggleAdvanceSearchPanel, this);
        this._checkAdvSearchCookie();
    }
    },

    _checkAdvSearchCookie: function() {
        var advSearchCookie = this._readCookie('sra_adv_search_tab');
        if( advSearchCookie != null)
            this._advanceSearchPanel.style.display = advSearchCookie;
    },
    _toggleAdvanceSearchPanel: function (evt,args){
        var advState = this._advanceSearchPanel.style.display == 'none' ? 'block' : 'none';
        this._advanceSearchPanel.style.display =advState;
        this._createCookie("sra_adv_search_tab", advState, 1);
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
    _searchRequest: function(evt,args)
    {
        this.Y.one(this._searchResultsContainer).removeClass('rn_Hidden');
    },
     _showHideAdvancedSearch: function () {
        var advancedSearch = document.getElementById('advancedSearchPanel');
        var advState = advancedSearch.style.display == 'none' ? 'block' : 'none'
        advancedSearch.style.display = advState;
        RightNow.Widget.ServiceRequestActivity.prototype._createCookie("sra_adv_search_tab", advState, 1);
    },
    _readCookie: function(name){
        var nameEQ = name + "=";
        var ca= document.cookie.split(';');
        for(var i = 0; i< ca.length; i++){
            var c =ca[i];
            while (c.charAt(0) == ' ') 
                c=c.substring(1, c.length);
            
            if(c.indexOf(nameEQ)== 0)
                return c.substring(nameEQ.length, c.length);
        }
        return null;
    },
    _disablePanel: function (el, disabled) {

        try {
            el.disabled = disabled;
        }
        catch (E) {
        }
        if (el.childNodes && el.childNodes.length > 0) {
            for (var x = 0; x < el.childNodes.length; x++) {
                this._disablePanel(el.childNodes[x], disabled);
            }
        }

    },

    _partnerTypeChanged: function (evt, args) {
        if (args[0].data.initial_search == true) {
            if (this.data.js.enhanced_customer_type == 'direct' || this.data.js.enhanced_customer_type == 'direct_plus_one' || this.data.js.enhanced_customer_type == 'one_non_direct')
                this._searchRequest();
        }
        if (args[0].customer_type != 'direct') {
            this._disablePanel(this._inc_selector, true)
            this.Y.one("#"+this._inc_selector.id).addClass("rn_Hidden");
        }
        else {
            this._disablePanel(this._inc_selector, false)
            this.Y.one("#"+this._inc_selector.id).removeClass("rn_Hidden");

        }
    }
});