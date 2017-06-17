RightNow.namespace('Custom.Widgets.output.CurrentMeter');
Custom.Widgets.output.CurrentMeter = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
        /* this._wPanel = null;
		this._createWaitPanel(); */

		this._compID = null;

		RightNow.Event.subscribe('evt_displayCurrentMeter', this._displayData, this);
    },

     _displayData: function (evt, args) {

        var errorDiv = document.getElementById("meter_err_"+this.instanceID);
        errorDiv.innerHTML = "";
		
		//console.log(args);

        this._compID = args[0].data.compID;

        /* meterDataSource = new YAHOO.util.DataSource(args[0].data.meterData);

        meterDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY; */

       
            fields: [
                    { key: "id" },
                    { key: "descr" },
                    { key: "reading" },
                    { key: "unit" },
                    { key: "date" },
                    { key: "source" }
                    ]
        
        current_meter_counter=0;
		instance_id=this.instanceID;
        var meterColumnDefs = [
                  { key: "id", label: this.data.js.pidmv_counterid, sortable: false, maxAutoWidth: 80 },
                  { key: "descr", label: this.data.js.pidmv_description, sortable: false, maxAutoWidth: 100 },
                  { key: "reading", label: this.data.js.pidmv_lastreadingvalue, sortable: false, maxAutoWidth: 80,allowHTML:true, formatter:function(row){
					  reading_data_id="valMeter_"+row.data.id;
					  return "<span id='"+reading_data_id+"' >"+row.data.reading+"</span>";
				  }
				  },
                  { key: "unit", label: this.data.js.pidmv_unit, sortable: false, maxAutoWidth: 30 },
                  { key: "source", label: this.data.js.pidmv_readingsource, sortable: false, maxAutoWidth: 70 },
                  { key: "date", label: this.data.js.pidmv_lastreadingdate, sortable: false, maxAutoWidth: 80 , allowHTML: true, formatter:function(row){
					  reading_date_id="valMeterDate_"+row.data.id;
					  return "<span id='"+reading_date_id+"' >"+row.data.date+"</span>";
				  }
				  },
                  { key: "newReading", label: this.data.js.pidmv_currentreading, sortable: false, allowHTML:true,maxAutoWidth: 84, formatter:function(row){
					  
					  var fld = row.data.id;
					  var inputID = "valMeter_"+fld+"_"+instance_id;
					  
					  var meter_text_field='<input style="width:90px" id="'+inputID+'" type="text" class="meter_reading" >';
					  
					  return meter_text_field;
				  }
				  },
                  { key: "newDate", label: this.data.js.pidmv_dateofreading, sortable: false,allowHTML:true,maxAutoWidth: 84, formatter:function(row){
					 
					var fld = row.data.id;
					var inputID = "valMeterDate_"+fld+"_"+instance_id;
					var meter_data_text_field = '<input style="width:90px" id="'+inputID+'" type="text" style="font-size:12px"  placeholder="YYYY-MM-DD" class="meter_date" >';
					return meter_data_text_field;
					
				  }					  
				  },
                  { key: "button", label:this.data.js.pidmv_action, allowHTML:true, formatter:function(row){
					  
					var update_meter_button = '<button id="'+current_meter_counter+'" class="yui-dt-button" type="button">UPDATE</button>';
					current_meter_counter++;
					return update_meter_button;
					  
				  }},
                  ];
       

        //YAHOO.widget.DataTable.Formatter.meterTextField = this._wrapFormatter(this.instanceID);
		
		

        /* var dtextFieldFormatter = function(el, oRecord, oColumn, oData) {
          var markup = '<input id="valMeterDate" type="text" class="meter_reading" />';

          el.innerHTML = markup;

        };

        //YAHOO.widget.DataTable.Formatter.meterDateTextField = this._wrapDateFormatter(this.instanceID); 

        var displaytxt = this.data.js.update;
        var updateCustomFormatter = function (elCell, oRecord, oColumn, oData) {
            elCell.innerHTML = '<button id="" class="yui-dt-button" type="button">'+displaytxt+'</button>';
        }; */

        // Add the custom formatter to the shortcuts
        //YAHOO.widget.DataTable.Formatter.updateMeterButton = updateCustomFormatter;

       
		
		instance_id=this.instanceID;
		var current_meter_table="";
		YUI().use('datatable', function (Y) {
						table = new Y.DataTable({
							columns: meterColumnDefs,
							data:args[0].data.meterData,
							highlightMode:"row",
							selectionMode: 'row'
						});
						
						 //this.meterDataTable = new YAHOO.widget.DataTable("rn_div_currentMeter_"+this.instanceID, meterColumnDefs, meterDataSource);
						document.getElementById("rn_div_currentMeter_"+instance_id).innerHTML="";
						table.render("#rn_div_currentMeter_"+instance_id);
						current_meter_table=table;
							   
			});

        //this.meterDataTable.subscribe("buttonClickEvent", this._sendMeterUpdate, this.meterDataTable, this);
		
		if(document.querySelectorAll('#div_meters2 .yui3-datatable-data tr').length)
		   this.Y.all("#div_meters2 .yui3-datatable-data .yui-dt-button").on("click",this._sendMeterUpdate,this,current_meter_table);
	
    },

    _wrapFormatter : function(instanceID) {
 
      return function(el, oRecord, oColumn, oData) {

          var fld = oRecord.getData("id");
          var inputID = "valMeter_"+fld+"_"+instanceID;
          var markup = '<input id="'+inputID+'" type="text" style="font-size:12px" class="meter_reading" />';

          el.innerHTML = markup;

      };

    },

    _wrapDateFormatter : function(instanceID) {

      return function(el, oRecord, oColumn, oData) {

          var fld = oRecord.getData("id");
          var inputID = "valMeterDate_"+fld+"_"+instanceID;
          var markup = '<input id="'+inputID+'" type="text" style="font-size:12px" class="meter_date" value="YYYY-MM-DD" />';
 
          el.innerHTML = markup;

          YAHOO.util.Event.addListener(inputID, "focus",  function () {

            var dateField = document.getElementById(inputID);
            if(dateField.value === "YYYY-MM-DD")
              dateField.value = ""; 
           }, null, this);

          YAHOO.util.Event.addListener(inputID, "blur", function () {

            var dateField = document.getElementById(inputID);
            if(dateField.value === "")
              dateField.value = "YYYY-MM-DD";
           }, null, this);
      };

    },

    _onFocus: function() {
      alert("focus on date field");
    },

    _sendMeterUpdate: function (evt, eo) {
		
	    rowIndex=parseInt(evt._currentTarget.id);
	

        //make ajax call and process
		
        /* var oRecord = eo.getRecord(evt.target);

        var fldID = oRecord.getData("id");
        var fldDescr = oRecord.getData("descr");
        var fldReading = oRecord.getData("reading"); 
        var fldUnit = oRecord.getData("unit");
        var fldDate = oRecord.getData("date");
        var fldSource = oRecord.getData("source"); */

			
		cells=eo.getRecord(rowIndex);
		oRecord=cells._state.data;
		
		var fldID = oRecord.id.value;
        var fldDescr = oRecord.descr.value;
        var fldReading = oRecord.reading.value;
        var fldUnit = oRecord.unit.value;
        var fldDate = oRecord.date.value;
        var fldSource = oRecord.source.value;
		

        var inputID = "valMeter_"+fldID+"_"+this.instanceID;
        var valMeter = document.getElementById(inputID).value; 

        var inputDateID = "valMeterDate_"+fldID+"_"+this.instanceID;
        var valMeterDate = document.getElementById(inputDateID).value; 
               
        if(!valMeterDate && !valMeter){
			
			return false;
		}			

        var mypostData = {};

        mypostData['meterID'] = oRecord.id.value;
        mypostData['meterValue'] = valMeter;
        mypostData['meterDate'] = valMeterDate;
        mypostData['productID'] = this._compID;
        mypostData['sapid'] = (oRecord.hasOwnProperty('SAPID'))?oRecord.SAPID.value:"";

        //alert("id: " + fldID + "  data: " + valMeter);
		


        this._wPanel('show');

        this._overrideAjaxMethodSendMeter();

        RightNow.Ajax.makeRequest("/cc/ibase_search/sendMeterUpdate", mypostData, {
            data: { eventName: "evt_getMeterResponse" },
            successHandler: function (myresponse) {

                var resp = RightNow.JSON.parse(myresponse.responseText);
                
                this._wPanel('hide');

                if (resp.status === 1) {
                    var errorDiv = document.getElementById("meter_err_"+this.instanceID);
                    errorDiv.innerHTML = "";
                    //show success message
                    /* pnl = new YAHOO.widget.Panel("success_meter_panel", {width:"620px", visible:false, draggable:false, close:true } );
                    pnl.setBody('Meter ID: '+  mypostData['meterID'] + ' Meter update was successful.');
                    pnl.render("meter_success_"+this.instanceID);
                    pnl.show(); */
					
					YUI().use('panel', 'dd-plugin', function(Y) { 

						var success_panel = new Y.Panel({
							srcNode      : '#panelContentlaslkdaskj',
							headerContent: "Success",
							bodyContent: 'Meter ID: '+  mypostData['meterID'] + ' Meter update was successful.',
							width        : 250,
							zIndex       : 5,
							centered     : true,
							modal        : true,
							visible      : false,
							render       : true,
							plugins      : [Y.Plugin.Drag]
						});
						
						success_panel.show();
						
						//Set the current reading values.
						document.getElementById('valMeter_'+mypostData['meterID']).innerHTML=mypostData['meterValue'];
						document.getElementById('valMeterDate_'+mypostData['meterID']).innerHTML=mypostData['meterDate'];
						
		            });

                    var eop = new RightNow.Event.EventObject();
                    eop.data.productData = resp;
                    RightNow.Event.fire("evt_NewProductData", eop);
                    
                    //Focus the tab.
                    tab_focus=document.querySelectorAll("#rn_div_currentMeter_"+this.instanceID)[0].parentNode;
                    focus_tab_id=tab_focus.id.split("div_meters").join("panelDetails");
                    document.querySelectorAll("#"+focus_tab_id+" .pid_tabs")[3].click();
                    
                }
                else {
                    var errorDiv = document.getElementById("meter_err_"+this.instanceID);
                    errorDiv.innerHTML = "</br>ERROR RETURNED FROM METER UPDATE</br>" + resp.error + "</br>";
                }



            },
            failureHandler: function (myresponse) {
                this._wPanel('hide');
            },
            scope: this,
            timeout: 120000
        });
		
		//Reset the value
		var inputID = "valMeter_"+fldID+"_"+this.instanceID;
		document.getElementById(inputID).value="";

        var inputDateID = "valMeterDate_"+fldID+"_"+this.instanceID;
        document.getElementById(inputDateID).value="";
		

   },

   _overrideAjaxMethodSendMeter: function () {

        this._overrideSendMeterAjaxMethodUnsubscribe();
        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_getMeterResponse") {
                eo[0].url = '/cc/ibase_search/sendMeterUpdate';
            }
        }, this);

   },

   _overrideSendMeterAjaxMethodUnsubscribe: function () {
        RightNow.Event.unsubscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_getMeterResponse") {
                eo[0].url = '/cc/ibase_search/sendMeterUpdate';
            }
        });
    },

    _createWaitPanel: function () {
        this._wPanel =
                    new YAHOO.widget.Panel("waitSendMeterUpdate_"+this.instanceID,
                                                    { width: "240px",
                                                        fixedcenter: true,
                                                        close: false,
                                                        draggable: false,
                                                        zindex: 4,
                                                        modal: true,
                                                        visible: false
                                                    }
                                                );

        this._wPanel.setHeader(this.data.js.loadingmessage);
        this._wPanel.setBody("<img src=\"/euf/assets/images/rel_interstitial_loading.gif\"/>");
        this._wPanel.render(document.body);

    },
	_wPanel: function (val) {
		var loadingmessage=this.data.js.loadingmessage;
		YUI().use('panel', 'dd-plugin', function(Y) { 

						var wait_panel = new Y.Panel({
							srcNode      : '#panelContent4224252456',
							headerContent: loadingmessage,
							bodyContent: '<img src=\"/euf/assets/images/rel_interstitial_loading.gif\"/>"',
							width        : 250,
							zIndex       : 5,
							centered     : true,
							modal        : true,
							visible      : false,
							render       : true,
							plugins      : [Y.Plugin.Drag]
						});
				 if(val=="show")
						wait_panel.show();
					if(val=="hide"){
					   for(i=0;i<=document.getElementsByClassName('yui3-button-close').length;i++){
						   if(document.getElementsByClassName('yui3-button-close')[i]!=null)
							  document.getElementsByClassName('yui3-button-close')[i].click();
						 }
					}  

		});

	  },
});