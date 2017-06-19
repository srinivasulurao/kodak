RightNow.namespace('Custom.Widgets.CIHFunction.RepairRequest');
Custom.Widgets.CIHFunction.RepairRequest = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
		
		this._eo = new RightNow.Event.EventObject();

		this._form_name = "rn_" + this.instanceID + "_form";

		this._form = document.getElementById(this._form_name);

		this._contactForm = document.getElementById("rn_" + this.instanceID + "_contactForm");

		this._createContactContainer = document.getElementById("rn_" + this.instanceID + "_createContact");

		this._createContactButton = document.getElementById("rn_" + this.instanceID + "_createContactButton");

		this._eo.data.form = this._form_name;
		
		this._parentForm = RightNow.UI.findParentForm("rn_" + this.instanceID + "_form");

		this._ppErrorMessage = document.getElementById("rn_" + this.instanceID + "_ppErrorMessage");

		this.Y.one("#"+this._createContactButton.id).on("click", this._showHideContactForm,this);
		
		this.error_field_exist=false;

		RightNow.Event.subscribe('evt_managePanel', this._onManagePanel, this);

		//this._createWaitPanel();

		RightNow.Event.subscribe('evt_populateRepairData', this._populateRepairData, this);
		
		RightNow.Event.subscribe('evt_formErrorExist',this.evt_formErrorExist,this);
		
		RightNow.Event.subscribe('evt_repairRequestContactSelectChanged', this._contactSelectChanged, this); //Added by Srini for dynamic contact detail change.
		
		RightNow.Event.subscribe('evt_formSubmissionSuccess',this._formSubmittedSuccess,this); //Added by Srini for dynamic contact list change.

		if (this._form.isDisabled != true) {


			this._disableEvents();


			this._enableEvents();


		}

    },
	
	   _enableEvents: function (evt, args) {


        RightNow.Event.subscribe("evt_contactSelectChanged", this._contactSelectChanged, this);


        RightNow.Event.subscribe("evt_resetForm", this._resetForm, this);


        RightNow.Event.subscribe('evt_populateRepairData', this._populateRepairData, this);


    },

	_formSubmittedSuccess: function(evt,args){
		
		var empty_contact='{"status":1,"firstname":"","lastname":"","emailaddress":"","officephone":"","mobilephone":"","homephone":"","faxnumber":"","language1":"","language2":"","language3":"","optinglobal":"","optinincident":"","optincisurvey":"","country":"","disabled":"","deactivate":"","ek_phone_extension":"","role":"","login":"","pperrormessage":null,"ek_ext_ref_no":"","cat":"","ek_severity":"","ek_repeatability":"","ek_error_code":""}';
			  empty_contact=JSON.parse(empty_contact);
			  this._ajaxResponse(empty_contact); 
        
		   
		  var eoSite = new RightNow.Event.EventObject();

				  eoSite.w_id = this.instanceID;

				  eoSite.data.orgID = document.querySelectorAll("[name='selectedOrg']")[0].value;

				  RightNow.Event.fire('evt_changeSite', eoSite);
				  
		      
		  //Remove the Additional Fields.
		  
		  document.querySelectorAll("#"+this._parentForm+" [name='ek_ext_ref_no']")[0].value="";
		  document.querySelectorAll("#"+this._parentForm+" [name='cat']")[0].value="";
		  document.querySelectorAll("#"+this._parentForm+" [name='ek_severity']")[0].value="";
		  document.querySelectorAll("#"+this._parentForm+" [name='ek_repeatability']")[0].value="";
		  document.querySelectorAll("#"+this._parentForm+" [name='ek_error_code']")[0].value="";
		  
			  
				  
	   },



    _disableEvents: function (evt, args) {


        RightNow.Event.unsubscribe("evt_contactSelectChanged", this._contactSelectChanged);


        RightNow.Event.unsubscribe("evt_resetForm", this._resetForm);


        RightNow.Event.unsubscribe('evt_populateRepairData', this._populateRepairData);


    },








    _onManagePanel: function (evt, args) {


        


        var hideList, showList;


        hideList = args[0].data.hidelist;


        showList = args[0].data.showlist;





        if (showList != undefined) {


            for (i = 0; i < showList.length; i++) {


                if (showList[i] == this.data.attrs.panel_name) {


                    this._disableEvents(evt, args);


                    this._enableEvents(evt, args);


                    RightNow.Event.fire("evt_resetForm", this._eo);


                }


            }


        }


        if (hideList != undefined) {


            for (i = 0; i < hideList.length; i++) {


                if (hideList[i] == this.data.attrs.panel_name) {


                    this._disableEvents(evt, args);


                }


            }


        }


        


        


        


    },





    _contactSelectChanged: function (evt, args) {


        if (this._form_name !== args[0].data.form)


            return;





        var c_id = args[0].filters.data;


        this._disablePanel(this._contactForm, false);


        if (c_id > 0) {


            this._disablePanel(this._contactForm, true);


            var postData = {};


            postData['c_id'] = c_id;


            if (evt == 'evt_contactSelectChanged')


                this._overrideAjaxMethod('evt_cih_repair_customer');


            this._waitPanel('show'); 


            RightNow.Ajax.makeRequest("/cc/contact_custom/contact_get", postData, {


                data: { eventName: "evt_contactRetrieveResponse" },


                successHandler: function (response) {


                    var resp = RightNow.JSON.parse(response.responseText);


                    this._ajaxResponse(resp);


                    this._waitPanel('hide');


                    //console.dir(response);


                },


                failuerHandler: function (response) {


                    this._waitPanel.hide();


                },


                scope: this


            });





        }
		
		if(parseInt(c_id)==0){
				var empty_contact='{"status":1,"firstname":"","lastname":"","emailaddress":"","officephone":"","mobilephone":"","homephone":"","faxnumber":"","language1":"","language2":"","language3":"","optinglobal":"","optinincident":"","optincisurvey":"","country":"","disabled":"","deactivate":"","ek_phone_extension":"","role":"","login":"","pperrormessage":null}';
				empty_contact=JSON.parse(empty_contact);
				this._ajaxResponse(empty_contact);
		}





    },


    /*


    _contactSelectChanged: function (evt, args) {





    var c_id = args[0].filters.data;





    if (c_id < 1) {





    this._showHideContactForm(null, true);


    }


    else {





    this._showHideContactForm(null, false);


    }





    },


    */





    _resetForm: function () {





    },





    _populateRepairData: function (evt, args) {


        //var sds = args[0].data.sds;


        //fire event to set value


        var eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_sds';


        eor.data.value = args[0].data.sds;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_k_number';


        eor.data.value = args[0].data.knum;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_serial_number';


        eor.data.value = args[0].data.sn;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_service_profile';


        eor.data.value = args[0].data.sp;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_response_profile';


        eor.data.value = args[0].data.rp;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_equip_component_id';


        eor.data.value = args[0].data.equip_id;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_sap_product_id';


        eor.data.value = args[0].data.sap_prod_id;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_sap_soldto_custid';


        eor.data.value = args[0].data.sold_to;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'default_product_array';


        eor.data.value = args[0].data.ibase_product_hier;


        RightNow.Event.fire('evt_setProductSelection', eor);
		
		
		//Srini's Customization.
		eor = new RightNow.Event.EventObject();
        eor.data.name = 'prod';
        eor.data.value = args[0].data.ibase_product_hier;
		console.log(args[0].data.ibase_product_hier);
		RightNow.Event.fire("evt_populateProductCategoryLinking",eor);
        RightNow.Event.fire("evt_setHiddenField", eor);
		
       //


        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_customer_sapid';


        eor.data.value = args[0].data.cust_sapid;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_remote_eos';


        eor.data.value = args[0].data.remoteEOSL;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_onsite_eos';


        eor.data.value = args[0].data.onsiteEOSL;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_enabling_partner';


        eor.data.value = args[0].data.enablingPartner;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_mvs_manfacturer';


        eor.data.value = args[0].data.mfgPartner;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_service_dist';


        eor.data.value = args[0].data.distrPartner;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_service_reseller';


        eor.data.value = args[0].data.resellPartner;


        RightNow.Event.fire("evt_setHiddenField", eor);





        eor = new RightNow.Event.EventObject();


        eor.data.name = 'ek_corporate';


        eor.data.value = args[0].data.corporatePartner;


        RightNow.Event.fire("evt_setHiddenField", eor);








    },





    _disablePanel: function (el, disabled) {








        try {


            el.disabled = disabled;


        }


        catch (E) {


        }


        if (el.childNodes && el.childNodes.length > 0) {


            for (var x = 0; x < el.childNodes.length; x++) {


                this._disablePanel(el.childNodes[x], disabled);


            }


        }





    },





    _showHideContactForm: function (evt, args) {





        if (args == true) {





            //YAHOO.util.Dom.removeClass(this._contactForm, "rn_Hidden");


            //YAHOO.util.Dom.addClass(this._createContactContainer, "rn_Hidden");


            RightNow.Event.fire("evt_resetContactSelection", this._eo);


            this._disablePanel(this._contactForm);


        }


        else {





            //YAHOO.util.Dom.addClass(this._contactForm, "rn_Hidden");


            //YAHOO.util.Dom.removeClass(this._createContactContainer, "rn_Hidden");


            this._disablePanel(this._contactForm);


        }


    },





    _ajaxResponse: function (args) {


        // First check for errors


        var result = args;


        if (!result) {


            alert("Unknown Ajax Error");


        } else if (result.status === 1) {


            this._processValues(result);


        } else {


            // handle the failure, see if there is a result.message for an error


        }





    },





    _processValues: function (result) {





        this._setValues('firstname', result.firstname);


        this._setValues('lastname', result.lastname);


        this._setValues('emailaddress', result.emailaddress);


        this._setValues('officephone', result.officephone);





        //if (result.mobilephone)


            this._setValues('mobilephone', result.mobilephone);





        //if (result.faxnumber)


            this._setValues('faxnumber', result.faxnumber);





        //if (result.language1)


            this._setValues('language1', result.language1);





        //if (result.language2)


            this._setValues('language2', result.language2);





        //if (result.language3)


            this._setValues('language3', result.language3);





        this._setValues('optinglobal', result.optinglobal);


        this._setValues('optinincident', result.optinincident);


        this._setValues('optincisurvey', result.optincisurvey);





        if (typeof result.pperrormessage != 'undefined') {


            if (result.pperrormessage != null) {


                this._ppErrorMessage.innerHTML = result.pperrormessage;


                this.Y.all(this._ppErrorMessage).removeClass("rn_Hidden");


            }


            else {


                this._ppErrorMessage.innerHTML = "";


                this.Y.all(this._ppErrorMessage).addClass("rn_Hidden");


            }


        }


        else {


            this._ppErrorMessage.innerHTML = "";


            this.Y.all(this._ppErrorMessage).addClass("rn_Hidden");


        }





        //if (result.country)


            this._setValues('country', result.country);


        //if (result.ek_phone_extension)


            this._setValues('ek_phone_extension', result.ek_phone_extension);






    },





    _setValues: function (name, value) {





        for (var obj in this._form) {





            //if(typeof obj != 'undefined' && obj != null && this.form != null && this._form[obj] != null && typeof this._form[obj].tagName != 'undefined') {


            if (typeof obj != null && this._form[obj] != null && this._form[obj].tagName != null) {





                switch (this._form[obj].tagName) {


                    case 'INPUT':


                        if (this._form[obj].name == name) {


                            this._form[obj].value = value;


                            if (this._form[obj].type == 'checkbox') {


                                this._form[obj].checked = value;


                            }


                            return;


                        }


                        break;


                    case 'SELECT':


                        if (this._form[obj].name == name) {


                            for (var i = 0; i < this._form[obj].length; i++) {


                                if (this._form[obj].options[i].value == value) {


                                    this._form[obj].selectedIndex = i;


                                    this._form[obj].options[i].selected = true;


                                    return;


                                }


                            }


                        }


                        break;





                }


            }  //end if


        }





    },





    /*


    * Override the submission controller ajax method


    */
	
	evt_formErrorExist:function(type,args){
		
		if(args[0].data.error_field){
		  this.error_field_exist=args[0].data.error_field;
		}
		else{
			this.error_field_exist=false;
		}
	 
	},


    _overrideAjaxMethod: function (str) {


       


        RightNow.Event.unsubscribe('on_before_ajax_request', function (str, eo) {


            //if(str == "evt_cih_repair_customer") {
				
			if(eo[0].hasOwnProperty('data')){
				
				if (eo[0].data.eventName == "evt_contactRetrieveResponse") {

                eo[0].url = '/cc/contact_custom/contact_get';
				
				}
			}

            //}


        });





        RightNow.Event.subscribe('on_before_ajax_request', function (str, eo) {


            //if(str == "evt_cih_repair_customer") {

            /* if(eo[0].hasOwnProperty('data')){
				
				if (eo[0].data.eventName == "evt_contactRetrieveResponse") {
					
                   eo[0].url = '/cc/contact_custom/contact_get';
				   
			    }
			} */


            //}
			
			
			//This will update/Create the whole Ibase Data
		   if(eo[0].url=="/cc/incident_custom/incident_submit_repair_request"){
			   
			   
			   //lets fire the validation request.
			    this.error_field_exist=false;
			    var eor = new RightNow.Event.EventObject();
				 
				eor.data.error_location="rn_"+this.instanceID+"_ErrorLocation";	
				eor.data.coming_form="rn_"+this.instanceID+"_form";
				RightNow.Event.fire("evt_formFieldValidateRequest", eor);
				
				//Stop the form to submit further.
				if(this.error_field_exist){
				  RightNow.Event.fire("evt_formValidateFailure", eor);
				  return false;
				}
			   		
			                 //All Expected values here. 							
							//var fields=new Array('c_id','firstname','officephone','language1','optinglobal','country','rn_ListFailedOrgContacts','lastname','mobilephone','language2','optinincident','ek_phone_extension','emailaddress','faxnumber','language3','optincisurvey','role','disabled','login',"orig_submit_id","orig_submit_name","secondarycontact","ek_ibase_updt_type","products","removal_reason","effective_date","thread","ibase_country","entitlement_type","panel","sesslang","ek_type","ek_enabling_partner","ek_mvs_manfacturer","ek_service_dist","ek_service_reseller","ek_corporate","ek_k_number","ek_serial_number","ek_equip_component_id","ek_sap_product_id","ek_sap_soldto_custid","equipment_location","storenumber","zipcode","state","city","street","ibase_address","sitecustomername","ibase_phone","ibase_lastname","ibase_firstname","product_identifier");
							var fields=new Array('c_id','firstname','lastname','emailaddress','officephone','mobilephone','faxnumber','language1','language2','language3','optinglobal','optinincident','optincisurvey','country','ek_phone_extension','orig_submit_id','orig_submit_name','secondarycontact','disabled','ek_ext_ref_no','categories','prod','cat','ek_severity','ek_repeatability','ek_error_code','ek_remote_access_perm','thread','ek_enabling_partner','ek_mvs_manfacturer','ek_service_dist','ek_service_reseller','ek_corporate','ek_type','ek_sds','ek_k_number','ek_serial_number','ek_service_profile','ek_equip_component_id','ek_sap_product_id','ek_sap_soldto_custid','ek_customer_sapid','ek_remote_eos','ek_onsite_eos','panel','sesslang');
							var ground=(this.data.attrs.panel_name=="accordionRepairRequest2")?"#panelRepairRequest2":"#panelRepairRequest";
							var data=new Array();
							k=0;
							for(i=0;i<fields.length;i++){
								
								if(document.querySelectorAll(ground+' [name="'+fields[i]+'"]').length){
									
									for(j=0;j < document.querySelectorAll(ground+' [name="'+fields[i]+'"]').length;j++){
										
										if(document.querySelectorAll(ground+' [name="'+fields[i]+'"]')[j].disabled==false){
											
											//We have to add the condition for checkbox.
											if(document.querySelectorAll(ground+' [name="'+fields[i]+'"]')[j].type=="checkbox")
											{
											  if(document.querySelectorAll(ground+' [name="'+fields[i]+'"]')[j].checked)
												  val=document.querySelectorAll(' [name="'+fields[i]+'"]')[j].value;
											  else
												  val=0;
											}
											else{ //For other input type.
											val=document.querySelectorAll(ground+' [name="'+fields[i]+'"]')[j].value;
											    if(fields[i]=="prod" || fields[i]=="cat")
													val=val.split(","); //Should go as an array.
                                            }
											data[k]={"name":fields[i],"value":val};
											k++;
											break;
										}
										
									}
								}
							}
													
			                 //eo[0].post.form='[{"name":"c_id","value":"0"},{"name":"firstname","value":"Test","required":true,"custom":false},{"name":"lastname","value":"Test","required":true,"custom":false},{"name":"emailaddress","value":"testing1234@gmail.com","required":true,"custom":false},{"name":"officephone","value":"test","required":true,"custom":false},{"name":"mobilephone","value":"123","required":false,"custom":false},{"name":"faxnumber","value":"1234567","required":false,"custom":false},{"name":"language1","value":"19","required":true,"custom":false},{"name":"language2","value":"24","required":false,"custom":false},{"name":"language3","value":"29","required":false,"custom":false},{"name":"optinglobal","value":true,"required":false,"custom":false},{"name":"optinincident","value":true,"required":false,"custom":false},{"name":"optincisurvey","value":true,"required":false,"custom":false},{"name":"country","value":"491","required":true,"custom":false},{"name":"ek_phone_extension","value":"test","required":false,"custom":false},{"name":"orig_submit_id","value":11403,"table":"incidents","required":false,"prev":"11403","custom":true,"customID":219,"customType":5},{"name":"orig_submit_name","value":"Loretta Borovitcky","table":"incidents","required":false,"prev":"Loretta Borovitcky","custom":true,"customID":220,"customType":8},{"name":"secondarycontact","value":true,"required":false,"custom":false},{"name":"disabled","value":true,"required":false,"custom":false},{"name":"ek_ext_ref_no","value":"123","required":false,"custom":false},{"data_type":"categories","hm_type":14,"linking_on":1,"linkingProduct":0,"table":"incidents","name":"cat","level":1,"value":[6487,6488],"label":"Error Message","reset":false,"cache":[]},{"data_type":"products","hm_type":13,"linking_on":1,"linkingProduct":0,"table":"incidents","name":"prod","value":[1481,2232,2947],"cache":[]},{"name":"ek_severity","value":"54","required":true,"custom":false},{"name":"ek_repeatability","value":"51","required":true,"custom":false},{"name":"ek_error_code","value":"123","required":false,"custom":false},{"name":"ek_remote_access_perm","value":true,"table":"incidents","required":false,"prev":"0","custom":true,"customID":211,"customType":12},{"name":"thread","value":"Test","required":true,"custom":false},{"name":"ek_enabling_partner","value":null},{"name":"ek_mvs_manfacturer","value":""},{"name":"ek_service_dist","value":""},{"name":"ek_service_reseller","value":""},{"name":"ek_corporate","value":""},{"name":"ek_type","value":"Repair"},{"name":"ek_sds","value":"Blank"},{"name":"ek_k_number","value":"7121236"},{"name":"ek_serial_number","value":"1493-FC"},{"name":"ek_service_profile","value":null},{"name":"ek_response_profile","value":null},{"name":"ek_equip_component_id","value":"3925996"},{"name":"ek_sap_product_id","value":"KCS25005C11K208"},{"name":"ek_sap_soldto_custid","value":"741387"},{"name":"ek_customer_sapid","value":"741387"},{"name":"ek_remote_eos","value":"00000000"},{"name":"ek_onsite_eos","value":"00000000"},{"name":"panel","value":"repairrequest"},{"name":"sesslang","value":""}]';							 
							 form_Data=JSON.stringify(data);
							 eo[0].post.form=form_Data;
							 
						
							
							
					}


        }, this);


    },
	
	 _waitPanel: function (val) {
		var loadingmessage=this.data.js.loadingmessage;
		YUI().use('panel', 'dd-plugin', function(Y) { 

						var wait_panel = new Y.Panel({
							srcNode      : '#panelContenthi09989988',
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

    _createWaitPanel: function () {


        this._waitPanel =


                    new YAHOO.widget.Panel("wait",


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


    }
	
});