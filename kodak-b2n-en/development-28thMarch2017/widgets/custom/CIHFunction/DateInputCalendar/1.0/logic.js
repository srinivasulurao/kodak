RightNow.namespace('Custom.Widgets.CIHFunction.DateInputCalendar');
Custom.Widgets.CIHFunction.DateInputCalendar = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function(data, instanceID) {
        this.data = data;
        this.instanceID = instanceID;
        this._over_cal = false;
        this._eo = new RightNow.Event.EventObject();
        this._fieldName = 'cal1Container';
        this._calendar = document.getElementById(this._fieldName);
        this._fromDate = document.getElementById("cal1Date");
        this._toDate = document.getElementById("cal2Date");
        this._createCalendar();
        RightNow.Event.subscribe("evt_getFiltersRequest", this._onGetFiltersRequest, this);
    },
                    
            
_stringToDate: function (mon, dd, yyyy, hh, mm) {
      var newDate = new Date(yyyy, mon, dd, hh, mm);

      return Math.round(newDate.getTime()/1000);

    },
 
    _setFilter: function () {
        console.log("Filters Defined");
        this._eo.w_id = this.instanceID;
        this._eo.filters = { "searchName": 'custom' + this.instanceID,
            "rnSearchType": 'filter',
            "report_id": this.data.attrs.search_report_id,
            "data": { "fltr_id": this.data.attrs.search_filter_id,
                "oper_id": this.data.attrs.search_operator_id,
                "val": this._getValue(),
                "cal_fields": this._fromDate.id + '|' + this._toDate.id,
                "w_id":this._fieldName
            }

        };
        this._eo.filters.data.val = this._getValue();
    },

    _getValue: function () {
        if (this._fromDate.value === "" || this._toDate.value === "") {
            return "";
        }

        var arrFromDate = this._fromDate.value.split("/");
        var arrToDate = this._toDate.value.split("/");

        //date hrs relative to 0600, ie. 6am, zero-indexed so 05=6am
        return this._stringToDate(parseInt(arrFromDate[0])-1, parseInt(arrFromDate[1]), arrFromDate[2], -6,0) + '|' + this._stringToDate(parseInt(arrToDate[0])-1, parseInt(arrToDate[1]), arrToDate[2],17,59);
    },
     _createCalendar: function () {
         var over_cal = false,
            cur_field = '';
         var init = function () {
            YUI().use('calendar',  function(Y) {
                var calendar = new Y.Calendar({
                showNextMonth: false,
                showPrevMonth: false,
                minimumDate: new Date(2001,10,1),
                maximumDate: new Date(2098,1,1),
                date: new Date(),
                width:'160px'});
                calendar.set("tabIndex", 1);
                
                var dtdate = Y.DataType.Date;
                Y.one("#mycalendar").on("mouseover", function() { over_cal = true; });
                Y.one("#mycalendar").on("mouseout", function() { over_cal = false; });
                Y.one('#cal1Date').on("focus",showCal);
                Y.one("#cal2Date").on("focus",showCal);
                Y.one("#cal1Date").on("blur",hideCal);
                Y.one("#cal2Date").on("blur",hideCal);
                calendar.on("selectionChange", function (ev) {
                        var newDate = ev.newSelection[0];
                        cur_field.value=dtdate.format(newDate);
                        over_cal=false;
                        hideCal();

                    });
                calendar.render("#mycalendar");
                });
         }
         var showCal = function (ev) {
                var tar = ev._currentTarget;
                cur_field = tar;
                YUI().use('overlay',  function (Y) {
                 var xy = Y.one(tar).getXY();
                var overlay = new Y.Overlay({
                    srcNode:"#mycalendar",
                    xy:[xy[0], xy[1]+25]
                    });
                    overlay.render();
                    Y.one("#mycalendar").setStyle("display","block");
                });
            }
           var hideCal = function () {
            if (!over_cal) {
                YUI().use("node", function(Y){
                    
                    Y.one("#mycalendar").setStyle("display","none");
                });
                
            }
        }
           init();
        },
    _onGetFiltersRequest: function () {
        this._setFilter();
        RightNow.Event.fire("evt_searchFiltersResponse", this._eo);
    }
});