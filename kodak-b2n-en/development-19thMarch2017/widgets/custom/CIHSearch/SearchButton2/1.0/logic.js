RightNow.namespace('Custom.Widgets.CIHSearch.SearchButton2');
Custom.Widgets.CIHSearch.SearchButton2 = RightNow.Form.extend({
  
    overrides:
    {
    constructor: function(data , instanceID) {
        this.parent();
        this.data = data;
        this.instanceID= instanceID;
        this._requestInProgress = false;
        this._searchButton = document.getElementById("rn_" + this.instanceID + "Button");
       // this.Y.one(this.baseSelector + "_Button");
        this._eo = new RightNow.Event.EventObject(this);
        this._parentForm = RightNow.UI.findParentForm('rn_' + this.instanceID + 'Button');
        this._enableClickListener();
//        RightNow.Event.subscribe("evt_reportResponse", this._onSearchResponse, this);
        
        this.on('response', this._onSearchResponse, this)
            .on('validation:pass',this._onFormValidated,this);
//        this.on("validation:pass", this._onFormValidated, this);
//        RightNow.Event.subscribe("evt_formValidatedResponse", this._onFormValidated, this);
        RightNow.Event.subscribe("evt_autoSearch", this._startSearch, this);
    }
    },
     _startSearch: function (evt) {

        this._eo.w_id = this.instanceID;
        this._eo.data = { form : this._parentForm, 
                         error_location: "", 
                         f_tok: "" };
        RightNow.Event.fire("evt_formButtonSubmitRequest", this._eo);
    },

    _onFormValidated: function () {
        if (this._requestInProgress)
            return false;
        if (!this.data.attrs.popup_window && (!this.data.attrs.report_page_url && (this.data.attrs.target === '_self')))
            this._disableClickListener();

        if (this.Y.UA.ie !== 0) {
            //since the form is submitted by script, deliberately tell IE to do auto completion of the form data
            if (!this._parentForm) 
                this._parentForm = this.Y.one("rn_"+ this.instanceID).ancestor("FORM");
            if (this._parentForm && window.external && "AutoCompleteSaveForm" in window.external) {
                window.external.AutoCompleteSaveForm(this._parentForm);
            }
        }


        var eo = new RightNow.Event.EventObject();
        eo.w_id = this.instanceID;
        eo.filters = { report_id: this.data.attrs.report_id,
            reportPage: this.data.attrs.report_page_url,
            target: this.data.attrs.target,
            popupWindow: this.data.attrs.popup_window,
            width: this.data.attrs.popup_window_width_percent,
            height: this.data.attrs.popup_window_height_percent
        };

        RightNow.Event.fire("evt_searchRequest", eo);

    },

    /**
    * Event handler executed when search submission returns from server
    * @param type string Event name
    * @param args object Event arguments
    */
    _onSearchResponse: function (type, args) {
        if (args[0].filters.report_id == this.data.attrs.report_id)
            this._enableClickListener();
    },

    /**
    * Enable the form submit control by enabling button and adding an onClick listener.
    */
    _enableClickListener: function () {
        this._requestInProgress = false;
        this.Y.one(this._searchButton).on("click",this._startSearch,this);
    },

    /**
    * Disable the form submit control by disabling button and removing the onClick listener.
    */
    _disableClickListener: function () {
        this._requestInProgress = true;
        this.Y.one(this._searchButton).detach("click",this._startSearch,this);
    }
});