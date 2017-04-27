RightNow.namespace('Custom.Widgets.CIHFunction.ContactCommunicationOptin');
Custom.Widgets.CIHFunction.ContactCommunicationOptin = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function(data,instanceID) {
        
        //this._waitPanel=null;
        this._form_name="rn_"+this.instanceID+"_div_optintable";
        this._form=document.getElementById(this._form_name);
        this._enableEvents();
        //this._createWaitPanel();
        this._arrList= new Array();
        this._origList = new Array();
        this._idx = new Array();
        this.partnerSelectInstance = null;
        this.partnerCount = 0;
        this._prefDiv = document.getElementById("rn_" + this.instanceID + "_communicationPref");
        RightNow.Event.subscribe('evt_registerPartnerType', this._setPartnerTypeSelector, this);
        RightNow.Event.subscribe('evt_resetForm', this._onResetForm, this);
        RightNow.Event.subscribe('evt_formFieldValidateRequest', this.onValidate, this);
},

    /**
     * Sample widget method.
     */
    _enableEvents: function(evt,args) {
         RightNow.Event.subscribe("evt_contactSelectChanged", this._contactSelectChanged, this);
    },
    
  _test: function (type, args) {

    var x = args;
    
  },
     _setPartnerTypeSelector: function (evt, args) {
//        if (args[0].data.name == "partnerTypeSelect1") {
            this.partnerSelectInstance = args[0].data.partnerTypeSelect;
            var partnerType = document.getElementById(this.partnerSelectInstance);
            if(partnerType!=null)
             this.partnerCount = partnerType.options.length;
//        }
    },

  _toggleDisplay: function () {
        var showState = 'block'; //this._prefDiv.style.display = 'block';
		if(this._prefDiv!=null)
        this._prefDiv.style.display = showState;
    },

  _contactSelectChanged: function (evt, args) {

      var c_id = args[0].filters.data;
	  //console.log(this.partnerCount);

      if(this.partnerCount) {
        this._onGetData(c_id);
        this._toggleDisplay();
      }
  },
     onValidate: function (type, args) {
        var eo = new RightNow.Event.EventObject();
        eo.data.name = 'communication_optin_list';
        eo.data.value = this._getAdds()+this._getDeletes();
        eo.data.form = RightNow.UI.findParentForm('rn_' + this.instanceID + '_div_optintable');
        ///RightNow.Event.fire('evt_formFieldValidateResponse', eo);
        if (RightNow.UI.Form.form === this._parentForm) {
            this._formErrorLocation = args[0].data.error_location;

            if (this._validateRequirement()) {
                if (this.data.js.profile)
                    eo.data.profile = true;
                if (this.data.js.customID) {
                    eo.data.custom = true;
                    eo.data.customID = this.data.js.customID;
                    eo.data.customType = this.data.js.type;
                }
                else {
                    eo.data.custom = false;
                }
                eo.w_id = this.data.info.w_id;
                RightNow.Event.fire("evt_formFieldValidateResponse", eo);
            }
            else {
                RightNow.UI.Form.formError = true;
            }
        }
        else {
            RightNow.Event.fire("evt_formFieldValidateResponse", eo);
        }
        RightNow.Event.fire('evt_formFieldCountRequest');
    },
  _onGetData: function (c_id) {

    var mypostData = {};
    mypostData['c_id'] = c_id;

    this._overrideAjaxMethodContactOptinData();
    RightNow.Ajax.makeRequest("/cc/contact_custom/get_communication_pref", mypostData, {
          data: { eventName: "evt_getContactOptinResponse" },
          successHandler: function (myresponse) {
              var resp = RightNow.JSON.parse(myresponse.responseText);

              this._waitPanel('hide');

              this._myAjaxContactOptinResponse(resp);
              this._overrideAjaxMethodContactOptinUnsubscribe();
              
          },
          failureHandler: function (myresponse) {
              this._waitPanel('hide');
          },
          timeout: 120000,
          scope: this
      });
    

  },

  _overrideAjaxMethodContactOptinData: function () {
        this._overrideAjaxMethodContactOptinUnsubscribe();
        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
			if(eo[0].hasOwnProperty('data')){
				if (eo[0].data.eventName == "evt_getContactOptinResponse") {
					eo[0].url = '/cc/contact_custom/get_communication_pref';
				}
			}
        }, this);
    },

  _overrideAjaxMethodContactOptinUnsubscribe: function () {
        RightNow.Event.unsubscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_getContactOptinResponse") {
                eo[0].url = '/cc/contact_custom/get_communication_pref';
            }
        });
    },

  _myAjaxContactOptinResponse: function (responseList) {

      if(!responseList)
        return;

      var localIdx = new Array();

        //add all current selections
        for(var i=0; i<responseList[0]['communication_preferences'].length; i++) {

          localIdx[localIdx.length] = responseList[0]['communication_preferences'][i].ID;

          if(responseList[0]['communication_preferences'][i].Optin == 1) {
            this._origList[responseList[0]['communication_preferences'][i].ID] = 1;
            this._arrList[responseList[0]['communication_preferences'][i].ID] = 1;

          }
          else {
            this._origList[responseList[0]['communication_preferences'][i].ID] = 0;
            this._arrList[responseList[0]['communication_preferences'][i].ID] = 0;
          }
        }

        this._idx = localIdx;

        var optinCustomFormatter = function (elCell, oRecord, oColumn, oData) {

                
                var isChecked = "";
                if(oRecord._oData.Optin == 1)
                  isChecked = "checked=\"true\"";

                elCell.innerHTML = "<input id=\"rn_commoptin_CheckBox\" type=\"checkbox\" value=\""+oRecord._oData.ID+"\" name=\"comm\" "+isChecked+" />";

        };

        //optinDataSource = new YAHOO.util.DataSource(responseList[0]['communication_preferences']);

        //optinDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

        /* optinDataSource.responseSchema = {
            resultsList: "", // String pointer to result data
            fields: [
                { key: "ID" },
                { key: "Optin" },
                { key: "Label" }
                ]
        }; */

        //YAHOO.widget.DataTable.Formatter.optinCustomFormat = optinCustomFormatter;

        var optinColumnDefs = [
                { key: "Label", label: this.data.js.type, sortable: false },
                { key: "Optin",allowHTML:true,formatter:function(oRecord){
					
					//console.log(row);
					
			    var isChecked = "";
                if(oRecord.data.Optin == 1)
                  isChecked = "checked=\"true\"";

                 $checkbox="<input id=\"rn_commoptin_CheckBox\" type=\"checkbox\" value=\""+oRecord.data.ID+"\" name=\"comm\" "+isChecked+" />";
				
			     return $checkbox;
					
				}, label: this.data.js.optin, sortable: false }
             ];

       
	    instance_id=this.instanceID;
		var communication_option_table="";
		YUI().use('datatable', function (Y) {
				table = new Y.DataTable({
					columns: optinColumnDefs,
					data:responseList[0]['communication_preferences'],
					highlightMode:"row",
					selectionMode: 'row'
		        });
        
		if(document.getElementById("rn_" + instance_id + "_div_optintable")!=null)
		  document.getElementById("rn_" + instance_id + "_div_optintable").innerHTML="";
		table.render("#rn_" + instance_id + "_div_optintable");
		//this.optinDataTable = new YAHOO.widget.DataTable("rn_" + this.instanceID + "_div_optintable", optinColumnDefs, optinDataSource);
		communication_option_table=table;
		
        });
        

         
        //GG this.optinDataTable.subscribe("checkboxClickEvent", this._checkboxHandler, this);

  },

  //update the current settings for preferences
  _checkboxHandler: function(evt, myeo){

    var chkbox = evt.target;
    if(chkbox.checked) {
      myeo._arrList[chkbox.value] = 1;
    }
    else
      myeo._arrList[chkbox.value] = 0;

  },

  _createWaitPanel: function () {
        this._waitPanel =
                    new YAHOO.widget.Panel("waitContacts",
                                                    { width: "240px",
                                                        fixedcenter: true,
                                                        close: false,
                                                        draggable: false,
                                                        zindex: 4,
                                                        modal: true,
                                                        visible: false
                                                    }
                                                );

        this._waitPanel.setHeader(this.data.js.loadingmessage);
        this._waitPanel.setBody("<img src=\"/euf/assets/images/rel_interstitial_loading.gif\"/>");
        this._waitPanel.render(document.body);
  },


  _getAdds: function () {

    var strList = "";

     for(var i=0; i<this._idx.length; i++) {
 
       var idx = parseInt(this._idx[i]);
       if(this._origList[idx] == 0 && this._arrList[idx] == 1) {
         strList += 'A'+idx.toString()+",";
       }
    }

     return strList;
  },


  _getDeletes: function() {

    var strList = "";

     for(var i=0; i<this._idx.length; i++) {

       var idx = parseInt(this._idx[i]);
       if(this._origList[idx] == 1 && this._arrList[idx] == 0) {
         strList += 'D'+idx.toString()+",";
       }
    }

     return strList;

  },
  _waitPanel: function (val) {
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
  
  _waitPanel: function (val) {
		var loadingmessage=this.data.js.loadingmessage;
		YUI().use('panel', 'dd-plugin', function(Y) { 

						var wait_panel = new Y.Panel({
							srcNode      : '#panddsanosahda',
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
	  

  _onResetForm: function (evt, args) {
    if (args[0].data.form === RightNow.UI.Form.form) {

      //clear data from optin table
      if (this.optinDataTable != null) {
        this.optinDataTable.deleteRows(0, this.optinDataTable.getRecordSet()._records.length);
      }

    }
  }
});