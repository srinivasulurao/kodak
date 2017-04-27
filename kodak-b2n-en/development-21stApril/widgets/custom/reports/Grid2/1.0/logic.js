RightNow.namespace('Custom.Widgets.reports.Grid2');
Custom.Widgets.reports.Grid2 = RightNow.ResultsDisplay.extend({ 
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
        this._dataTable = null;
        this._sortEo = new RightNow.Event.EventObject();
        this._contentName = "rn_" + this.instanceID + "_Content";
        this._gridName = "#rn_" + this.instanceID + "_Grid";
        //this._gridName = this.baseSelector + '_Grid';
        this._loadingName = "rn_" + this.instanceID + "_Loading";
            this._columns = [];
            this._data = [];
            this._sortOn = null;
        
         RightNow.Event.subscribe('evt_getFiltersRequest', this._fireSortEvent, this);
//    RightNow.Event.subscribe("evt_reportResponse" , this._onReportChanged, this);
//        this.searchSource().on('response', this._onReportChanged, this);
//    RightNow.Event.subscribe("evt_searchInProgressResponse", this._searchInProgress, this);
                    this.searchSource().on('response', this._onReportChanged, this)
                               .on('send', this._searchInProgress, this);
            RightNow.Event.subscribe('evt_sortTypeResponse', this._onSortTypeResponse, this);
//            this.searchSource().on('response', this._onReportChanged, this);
                               
        this._setFilter();
        RightNow.Event.fire("evt_setInitialFiltersRequest", this._eo);
    
    // hack for xhtml compliance
        var dummyElement = document.getElementById("rn_" + this.instanceID + "_Tbody");
        if(dummyElement)
            dummyElement.parentNode.removeChild(dummyElement);
        
        if(this.data.attrs.headers)
            this._generateYUITable(this._gridName, this._contentName, this.data.js.headers);
    
        if (RightNow.Event.isHistoryManagerFragment()) {
            this._setLoading(true);
        }
        else {
            var table;
            this._updateAriaAlert(((this._grid && this._grid.getRecord(0)) || ((table = document.getElementById(this._gridName)) && table.rows.length)) 
                                  ? this.data.attrs.label_screen_reader_search_success_alert
                                  : this.data.attrs.label_screen_reader_search_no_results_alert);
        }
    }
    },

    _setFilter: function()
    {
        this._sortEo.w_id = this.instanceID;
        this._sortEo.filters = {"report_id": this.data.attrs.report_id,
                                "searchName": this.data.js.searchName,
                                "report_page": "",
                                "data" : {}
                               };

        this._eo.w_id = this.instanceID;
        this._eo.filters = {"report_id": this.data.attrs.report_id,
                            "token": this.data.js.token,
                            "allFilters": this.data.js.filters,
                            "format": this.data.js.format
                            };
        this._eo.filters.format.parmList = this.data.attrs.add_params_to_url;
    },
    _searchInProgress: function(type, args)
    {
        if(args[0].filters.report_id == this.data.attrs.report_id)
        {
            document.body.setAttribute("aria-busy", "true");
            this._setLoading(true);
        }
    },
    _setSortData: function()
    {
        this._setFilter();
        this._sortEo.filters.data =  {"col_id": this.data.js.colId,
                                      "sort_direction": this.data.js.sortDirection
                                     };
    },
    _setLoading: function(loading)
    {
        if(loading)
        {
            var toOpacity = 1,
            method = 'removeClass';
            var element = document.getElementById(this._contentName);
            if(element)
            {
                //this.Y.all("#"+element).setStyle("height", element.offsetHeight + "px");
                this._contentName.setStyle('height', this._contentName.get('offsetHeight') + 'px');
                //IE rendering: so bad it can't handle eye-candy
                if(this.Y.UA.ie)
                    this.Y.one(element).addClass("rn_Hidden");
                else{
                    toOpacity = 0;
                    method = 'addClass';
                    this._contentName.transition({
                    opacity: toOpacity,
                    duration: 0.4
                    });
                }
                    this.Y.one("#"+this._loadingName).addClass("rn_Loading");
                
            }
        }
        else
        {
            this.Y.one("#"+this._loadingName).removeClass("rn_Loading");
            if(this.Y.UA.ie)
                this.Y.one("#"+this._contentName).removeClass("rn_Hidden");
            else
            {
                toOpacity = 0;
                method = 'addClass';
                this._contentName.transition({
                opacity: toOpacity,
                duration: 0.4
            });
            }
        }
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
                    this.Y.one("#rn_"+this.instanceID).removeClass("rn_Hidden");
            }
            else if(this.data.attrs.hide_when_no_results)
            {
                this.Y.one("#rn_"+ this.instanceID).addClass("rn_Hidden");
            }
            str += "</table>";
            report.innerHTML = str;
            if(this.data.attrs.headers)
                this._generateYUITable(this._gridName, this._contentName, newdata.headers);
            //now allow expand/contract
            this.Y.one("#"+report).setStyle("height","auto");
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
     _generateYUITable: function(source,dest,headers)
    {
        var dataTypes = this.data.js.dataTypes,
            sortDirection = this._sortEo.filters.data.sort_direction,
            sortColumnID = this._sortEo.filters.data.col_id,
            sortDirectivesPresent = (sortDirection !== null && sortColumnID !== null),
            rowNumbersPresent = this.data.js.rowNumber,
            key, columnID, header, rowObj, rows, i,
            iconColumns =  [];

        this._columns = [];
        this._data = [];
        if (rowNumbersPresent) {
            this._columns.push({key: 'c0', label: this.data.attrs.label_row_number, sortable: false});
        }

        for (i = 0; i < headers.length; i++) {
            header = headers[i];
            if (!header.visible)
                continue;
            columnID = header.col_id;
            key = 'c' + columnID;
            if (sortDirectivesPresent && sortColumnID === columnID) {
                this._sortOn = {key: key, dir: sortDirection};
            }
            var isSortable = !this.Y.Lang.isArray(this.data.attrs.exclude_from_sorting) || this.Y.Array.indexOf(this.data.attrs.exclude_from_sorting, (i + 1).toString()) === -1;

            this._columns.push({
            key: key,
            label: (iconColumns.indexOf(columnID.toString()) !== -1) ? "<span class='rn_ScreenReaderOnly'>" + header.heading + "</span>" : header.heading,
            columnID: columnID,
            sortable: isSortable,
            emptyCellValue: "&nbsp;",
            allowHTML: true,
            className: "rn_GridColumn_" + columnID,
            title: isSortable ? header.heading : ""
            });
        }

        // traverse html table to obtain data
        if (rows = this.Y.all(this._gridName + ' tbody tr')) {
            rows.each(function (row) {
                rowObj = {};
                row.all('td').each(function (td, i) {
                    rowObj[this._columns[i].key] = td.getContent();
                }, this);
                this._data.push(rowObj);
            }, this);
        }
          this._dataTable = new this.Y.DataTable({
            data:    this._data,
            columns: this._columns,
            caption:  this.data.attrs.label_caption,
            strings: {
                MSG_EMPTY:      RightNow.Interface.getMessage('NO_RECORDS_FOUND_MSG'),
                MSG_SORTASC:    RightNow.Interface.getMessage('CLICK_TO_SORT_ASCENDING_CMD'),
                MSG_SORTDESC:   RightNow.Interface.getMessage('CLICK_TO_SORT_DESCENDING_CMD')
            }
        });
        if(typeof this._sortEo.filters.data != 'undefined' && this._sortEo.filters.data.sort_direction != null && this._sortEo.filters.data.col_id != null)
        {
            for (var i=0; i < gridColumns.length; i++)
            {
                if (gridColumns[i].colId == this._sortEo.filters.data.col_id)
                {
                    var sortKey = gridColumns[i].key;
                    if (sortKey)
                    {
                        var sortDirection = (this._sortEo.filters.data.sort_direction == 1) ? "asc" : "desc";
                        configs.sortedBy = {key: sortKey, dir: sortDirection};
                        break;
                    }
                }
            }
        }
        this._dataTable.on('sort', this._onSort, this);

        this.Y.one(this._gridName).remove();
        this._dataTable.render("#"+this._contentName);

        if (!this._data.length) {
            this._dataTable.showMessage(RightNow.Interface.getMessage('NO_RECORDS_FOUND_MSG'));
            this._dataTable.setAttrs({'sortable': false});
            this._sortOn = null;
        }
        else {
            this.Y.one(this._contentName + ' .yui3-datatable-message-content').remove();
        }

        if (this._sortOn) {
            this._setSortedColumn(this._sortOn.key, this._sortOn.dir);
        }

        // adds an aria-labelledby tag to the liner div to appease the oghag toolbar
        this.Y.all(this._contentName + " th .yui3-datatable-sort-liner").each(function(node) {
            node.setAttribute('aria-labelledby', node.ancestor('th').get('id'));
        }, this);
    },
    _setSortedColumn: function(columnID, dir) {
        var yuiPrefix = 'yui3-datatable-',
            sortedClass = yuiPrefix + 'sorted',
            descClass = yuiPrefix + 'sorted-desc',
            table = this.Y.one('#' + this._dataTable.get('id')),
            header = table.one('th.' + yuiPrefix + 'col-' + columnID + '.' + yuiPrefix + 'sortable-column');

        if (header) {
            var headerLiner = header.one('.yui3-datatable-sort-liner'),
                sortLabel = (dir === this._directions.asc) ?
                    RightNow.Interface.getMessage('SORTED_ASCENDING_LBL') :
                    RightNow.Interface.getMessage('SORTED_DESCENDING_LBL');

            headerLiner.append('<span class="rn_ScreenReaderOnly">' + sortLabel + '</span>');
        }

        if (!this._prevSorted && this.data.js.columnID) {
            this._prevSorted = 'c' + this.data.js.columnID;
        }
        if (this._prevSorted) {
            table.all('.' + yuiPrefix + 'col-' + this._prevSorted).removeClass(sortedClass).removeClass(descClass);
        }

        if (dir === this._directions.asc) {
            header.removeClass(descClass);
        }
        else {
            header.addClass(descClass);
        }

        header.addClass(sortedClass);
        table.all('td.' + yuiPrefix + 'col-' + columnID).addClass(sortedClass);
        this._prevSorted = columnID;
    },
    _onGetFiltersRequest: function(type, args)
    {
         RightNow.Event.fire("evt_searchFiltersResponse", this._sortEo);
    },

    /**
    * Event handler executed when the sort type is changed
    *
    * @param type string Event type
    * @param args object Arguments passed with event
    */
    _onSortTypeResponse: function(type, args)
    {
        var evt = args[0];
        if (evt.filters.report_id == this.data.attrs.report_id)
        {
            this._sortEo.filters.data.col_id = evt.filters.data.col_id;
            this._sortEo.filters.data.sort_direction = evt.filters.data.sort_direction;
        }
    },
    
     _updateAriaAlert: function(text) {
        this._ariaAlert = this._ariaAlert || document.getElementById("rn_" + this.instanceID + "_Alert");
        if(this._ariaAlert) {
            this._ariaAlert.innerHTML = text;
        }
    }
});