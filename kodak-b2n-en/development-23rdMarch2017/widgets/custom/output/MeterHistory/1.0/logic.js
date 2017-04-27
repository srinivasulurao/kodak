RightNow.namespace('Custom.Widgets.output.MeterHistory');
Custom.Widgets.output.MeterHistory = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
       
	   RightNow.Event.subscribe('evt_displayMeterHistory', this._displayData, this);
	   
    },
        _displayData: function (evt, args) {
			
			//
					//experimental - meter history
					//
					//meterhistoryDataSource = new YAHOO.util.DataSource(args[0].data.meterData);
					//meterhistoryDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;
			
			/* meterhistoryDataSource.responseSchema = {
						resultsList: "", // String pointer to result data
						fields: [
								{ key: "id" },
								{ key: "descr" },
								{ key: "reading" },
								{ key: "unit" },
								{ key: "date" },
								{ key: "source" }
								]
					}; */

					var meterhistoryColumnDefs = [
							  { key: "id", label: this.data.js.pidmvmh_counterid, sortable: false },
							  { key: "descr", label: this.data.js.pidmvmh_description, sortable: false },
							  { key: "reading", label: this.data.js.pidmvmh_lastreadingvalue, sortable: false },
							  { key: "unit", label: this.data.js.pidmvmh_unit, sortable: false },
							  { key: "source", label: this.data.js.pidmvmh_readingsource, sortable: false },
							  { key: "date", label: this.data.js.pidmvmh_lastreadingdate, sortable: false },
							  ];
							  
							  
					YUI().use('datatable', function (Y) {
								
							table = new Y.DataTable({
								columns: meterhistoryColumnDefs,
								data:args[0].data.meterData,
								highlightMode:"row",
								selectionMode: 'row'
							});
					
							//this.meterhistoryDataTable = new YAHOO.widget.DataTable("rn_div_meterHistory_"+this.instanceID, meterhistoryColumnDefs, meterhistoryDataSource);
							document.getElementById("rn_div_meterHistory_MeterHistory_12").innerHTML="";
							table.render("#rn_div_meterHistory_MeterHistory_12");
			
	                }); //YUI event Ends here.
      }
});