RightNow.Widget.DateInputCalendar = function (data, instanceID) {
    //Data object contains all widget attributes, values, etc.
    this.data = data;
    this.instanceID = instanceID;
    this._over_cal = false;
    this._eo = new RightNow.Event.EventObject();
    this._fieldName = 'cal1Container';
    this._calendar = document.getElementById(this._fieldName);
    this._fromDate = document.getElementById("cal1Date");
    this._toDate = document.getElementById("cal2Date");
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event
    this._createCalendar();

    RightNow.Event.subscribe("evt_getFiltersRequest", this._onGetFiltersRequest, this);

};
RightNow.Widget.DateInputCalendar.prototype = {

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

        var Dom = YAHOO.util.Dom,
            Event = YAHOO.util.Event,
            cal1,
            over_cal = false,
            cur_field = '';

        var init = function () {
            cal1 = new YAHOO.widget.Calendar("cal1", "cal1Container");
            cal1.selectEvent.subscribe(getDate, cal1, true);
            cal1.renderEvent.subscribe(setupListeners, cal1, true);
            Event.addListener(['cal1Date', 'cal2Date'], 'focus', showCal);
            Event.addListener(['cal1Date', 'cal2Date'], 'blur', hideCal);
            cal1.render();
        }

        var setupListeners = function () {
            Event.addListener('cal1Container', 'mouseover', function () {
                over_cal = true;
            });
            Event.addListener('cal1Container', 'mouseout', function () {
                over_cal = false;
            });
        }

        var getDate = function () {
            var calDate = this.getSelectedDates()[0];
            calDate = (calDate.getMonth() + 1) + '/' + calDate.getDate() + '/' + calDate.getFullYear();
            cur_field.value = calDate;
            over_cal = false;
            hideCal();
        }

        var showCal = function (ev) {
            var tar = Event.getTarget(ev);
            cur_field = tar;

            var xy = Dom.getXY(tar),
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

};
