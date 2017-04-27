RightNow.Widget.SearchButton2OnChange = function(data, instanceID)
{
    this.data = data;
    this.instanceID = instanceID;
    YAHOO.util.Event.addListener("rn_" + this.instanceID, "click", this._startSearch, null, this);
    RightNow.Event.subscribe('evt_startSearchOnChange', this._startSearch, this);
};

RightNow.Widget.SearchButton2OnChange.prototype = {
    /**
    * Event handler executed when the button is clicked
    * @param evt object Event
    */
    _startSearch: function(evt)
    {
        if(YAHOO.env.ua.ie !== 0)
        {
            //since the form is submitted by script, deliberately tell IE to do auto completion of the form data
            if(!this._parentForm)
                this._parentForm = YAHOO.util.Dom.getAncestorByTagName("rn_" + this.instanceID, "FORM");
            if(this._parentForm && window.external && "AutoCompleteSaveForm" in window.external)
            {
                window.external.AutoCompleteSaveForm(this._parentForm);
            }
        }
        var eo = new RightNow.Event.EventObject();
        eo.w_id = this.instanceID;
        eo.filters = {report_id: this.data.attrs.report_id, reportPage: this.data.attrs.report_page_url, target: this.data.attrs.target};
        RightNow.Event.fire("evt_searchRequest", eo);
    }
};
