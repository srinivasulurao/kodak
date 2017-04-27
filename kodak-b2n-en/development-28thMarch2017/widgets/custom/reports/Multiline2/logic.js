RightNow.Widget.Multiline2 = function(data, instanceID){
    this.data = data;
    this.instanceID = instanceID;
    this._eo = new RightNow.Event.EventObject();
    this._contentName = "rn_" + this.instanceID + "_Content";
    this._loadingName = "rn_" + this.instanceID + "_Loading";

    if (RightNow.Event.isHistoryManagerFragment())
        this._setLoading(true);
    RightNow.Event.subscribe("evt_reportResponse",  this._onReportChanged, this);
    RightNow.Event.subscribe("evt_searchInProgressRequest", this._searchInProgress, this);
    this._setFilter();
    RightNow.Event.fire("evt_setInitialFiltersRequest", this._eo);
};

RightNow.Widget.Multiline2.prototype = {
    /**
     * Initilization function to set up search filters for report
     */
    _setFilter: function()
    {
        this._eo.w_id = this.instanceID;
        this._eo.filters = {"report_id": this.data.attrs.report_id,
                            "token": this.data.js.r_tok,
                            "allFilters": this.data.js.filters,
                            "format": this.data.js.format
                           };
      //  this._eo.filters.format.parmList = this.data.attrs.add_params_to_url;
    },
    /**
    * Event handler received when search data is changing.
    * Shows progress icon during searches
    *
    * @param type string Event type
    * @param args object Arguments passed with event
    */
    _searchInProgress: function(type, args)
    {
        if(args[0].filters.report_id == this.data.attrs.report_id)
        {
            document.body.setAttribute("aria-busy", "true");
            this._setLoading(true);
        }
    },

    /**
    * changes the loading icon and hides/unhide the data
    * @param loading bool
    */
    _setLoading: function(loading)
    {
        if (loading)
        {
            var element = document.getElementById(this._contentName);
            if (element)
            {
                //keep height to prevent collapsing behavior
                YAHOO.util.Dom.setStyle(element, "height", element.offsetHeight + "px");
                //IE rendering: so bad it can't handle eye-candy
                if(YAHOO.env.ua.ie)
                    YAHOO.util.Dom.addClass(element, "rn_Hidden");
                else
                    (new YAHOO.util.Anim(element, { opacity: {to: 0 } }, 0.4, YAHOO.util.Easing.easeIn)).animate();
                YAHOO.util.Dom.addClass(this._loadingName, "rn_Loading");
            }
        }
        else
        {
            YAHOO.util.Dom.removeClass(this._loadingName, "rn_Loading");
            if(YAHOO.env.ua.ie)
                YAHOO.util.Dom.removeClass(this._contentName, "rn_Hidden");
            else
                (new YAHOO.util.Anim(this._contentName, { opacity: {to: 1 } }, 0.4, YAHOO.util.Easing.easeIn)).animate();
        }
    },

    /**
     * Event handler received when report data is changed
     *
     * @param tyep string Event type
     * @param args object Arguments passed with event
     */
    _onReportChanged: function(type, args)
    {
        var newdata = args[0].data;
        this._setLoading(false);
        var alertDiv = document.getElementById("rn_" + this.instanceID + "_Alert");
        if (newdata.report_id == this.data.attrs.report_id)
        {
            var currentPageSize = newdata.per_page;
            var cols = newdata.headers.length;
            var str = "";

            var report = document.getElementById(this._contentName);
            if (!report)
                return;
            if(newdata.total_num > 0)
            {
                if(alertDiv)
                    alertDiv.innerHTML = this.data.attrs.label_screen_reader_search_success_alert;
                //Add the new results to the widgets's DOM
                if(newdata.row_num)
                    str += '<ol start="' + newdata.start_num + '">';
                else
                    str += '<ul>';

                for (var i=0; i < currentPageSize ; i++)
                {                    str += '<li>';
                    str += '<span class="rn_Element1">' + newdata.data[i][0] + '&nbsp;</span>';
                    str += (newdata.data[i][1]) ? '<span class="rn_Element2">' + newdata.data[i][1] + '</span>' : '';
                    str += '<br/>';
                    str += (newdata.data[i][2]) ? '<span class="rn_Element3">' + newdata.data[i][2] + '</span><br/>' : '';
                    for (var j = 3; j < cols; j++)
                    {
                        str += '<span class="rn_ElementsHeader">' + newdata.headers[j]['heading'];
                        if(newdata.headers[j]['heading'] != "")
                            str += ':&nbsp;';
                        str += '</span>';
                        str += '<span class="rn_ElementsData">' + newdata.data[i][j] + '</span><br/>';
                    }
                    str += '</li>';
                }
                if(newdata.row_num)
                    str += '</ol>';
                else
                    str += '</ul>';
                report.innerHTML = str;
                if(this.data.attrs.hide_when_no_results)
                    YAHOO.util.Dom.removeClass('rn_' + this.instanceID, 'rn_Hidden');
            }
            else
            {
                report.innerHTML = "";
                if(alertDiv)
                    alertDiv.innerHTML = this.data.attrs.label_screen_reader_search_no_results_alert;
                if(this.data.attrs.hide_when_no_results)
                    YAHOO.util.Dom.addClass('rn_' + this.instanceID, 'rn_Hidden');
            }
            
            //now allow expand/contract
            YAHOO.util.Dom.setStyle(report, "height", "auto");
            RightNow.Url.transformLinks(report);
            document.body.setAttribute("aria-busy", "false");
            //focus on the first result
            var anchors = report.getElementsByTagName('a');
            if(anchors && anchors[0])
                anchors[0].focus();
        }
    }
};