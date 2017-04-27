RightNow.namespace('Custom.Widgets.CIHFunction.IBaseUpdate');
Custom.Widgets.CIHFunction.IBaseUpdate = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
     constructor: function() {
		
		//this._waitPanel = null;
		this._eo = new RightNow.Event.EventObject();
		this._form_name = "rn_" + this.instanceID + "_form";
		this._form = document.getElementById("rn_" + this.instanceID + "_form");
		this._parentForm = RightNow.UI.findParentForm("rn_" + this.instanceID + "_form");
		this._contactForm = document.getElementById("rn_" + this.instanceID + "_contactForm");
		this._createContactContainer = document.getElementById("rn_" + this.instanceID + "_createContact");
		this._createContactButton = document.getElementById("rn_" + this.instanceID + "_createContactButton");
		this._ibaseUpdateType = document.getElementsByName("ek_ibase_updt_type")[this.data.attrs.widget_index];


		this._equipmentRelocationForm = document.getElementById("form_EquipmenRelocationForm" + this.instanceID);
		this._equipmentRemovalForm = document.getElementById("form_EquipmenRemovalForm" + this.instanceID);
		this._entitlementChangeForm = document.getElementById("form_EntitlementChangeForm" + this.instanceID);
		this._iBaseUpdateOtherForm = document.getElementById("form_IBaseUpdateOtherForm" + this.instanceID);
		this._eo.data.form = this._form_name;

		this._scrollTarg = document.getElementById("scroll_target");
		
		
		if(this._ibaseUpdateType != null)
		  this.Y.one("#"+this._ibaseUpdateType.id).on("change", this._onIbaseTypeChange,this);
		
		if(this._createContactButton !=null)
		 this.Y.one("#"+this._createContactButton.id).on("click", this._showHideContactForm,this,true);

		RightNow.Event.subscribe('evt_populateIbaseUpdateData', this._populateIbaseUpdateData, this);
		
		RightNow.Event.subscribe('evt_ibaseContactSelectChanged', this._contactSelectChanged, this); //Added by Srini for dynamic contact detail change.

		RightNow.Event.subscribe('evt_managePanel', this._onManagePanel, this);

		if (this._form.isDisabled != true) {
			this._disableEvents();
			this._enableEvents();
		}

    },
  
    _enableEvents: function (evt, args) {
 

        RightNow.Event.subscribe("evt_contactSelectChanged", this._contactSelectChanged, this);


        RightNow.Event.subscribe("evt_resetForm", this._resetForm, this);





    },

    _disableEvents: function (evt, args) {


        RightNow.Event.unsubscribe("evt_contactSelectChanged", this._contactSelectChanged);


        RightNow.Event.unsubscribe("evt_resetForm", this._resetForm);


    },

    _onIbaseTypeChange: function (evt, args) {


        var selectedValue = this._ibaseUpdateType.options[this._ibaseUpdateType.selectedIndex].value





        switch (selectedValue) {





            case "460": //Equipment Removal


                this._showIBaseForm(this._equipmentRemovalForm);


                break;


            case "461": //Equipment Relocation


                this._showIBaseForm(this._equipmentRelocationForm);


                break;


            case "462": //Entitlement Change


                this._showIBaseForm(this._entitlementChangeForm);


                break;


            case "463": //IBase Update Other


                this._showIBaseForm(this._iBaseUpdateOtherForm);


                break;


            default:


                this._hideAllIBaseForms();


                break;





        }


    },

    _onManagePanel: function (evt, args) {





        var hideList, showList;


        hideList = args[0].data.hidelist;


        showList = args[0].data.showlist;





        if (showList != undefined) {


            for (i = 0; i < showList.length; i++) {


                if (showList[i] == this.data.attrs.panel_name) {





                    //2012.12.07 scott harris: to scroll to display form


                    //var yval = YAHOO.util.Dom.getY(this._scrollTarg);
					var yval = this.Y.one(this._scrollTarg).getY();


                    window.scrollTo(0,yval);





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
	
    _showIBaseForm: function (ibaseform) {

        this._hideAllIBaseForms();


        this._toggleFormElements(ibaseform, false);


        this.Y.one("#"+ibaseform.id).removeClass("rn_Hidden");


    },

    _hideAllIBaseForms: function () {



        if (this.Y.all("#"+this._equipmentRelocationForm.id).addClass("rn_Hidden"));

        {


            this._toggleFormElements(this._equipmentRelocationForm, true);


            this.Y.all("#"+this._equipmentRelocationForm.id).addClass("rn_Hidden");


        }


        if (!this.Y.all("#"+this._equipmentRemovalForm.id).addClass("rn_Hidden"));


        {


            this._toggleFormElements(this._equipmentRemovalForm, true);


            this.Y.all("#"+this._equipmentRemovalForm.id).addClass("rn_Hidden");


        }


        if (!this.Y.all("#"+this._entitlementChangeForm.id).addClass("rn_Hidden"));


        {


            this._toggleFormElements(this._entitlementChangeForm, true);


            this.Y.all("#"+this._entitlementChangeForm.id).addClass("rn_Hidden");


        }





        if (!this.Y.all("#"+this._iBaseUpdateOtherForm.id).addClass("rn_Hidden"));


        {


            this._toggleFormElements(this._iBaseUpdateOtherForm, true);


            this.Y.all("#"+this._iBaseUpdateOtherForm.id).addClass("rn_Hidden");


        }


    },

    _toggleFormElements: function (container, isDisabled) {


        var tagNames = ["INPUT", "SELECT", "TEXTAREA"];


        for (var i = 0; i < tagNames.length; i++) {


            var elems = container.getElementsByTagName(tagNames[i]);


            for (var j = 0; j < elems.length; j++) {


                elems[j].disabled = isDisabled;


            }


        }


    },

    _contactSelectChanged: function (evt, args) {


        if (this._form.id != args[0].data.form)


            return;


        var c_id = args[0].filters.data;


        this._disablePanel(this._contactForm, false);


        if (c_id > 0) {


            this._disablePanel(this._contactForm, true);


            var postData = {};


            postData['c_id'] = c_id;


            if (evt == 'evt_contactSelectChanged')


                this._overrideAjaxMethod('evt_cih_ibase_customer');            


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


                    this._waitPanel('hide');


                },


                scope: this


            });





        }





    },

    _resetForm: function () {


        this._hideAllIBaseForms();


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





        if (result.mobilephone)


            this._setValues('mobilephone', result.mobilephone);





        if (result.faxnumber)


            this._setValues('faxnumber', result.faxnumber);





        if (result.language1)


            this._setValues('language1', result.language1);





        if (result.language2)


            this._setValues('language2', result.language2);





        if (result.language3)


            this._setValues('language3', result.language3);





        this._setValues('optinglobal', result.optinglobal);


        this._setValues('optinincident', result.optinincident);


        this._setValues('optincisurvey', result.optincisurvey);








        if (result.country)


            this._setValues('country', result.country);


        if (result.ek_phone_extension)


            this._setValues('ek_phone_extension', result.ek_phone_extension);






    },

    _setValues: function (name, value) {





        for (var obj in this._form) {





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


                        if (this._form[obj].name == name && value != "") {


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


            } //end if





        }





    },

    _populateIbaseUpdateData: function (evt, args) { 

        //var sds = args[0].data.sds;

        //fire event to set value

        var eor = new RightNow.Event.EventObject();

        eor.data.name = 'ek_k_number';

        eor.data.value = args[0].data.knum.value;

        RightNow.Event.fire("evt_setHiddenField", eor);

        var eor = new RightNow.Event.EventObject();

        eor.data.name = 'ek_serial_number';

        eor.data.value = args[0].data.sn.value;

        RightNow.Event.fire("evt_setHiddenField", eor);

        var eor = new RightNow.Event.EventObject();

        eor.data.name = 'ek_equip_component_id';

        eor.data.value = args[0].data.equip_id.value;

        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();

        eor.data.name = 'default_product_array';

        eor.data.value = args[0].data.ibase_product_hier.value;

        RightNow.Event.fire('evt_setProductSelection', eor);

        eor = new RightNow.Event.EventObject();

        eor.data.name = 'ek_sap_product_id';

        eor.data.value = args[0].data.sap_prod_id.value;

        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();

        eor.data.name = 'ek_sap_soldto_custid';

        eor.data.value = args[0].data.sold_to.value;

        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();

        eor.data.name = 'ek_enabling_partner';

        eor.data.value = (args[0].data.enablingPartner!=null)?args[0].data.enablingPartner.value:"";
		
        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();

        eor.data.name = 'ek_mvs_manfacturer';

        eor.data.value = args[0].data.mfgPartner.value;
		
        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();

        eor.data.name = 'ek_service_dist';
		
        eor.data.value = args[0].data.distrPartner.value;

        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();

        eor.data.name = 'ek_service_reseller';

        eor.data.value = args[0].data.resellPartner.value;

        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();

        eor.data.name = 'ek_corporate';

        eor.data.value = (args[0].data.hasOwnProperty('corporatePartner'))?args[0].data.corporatePartner.value:"";

        RightNow.Event.fire("evt_setHiddenField", eor);

    },
	_waitPanel: function (val) {
			var loadingmessage=this.data.js.loadingmessage;
			YUI().use('panel', 'dd-plugin', function(Y) { 

							var wait_panel = new Y.Panel({
								srcNode      : '#panelContentIbaaseUpdate',
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
    _overrideAjaxMethod: function (str) {

		RightNow.Event.subscribe('on_before_ajax_request', function (str, eo) {

                       				
           if(eo[0].hasOwnProperty('data')){
				if (eo[0].data.eventName == "evt_contactRetrieveResponse") {


						eo[0].url = '/cc/contact_custom/contact_get';


						}
		   }
		  	   
		   //This will update/Create the whole Ibase Data
		   if(eo[0].url=="/cc/incident_custom/incident_submit"){
						
			                //All Expected values here. 							
							var fields=new Array('c_id','firstname','officephone','language1','optinglobal','country','rn_ListFailedOrgContacts','lastname','mobilephone','language2','optinincident','ek_phone_extension','emailaddress','faxnumber','language3','optincisurvey','role','disabled','login',"orig_submit_id","orig_submit_name","secondarycontact","ek_ibase_updt_type","products","removal_reason","effective_date","thread","ibase_country","entitlement_type","panel","sesslang","ek_type","ek_enabling_partner","ek_mvs_manfacturer","ek_service_dist","ek_service_reseller","ek_corporate","ek_k_number","ek_serial_number","ek_equip_component_id","ek_sap_product_id","ek_sap_soldto_custid","equipment_location","storenumber","zipcode","state","city","street","ibase_address","sitecustomername","ibase_phone","ibase_lastname","ibase_firstname","product_identifier");
							
							var ground=(this.data.attrs.panel_name=="accordionIbaseUpdate2")?"#panelIbaseUpdate2":"#panelIbaseUpdate"
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
											else //For other input type.
											val=document.querySelectorAll(ground+' [name="'+fields[i]+'"]')[j].value;
										
											data[k]={"name":fields[i],"value":val};
											k++;
											break;
										}
										
									}
								}
							}
													
							 //eo[0].post.form='[{"name":"c_id","value":"51601"},{"name":"","value":"Brian","required":true,"custom":false},{"name":"","value":"Haskell","required":true,"custom":false},{"name":"","value":"brian.haskell@qg.com.invalid","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"language1","value":"19","required":true,"custom":false},{"name":"language2","value":"","required":false,"custom":false},{"name":"language3","value":"","required":false,"custom":false},{"name":"optinglobal","value":false,"required":false,"custom":false},{"name":"optinincident","value":true,"required":false,"custom":false},{"name":"optincisurvey","value":true,"required":false,"custom":false},{"name":"country","value":"246","required":true,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"orig_submit_id","value":11403,"table":"incidents","required":true,"prev":"11403","custom":true,"customID":219,"customType":5},{"name":"orig_submit_name","value":"Loretta Borovitcky","table":"incidents","required":true,"prev":"Loretta Borovitcky","custom":true,"customID":220,"customType":8},{"name":"secondarycontact","value":true,"required":false,"custom":false},{"name":"disabled","value":true,"required":false,"custom":false},{"name":"ek_ibase_updt_type","value":"460","required":true,"custom":false},{"data_type":"products","hm_type":13,"linking_on":1,"linkingProduct":0,"table":"incidents","name":"prod","value":[2754,2431,9484],"cache":[]},{"name":"removal_reason","value":"Can\'t locate equipment","required":true,"custom":false},{"name":"effective_date","value":"Test","required":true,"custom":false},{"name":"thread","value":"test","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"ibase_country","value":"--","required":true,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"entitlement_type","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"panel","value":"ibaseupdate"},{"name":"sesslang","value":""},{"name":"ek_type","value":"Administrative"},{"name":"ek_enabling_partner","value":null},{"name":"ek_mvs_manfacturer","value":""},{"name":"ek_service_dist","value":""},{"name":"ek_service_reseller","value":""},{"name":"ek_corporate","value":""},{"name":"ek_k_number","value":"0000050B"},{"name":"ek_serial_number","value":"50"},{"name":"ek_equip_component_id","value":"1924285"},{"name":"ek_sap_product_id","value":"COI0191382"},{"name":"ek_sap_soldto_custid","value":"599428"}]';				 
							 //eo[0].post.form='[{"name":"c_id","value":"0"},{"name":"firstname","value":"Test2","required":true,"custom":false},{"name":"lastname","value":"Test2","required":true,"custom":false},{"name":"emailaddress","value":"test2@gmail.com","required":true,"custom":false},{"name":"officephone","value":"777-777-8888","required":true,"custom":false},{"name":"mobilephone","value":"901 791 1191","required":false,"custom":false},{"name":"faxnumber","value":"333333","required":false,"custom":false},{"name":"language1","value":"19","required":true,"custom":false},{"name":"language2","value":"25","required":false,"custom":false},{"name":"language3","value":"31","required":false,"custom":false},{"name":"optinglobal","value":true,"required":false,"custom":false},{"name":"optinincident","value":true,"required":false,"custom":false},{"name":"optincisurvey","value":true,"required":false,"custom":false},{"name":"country","value":"491","required":true,"custom":false},{"name":"ek_phone_extension","value":"+91","required":false,"custom":false},{"name":"orig_submit_id","value":11403,"table":"incidents","required":true,"prev":"11403","custom":true,"customID":219,"customType":5},{"name":"orig_submit_name","value":"Loretta Borovitcky","table":"incidents","required":true,"prev":"Loretta Borovitcky","custom":true,"customID":220,"customType":8},{"name":"secondarycontact","value":true,"required":false,"custom":false},{"name":"disabled","value":true,"required":false,"custom":false},{"name":"ek_ibase_updt_type","value":"460","required":true,"custom":false},{"data_type":"products","hm_type":13,"linking_on":1,"linkingProduct":0,"table":"incidents","name":"prod","value":[1481,2232,2947],"cache":[]},{"name":"removal_reason","value":"Can't locate equipment","required":true,"custom":false},{"name":"effective_date","value":"Test","required":true,"custom":false},{"name":"thread","value":"Test","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"ibase_country","value":"--","required":true,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"entitlement_type","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"panel","value":"ibaseupdate"},{"name":"sesslang","value":""},{"name":"ek_type","value":"Administrative"},{"name":"ek_enabling_partner","value":null},{"name":"ek_mvs_manfacturer","value":""},{"name":"ek_service_dist","value":""},{"name":"ek_service_reseller","value":""},{"name":"ek_corporate","value":""},{"name":"ek_k_number","value":"7121236"},{"name":"ek_serial_number","value":"1493-FC"},{"name":"ek_equip_component_id","value":"3925996"},{"name":"ek_sap_product_id","value":"KCS25005C11K208"},{"name":"ek_sap_soldto_custid","value":"741387"}]';
							 form_Data=JSON.stringify(data);
							 eo[0].post.form=form_Data;
							 
						
							
							
					}
		   
		   

         },this);

    }



});
