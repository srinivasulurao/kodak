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
        

        var meterColumnDefs = [
                  { key: "id", label: this.data.js.pidmv_counterid, sortable: false, maxAutoWidth: 80 },
                  { key: "descr", label: this.data.js.pidmv_description, sortable: false, maxAutoWidth: 100 },
                  { key: "reading", label: this.data.js.pidmv_lastreadingvalue, sortable: false, maxAutoWidth: 80 },
                  { key: "unit", label: this.data.js.pidmv_unit, sortable: false, maxAutoWidth: 30 },
                  { key: "source", label: this.data.js.pidmv_readingsource, sortable: false, maxAutoWidth: 70 },
                  { key: "date", label: this.data.js.pidmv_lastreadingdate, sortable: false, maxAutoWidth: 80 },
                  { key: "newReading", label: this.data.js.pidmv_currentreading, sortable: false, maxAutoWidth: 84, formatter:"meterTextField" },
                  { key: "newDate", label: this.data.js.pidmv_dateofreading, sortable: false,  maxAutoWidth: 84, formatter:"meterDateTextField" },
                  { key: "button", label:this.data.js.pidmv_action, formatter:"updateMeterButton"},
                  ];
       

        //YAHOO.widget.DataTable.Formatter.meterTextField = this._wrapFormatter(this.instanceID);
		
		

        var dtextFieldFormatter = function(el, oRecord, oColumn, oData) {
          var markup = '<input id="valMeterDate" type="text" class="meter_reading" />';

          el.innerHTML = markup;

        };

        //YAHOO.widget.DataTable.Formatter.meterDateTextField = this._wrapDateFormatter(this.instanceID); 

        var displaytxt = this.data.js.update;
        var updateCustomFormatter = function (elCell, oRecord, oColumn, oData) {
            elCell.innerHTML = '<button id="" class="yui-dt-button" type="button">'+displaytxt+'</button>';
        };

        // Add the custom formatter to the shortcuts
        //YAHOO.widget.DataTable.Formatter.updateMeterButton = updateCustomFormatter;

       
		
		instance_id=this.instanceID;
		var comp_table="";
		YUI().use('datatable', function (Y) {
						table = new Y.DataTable({
							columns: meterColumnDefs,
							data:args[0].data.meterData,
							highlightMode:"row",
							selectionMode: 'row'
						});
						
						 //this.meterDataTable = new YAHOO.widget.DataTable("rn_div_currentMeter_"+this.instanceID, meterColumnDefs, meterDataSource);
						document.getElementById("rn_div_currentMeter_"+instance_id).innerHTML="";
						table.render("#rn_div_currentMeter_"+this.instanceID);
						comp_table=table;
							   
			});

        //this.meterDataTable.subscribe("buttonClickEvent", this._sendMeterUpdate, this.meterDataTable, this);
	
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

        //make ajax call and process
        var oRecord = eo.getRecord(evt.target);

        var fldID = oRecord.getData("id");
        var fldDescr = oRecord.getData("descr");
        var fldReading = oRecord.getData("reading");
        var fldUnit = oRecord.getData("unit");
        var fldDate = oRecord.getData("date");
        var fldSource = oRecord.getData("source");	

        var inputID = "valMeter_"+fldID+"_"+this.instanceID;
        var valMeter = document.getElementById(inputID).value; 

        var inputDateID = "valMeterDate_"+fldID+"_"+this.instanceID;
        var valMeterDate = document.getElementById(inputDateID).value; 
               

        var mypostData = {};

        mypostData['meterID'] = oRecord.getData("id");
        mypostData['meterValue'] = valMeter;
        mypostData['meterDate'] = valMeterDate;
        mypostData['productID'] = this._compID;
        mypostData['sapid'] = oRecord.getData("SAPID");

        //alert("id: " + fldID + "  data: " + valMeter);


        this._wPanel.show();

        this._overrideAjaxMethodSendMeter();

        RightNow.Ajax.makeRequest("/cc/ibase_search/sendMeterUpdate", mypostData, {
            data: { eventName: "evt_getMeterResponse" },
            successHandler: function (myresponse) {

                var resp = RightNow.JSON.parse(myresponse.responseText);

                this._wPanel.hide();

                if (resp.status === 1) {
                    var errorDiv = document.getElementById("meter_err_"+this.instanceID);
                    errorDiv.innerHTML = "";
                    //show success message
                    pnl = new YAHOO.widget.Panel("success_meter_panel", {width:"620px", visible:false, draggable:false, close:true } );
                    pnl.setBody('Meter ID: '+  mypostData['meterID'] + ' Meter update was successful.');
                    pnl.render("meter_success_"+this.instanceID);
                    pnl.show();

                    var eop = new RightNow.Event.EventObject();
                    eop.data.productData = resp;
                    RightNow.Event.fire("evt_NewProductData", eop);

/*

                    //find records related to this component meter and refresh by triggering the event
                    for (var i = 0; i < resp[0]['products'].length; i++) {

                      if(this._compID == resp[0]['products'][i]['ID'] || this._compID == resp[0]['products'][i]['compID']) {
                    
                        var eoc = new RightNow.Event.EventObject();
                        eoc.data.meterData = resp[0]['products'][i]['meters']; 
                        eoc.data.compID = this._compID;
                        RightNow.Event.fire("evt_displayCurrentMeter", eoc);

                        var eoh = new RightNow.Event.EventObject();
                        eoh.data.meterData = resp[0]['products'][i]['meter_history'];
                        RightNow.Event.fire("evt_displayMeterHistory", eoh);
                        break; 
                      } 
                    }
*/

                }
                else {
                    var errorDiv = document.getElementById("meter_err_"+this.instanceID);
                    errorDiv.innerHTML = "</br>ERROR RETURNED FROM METER UPDATE</br>" + resp.error + "</br>";
                }



            },
            failureHandler: function (myresponse) {
                this._wPanel.hide();
            },
            scope: this,
            timeout: 120000
        });

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
	__waitPanel: function (val) {
		var loadingmessage=this.data.js.loadingmessage;
		YUI().use('panel', 'dd-plugin', function(Y) { 

						var wait_panel = new Y.Panel({
							srcNode      : '#panelContent',
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