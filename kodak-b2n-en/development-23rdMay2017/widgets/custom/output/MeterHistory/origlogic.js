RightNow.Widget.MeterHistory = function (data, instanceID) {
    //Data object contains all widget attributes, values, etc.
    this.data = data;
    this.instanceID = instanceID;


   RightNow.Event.subscribe('evt_displayMeterHistory', this._displayData, this);
 
};
RightNow.Widget.MeterHistory.prototype = {
    //Define any widget functions here

    _displayData: function (evt, args) {

        //
        //experimental - meter history
        //
        meterhistoryDataSource = new YAHOO.util.DataSource(args[0].data.meterData);
        meterhistoryDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

        meterhistoryDataSource.responseSchema = {
            resultsList: "", // String pointer to result data
            fields: [
                    { key: "id" },
                    { key: "descr" },
                    { key: "reading" },
                    { key: "unit" },
                    { key: "date" },
                    { key: "source" }
                    ]
        };

        var meterhistoryColumnDefs = [
                  { key: "id", label: "Counter ID", sortable: false },
                  { key: "descr", label: "Description", sortable: false },
                  { key: "reading", label: "Last Reading</br>Value", sortable: false },
                  { key: "unit", label: "Unit", sortable: false },
                  { key: "source", label: "Reading Source", sortable: false },
                  { key: "date", label: "Last Reading</br>Date", sortable: false },
                  ];


        this.meterhistoryDataTable = new YAHOO.widget.DataTable("rn_div_meterHistory_"+this.instanceID, meterhistoryColumnDefs, meterhistoryDataSource);	
	
    },
	
    
};

