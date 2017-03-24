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
            YUI().use('calendar', 'datatype-date', 'cssbutton',  function (Y) {
              var cal1 = new Y.Calendar({
                  contentBox: "#cal1Container",
                  height:'200px',
                  width:'600px',
                  showPrevMonth: true,
                  showNextMonth: true,
                  date: new Date()}).render();
            var dtdate = Y.DataType.Date;
                     cal1.set('showPrevMonth', !(cal1.get("showPrevMonth")));
                 cal1.set('showNextMonth', !(cal1.get("showNextMonth")));
                Y.one("#cal1Container").on("mouseover", function() { over_cal = true; });
            Y.one("#cal1Container").on("mouseout", function() { over_cal = false; });
                Y.one('#cal1Date').on("focus",showCal);
            Y.one("#cal2Date").on("focus",showCal);
            Y.one("#cal1Date").on("blur",hideCal);
            Y.one("#cal2Date").on("blur",hideCal);
                cal1.on("selectionChange", function (ev) {
                var newDate = ev.newSelection[0];
                var newDate1= ev.newSelection[1];
                Y.one("#cal1Date").setHTML(dtdate.format(newDate));
                Y.one("#cal2Date").setHTML(dtdate.format(newDate));
           
        });
            });
            
          //  cal1.selectEvent.set(getDate, cal1, true);
         //   cal1.renderEvent.set(setupListeners, cal1, true);
           
          
     }
        
//     var getDate = function () {
//            var calDate = this.getSelectedDates()[0];
//            calDate = (calDate.getMonth() + 1) + '/' + calDate.getDate() + '/' + calDate.getFullYear();
//            cur_field.value = calDate;
//            over_cal = false;
//            hideCal();
//        }
            var showCal = function (ev) {
            var tar = ev._currentTarget;
            cur_field = tar;

            var xy = Y.all("#cal1Container").getXY(tar),
                date = Dom.get(tar).value;
            if (date) {
                cal1.cfg.setProperty('selected', date);
                cal1.cfg.setProperty('pagedate', new Date(date), true);
            } else {
                cal1.cfg.setProperty('selected', '');
                cal1.cfg.setProperty('pagedate', new Date(), true);
            }
            cal1.render();
            Dom.setStyle('cal1Container', 'display', 'block');
            xy[1] = xy[1] + 20;
            Dom.setXY('cal1Container', xy);
        }

        var hideCal = function () {
            if (!over_cal) {
                Dom.setStyle('cal1Container', 'display', 'none');
            }
        }

        init();

        //Event.addListener(window, 'load', init);

    },
    _onGetFiltersRequest: function () {
        this._setFilter();
        RightNow.Event.fire("evt_searchFiltersResponse", this._eo);
    }
});