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
                    var meterdata_arr=new Array();
					var header=new Array(this.data.js.pidmvmh_counterid,this.data.js.pidmvmh_description, this.data.js.pidmvmh_lastreadingvalue, this.data.js.pidmvmh_unit, this.data.js.pidmvmh_readingsource, this.data.js.pidmvmh_lastreadingdate);
                    alternate_html="<table class='yui3-datatable-table'><tr><th>"+header.join("</th><th>")+"</th></tr>";
					if(args[0].data.meterData!=null){
						for(i=0;i<args[0].data.meterData.length;i++){
							meterdata_arr[i]=args[0].data.meterData[i];
							row_data=new Array(meterdata_arr[i].id, meterdata_arr[i].descr, meterdata_arr[i].reading, meterdata_arr[i].unit, meterdata_arr[i].source, meterdata_arr[i].date);
							alternate_html+="<tr><td>"+row_data.join("</td><td>")+"</td></tr>";
						}
					}
					alternate_html+="</table>";

					var meterhistoryColumnDefs = [
								{ key: "id", label: this.data.js.pidmvmh_counterid, sortable: false },
								{ key: "descr", label: this.data.js.pidmvmh_description, sortable: false },
								{ key: "reading", label: this.data.js.pidmvmh_lastreadingvalue, sortable: false },
								{ key: "unit", label: this.data.js.pidmvmh_unit, sortable: false },
								{ key: "source", label: this.data.js.pidmvmh_readingsource, sortable: false },
								{ key: "date", label: this.data.js.pidmvmh_lastreadingdate, sortable: false }
							  ];
							  
						  
				    instance_id=this.instanceID;	
				    meter_history="";		  
					YUI().use('datatable', function (Y) {	
							table = new Y.DataTable({

								columns: meterhistoryColumnDefs,
								data: meterdata_arr

							});
							//this.meterhistoryDataTable = new YAHOO.widget.DataTable("rn_div_meterHistory_"+this.instanceID, meterhistoryColumnDefs, meterhistoryDataSource);
							document.getElementById("rn_div_meterHistory_"+instance_id).innerHTML=alternate_html;
							//table.render("#rn_div_meterHistory_"+instance_id);
							meter_history=table;
			
	                }); //YUI event Ends here.
      }
});