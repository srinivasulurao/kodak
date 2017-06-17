RightNow.namespace('Custom.Widgets.reports.Grid');
Custom.Widgets.reports.Grid = RightNow.Widgets.Grid.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.Grid#constructor.
         */
        constructor: function() {
        this.parent();
        },
            _onReportChanged: function(type, args)
    {
        var sortData = RightNow.Event.getDataFromFiltersEventResponse(args, this.data.js.searchName, this.data.attrs.report_id);
        if (sortData)
            this._sortEo.filters.data = sortData;
        else
            this._setSortData();

        var newdata = args[0].data;
        if(newdata.report_id == this.data.attrs.report_id)
        {
            this._setLoading(false);
            var currentPageSize = newdata.per_page,
                cols = newdata.headers.length,
                report = document.getElementById(this._contentName),
                str = "<table id='" + this._gridName + "' class='yui-dt-table'>" +
                    "<caption>" + this.data.attrs.label_caption + "</caption>",
                i, j;

           //Add the new results to the widgets's DOM
            if(this.data.attrs.headers)
            {
                str += "<thead class='GridHead'><tr>" + 
                ((newdata.row_num)
                    ? "<th scope='col' class='GridHeader'>" + this.data.attrs.label_row_number + "</th>"
                    : "");
                for(i = 0; i < cols; i++)
                    str += "<th scope='col' class='GridHeader' style='width:\"" + newdata.headers[i].width + "%\"'>" + newdata.headers[i].heading + "</th>";
                str += "</tr></thead>";
            }
            if(newdata.total_num > 0)
            {
                str += "<tbody class='yui-dt-body>'";
                for (i = 0; i < currentPageSize; i++)
                {
                    str += "<tr class='" + ((i % 2 === 0) ? 'yui-dt-even' : 'yui-dt-odd') + "'>" + 
                    ((newdata.row_num)
                        ? "<td>" + (newdata.start_num + i) + "</td>"
                        : "");
                    for(j = 0; j < cols; j++)
                        str += "<td>" + ((newdata.data[i][j] !== "") ? newdata.data[i][j] : '&nbsp;')  + "</td>";
                    str += "</tr>";
                }
                str += "</tbody>";
                if(this.data.attrs.hide_when_no_results)
                    this.Y.one("#rn"+this.instanceID).removeClass("rn_Hidden");
            }
            else if(this.data.attrs.hide_when_no_results)
            {
                    this.Y.one("#rn"+this.instanceID).addClass("rn_Hidden");
            }
            str += "</table>";
            report.innerHTML = str;
            if(this.data.attrs.headers)
                this._generateYUITable(this._gridName, this._contentName, newdata.headers);
            //now allow expand/contract
            this.Y.one(report).setStyle("height","auto");
            RightNow.Url.transformLinks(document.getElementById(this._contentName));
            document.body.setAttribute("aria-busy", "false");

            if(newdata.total_num > 0)
            {
                this._updateAriaAlert(this.data.attrs.label_screen_reader_search_success_alert);

                //focus on the first result
                var anchors = null;
                if(this._grid){
                    anchors = this._grid.getFirstTdEl().getElementsByTagName("A");
                }
                else{
                    anchors = document.getElementById(this._gridName).getElementsByTagName("A");
                }
                
                if(anchors && anchors[0])
                    anchors[0].focus();
            }
            else
            {
                //don't focus anywhere, stay where you are so you can perhaps try a new search
                this._updateAriaAlert(this.data.attrs.label_screen_reader_search_no_results_alert);
            }
        }
    },

    /*
        /**
         * Overridable methods from Grid:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _setFilter: function()
        // _setSortData: function(columnID, sortDirection)
        // _searchInProgress: function(evt, args)
        // _setLoading: function(loading)
        // _onReportChanged: function(type, args)
        // _setTableData: function(headers)
        // _generateYUITable: function(headers)
        // _onSort: function(sortEvent)
        // _getDirectionToSort: function(columnID)
        // _setSortedColumn: function(columnID, dir)
        // _fireSortEvent: function()
        // _onSortTypeResponse: function(type, args)
        // _updateAriaAlert: function(text)
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});