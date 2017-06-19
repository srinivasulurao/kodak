RightNow.namespace('Custom.Widgets.CIHFunction.ManageContacts');
Custom.Widgets.CIHFunction.ManageContacts = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {


    this._eo = new RightNow.Event.EventObject();


    this._form_name = "rn_" + this.instanceID + "_form";
	 
	this._searchbyPanel=this.data.attrs.panel;


    this._form = document.getElementById(this._form_name);


    this._loginField;


    this._roleField;


    this._disabledField;


    this._emailAddress;
	
	this.error_field_exist=false;





    //this._inputList = this._form.getElementsByTagName('input');
	
	this._inputList=document.querySelectorAll("#"+this._form_name+" input");


    //this._selectList = this._form.getElementsByTagName('select');
	
	this._selectList=document.querySelectorAll("#"+this._form_name+" select");





    for (i = 0; i < this._inputList.length; i++) {


        if (this._inputList[i].name == "login") {


            this._loginField = this._inputList[i];


        }


        if (this._inputList[i].name == "emailaddress") {


            this._emailAddress = this._inputList[i];


        }


        if (this._inputList[i].type == 'checkbox' && this._inputList[i].name == 'disabled') {


            this._disabledField = this._inputList[i];


        }


    }





    for (ii = 0; ii < this._selectList.length; ii++) {

        if (this._selectList[ii].name == "role") {


            this._roleField = this._selectList[ii];


        }


    }

if(this._roleField!=null)    
    this.Y.one("#"+this._roleField.id).on("change", this._checkLoginRoleRequired,this);

if(this._loginField!=null)
    this.Y.one("#"+this._loginField.id).on("blur", this._checkLoginRoleRequired,this);

if(this._disabledField!=null)
    this.Y.one("#"+this._disabledField.id).on("click", this._checkDisabled,this);


    RightNow.Event.subscribe('evt_selectedOrgToManage', this._setSelectedOrg, this);
	RightNow.Event.subscribe('evt_manageContactSelectChanged', this._contactSelectChanged, this); //Added by Srini for dynamic contact detail change.
	RightNow.Event.subscribe('evt_formErrorExist',this.evt_formErrorExist,this); 
	RightNow.Event.subscribe('evt_formSubmissionSuccess',this._formSubmittedSuccess,this); //Added by Srini for dynamic contact list change.
	
    this._loginRequiredLabel = document.getElementById("rn_" + this.instanceID + "_loginRequiredIndicator");


    this._roleRequiredLabel = document.getElementById("rn_" + this.instanceID + "_roleRequiredIndicator");





    this._contactForm = document.getElementById("rn_" + this.instanceID + "_contactForm");


    this._ppErrorMessage = document.getElementById("rn_" + this.instanceID + "_ppErrorMessage");


    this._eo.data.form = this._form_name;




if(this._form !=null){

    if (this._form.isDisabled != true) {


        this._disableEvents();


        this._enableEvents();


    }

}



    RightNow.Event.subscribe('evt_managePanel', this._onManagePanel, this);

    },

    _enableEvents: function (evt, args) {


        RightNow.Event.subscribe("evt_contactSelectChanged", this._contactSelectChanged, this);


        RightNow.Event.subscribe("evt_resetForm", this._resetForm, this);


    },



   _formSubmittedSuccess: function(evt,args){
	   
	  var eoSite = new RightNow.Event.EventObject();

              eoSite.w_id = this.instanceID;

              eoSite.data.orgID = document.querySelectorAll("[name='selectedOrg']")[0].value;

              RightNow.Event.fire('evt_changeSite', eoSite);
			  
			  var empty_contact='{"status":1,"firstname":"","lastname":"","emailaddress":"","officephone":"","mobilephone":"","homephone":"","faxnumber":"","language1":"","language2":"","language3":"","optinglobal":"","optinincident":"","optincisurvey":"","country":"","disabled":"","deactivate":"","ek_phone_extension":"","role":"","login":"","pperrormessage":null}';
			  empty_contact=JSON.parse(empty_contact);
			  this._ajaxResponse(empty_contact);  
			  
   },

    _disableEvents: function (evt, args) {


        RightNow.Event.unsubscribe("evt_contactSelectChanged", this._contactSelectChanged);


        RightNow.Event.unsubscribe("evt_resetForm", this._resetForm);


    },





    _checkDisabled: function (evt, args) {


        if (this._disabledField.checked == true) {


            this._loginField.value = "";


            this._roleField.selectedIndex = 0;


            this._checkLoginRoleRequired();


        }


        else {


            this._loginField.value = this._emailAddress.value;


            this._checkLoginRoleRequired();


        }


    },





    _checkLoginRoleRequired: function () {


        if (this._disabledField.checked == false)


            this._loginField.value = this._emailAddress.value;


        if (this._loginField.value.length > 0 || (this._roleField!=null && this._roleField.options[this._roleField.selectedIndex].value) > 0) {


            this._toggleRequired(true);


            this._setLoginAndRoleRequired(true);


        }


        else {


            this._toggleRequired(false);


            this._setLoginAndRoleRequired(false);





        }


    },





    _setSelectedOrg: function (evt, args) {

        var eor = new RightNow.Event.EventObject();


        eor.data.name = 'selectedOrg';


        eor.data.value = args[0].data.selectedOrg;
		


        RightNow.Event.fire("evt_setHiddenField", eor);


    },
    _setLoginAndRoleRequired: function (required) {

        var login_field_id=(this._loginField!=null)?this._loginField.id:"xyz";
        var role_field_id=(this._roleField!=null)?this._roleField.id:"xyz";

        this._eo.data.toggleRequired = {


            "fields": [login_field_id, role_field_id],


            "value": required


        };


        RightNow.Event.fire("evt_toggleRequired", this._eo);


    },

    _contactSelectChanged: function (evt, args) {
		
         //console.log(document.querySelectorAll("#panelManageContacts2 [name='c_id']")[0].value);
         if (this._form_name !== args[0].data.form)

             return;

        this._requestInProgress = true;


        var c_id = args[0].filters.data;		

        if (c_id > 0) {


            var postData = {};


            postData['c_id'] = c_id;


            this._overrideAjaxMethod();


            this._waitPanel('show');


            RightNow.Ajax.makeRequest("/cc/contact_custom/contact_get", postData, {


                data: { eventName: "evt_contactRetrieveResponse" },


                successHandler: function (response) {
					

                    var responseText=response.responseText.replace('<rn:meta title="" template="kodak_b2b_template.php" />',"");
                    var resp = RightNow.JSON.parse(responseText);


                    this._ajaxResponse(resp);


                    this._waitPanel('hide');


                    this._requestInProgress = false;


                    this._unsubscribeOverrideAjaxMethod();


                },


                failuerHandler: function (response) {


                    this._waitPanel('hide');


                    this._requestInProgress = false;


                },


                scope: this


            });





        }
		
        else {
            if(parseInt(c_id)==0){
				var empty_contact='{"status":1,"firstname":"","lastname":"","emailaddress":"","officephone":"","mobilephone":"","homephone":"","faxnumber":"","language1":"","language2":"","language3":"","optinglobal":"","optinincident":"","optincisurvey":"","country":"","disabled":"","deactivate":"","ek_phone_extension":"","role":"","login":"","pperrormessage":null}';
				empty_contact=JSON.parse(empty_contact);
				this._ajaxResponse(empty_contact);
		    }
		
            this._toggleRequired(false);

            this._setLoginAndRoleRequired(false);


        }


    },





    _onManagePanel: function (evt, args) {


        var hideList, showList;

        hideList = args[0].data.hidelist;
        showList = args[0].data.showlist;

        if (hideList != undefined) {


            for (i = 0; i < hideList.length; i++) {


                if (hideList[i] == this.data.attrs.panel_name) {


                    this._disableEvents(evt, args);


                }


            }


        }





        if (showList != undefined) {


            for (i = 0; i < showList.length; i++) {


                if (showList[i] == this.data.attrs.panel_name) {


                    this._disableEvents(evt, args);


                    this._enableEvents(evt, args);


                    RightNow.Event.fire("evt_resetForm", this._eo);


                }


            }


        }








    },





    _toggleRequired: function (args) {


        if (args == true) {


            this.Y.one("#"+this._loginRequiredLabel.id).removeClass("rn_Hidden");


            this.Y.one("#"+this._roleRequiredLabel.id).removeClass("rn_Hidden");


        }


        else {


            this.Y.one("#"+this._loginRequiredLabel.id).addClass("rn_Hidden");


            this.Y.one("#"+this._roleRequiredLabel.id).addClass("rn_Hidden");


        }





    },





    _resetForm: function () {


        this._ppErrorMessage.innerHTML = "";


        this.Y.one("#"+this._ppErrorMessage.id).addClass("rn_Hidden");


    },





    _disablePanel: function (control) {





        try {


            el.disabled = el.disabled ? false : true;


        }


        catch (E) {


        }


        if (el.childNodes && el.childNodes.length > 0) {


            for (var x = 0; x < el.childNodes.length; x++) {


                this._disablePanel(el.childNodes[x]);


            }


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


    _overrideAjaxMethod: function () {





        this._unsubscribeOverrideAjaxMethod();

        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
			
			
			
			

           if(eo[0].hasOwnProperty('data')){
				if (eo[0].data.eventName == "evt_contactRetrieveResponse") {


					eo[0].url = '/cc/contact_custom/contact_get';


				}

		   }
		   
		   
		   if(eo[0].url=="/cc/contact_custom/contact_update_submit"){
			   
							var fields=new Array('c_id','firstname','officephone','language1','optinglobal','country','rn_ListFailedOrgContacts','lastname','mobilephone','language2','optinincident','ek_phone_extension','emailaddress','faxnumber','language3','optincisurvey','role','disabled','login','selectedOrg','communication_optin_list','sesslang');
							
							var data=new Array();
							
							var ground=(this.data.attrs.panel_name=="accordionManageContacts2")?"#panelManageContacts2":"#panelManageContacts"
							
							for(i=0;i<fields.length;i++){
											
								if(fields[i]=='communication_optin_list'){
									communication_option_value=new Array();
									cov=0;
									for(c=0;c<document.querySelectorAll(ground+" #rn_commoptin_CheckBox").length;c++){
										if(document.querySelectorAll(ground+" #rn_commoptin_CheckBox")[c].checked){
										   communication_option_value[cov]="A"+document.querySelectorAll(" #rn_commoptin_CheckBox")[c].value;
										   cov++;
										}
									}
									
									val=communication_option_value.join(",");
								}
								else{
									if(document.querySelectorAll(ground+' [name="'+fields[i]+'"]')[0].type=="checkbox")
										{
										  if(document.querySelectorAll(ground+' [name="'+fields[i]+'"]')[0].checked)
										      val=document.querySelectorAll(ground+' [name="'+fields[i]+'"]')[0].value;
									      else
											  val=0;
										}
								    else
									    val=document.querySelectorAll(ground+' [name="'+fields[i]+'"]')[0].value;
							    }
								
								data[i]={"name":fields[i],"value":val};
							}
							//console.log(data);
						    form_Data=JSON.stringify(data); //JSON string.
							
							 //eo[0].post.form='[{"name":"c_id","value":"51589"},{"name":"firstname","value":"Cedric","required":true,"custom":false},{"name":"lastname","value":"Walsh","required":true,"custom":false},{"name":"emailaddress","value":"rahul.chanda@oracle.com","required":true,"custom":false},{"name":"officephone","value":"901 523 1561 ext 147","required":true,"custom":false},{"name":"mobilephone","value":"901 791 1191","required":false,"custom":false},{"name":"faxnumber","value":"","required":false,"custom":false},{"name":"language1","value":"19","required":true,"custom":false},{"name":"language2","value":"","required":false,"custom":false},{"name":"language3","value":"","required":false,"custom":false},{"name":"optinglobal","value":false,"required":false,"custom":false},{"name":"optinincident","value":true,"required":false,"custom":false},{"name":"optincisurvey","value":true,"required":false,"custom":false},{"name":"country","value":"246","required":true,"custom":false},{"name":"selectedOrg","value":405606},{"name":"ek_phone_extension","value":"","required":false,"custom":false},{"name":"disabled","value":false,"required":false,"custom":false},{"name":"login","value":"jbuescher@memphisdailynews.com.invalid","required":true,"custom":false},{"name":"role","value":"2","custom":false},{"name":"communication_optin_list","value":""},{"name":"sesslang","value":""}]';
							 eo[0].post.form=form_Data;
							 
					}
		   


        }, this);
		
		
		
		RightNow.Event.subscribe('on_after_ajax_request', function (evt, eo) {
			
			 if(eo[0].url=="/cc/contact_custom/contact_update_submit"){
				 alert("Hello");
			 }
			
			
		},this);


    },





    _unsubscribeOverrideAjaxMethod: function () {





        RightNow.Event.unsubscribe('on_before_ajax_request', function (evt, eo) {


            if (eo[0].data.eventName == "evt_contactRetrieveResponse") {


                eo[0].url = '/cc/contact_custom/contact_get';


            }


        });


    },





    _processValues: function (result) {
	

        this._setValues('firstname', result.firstname);


        this._setValues('lastname', result.lastname);


        this._setValues('emailaddress', result.emailaddress);


        this._setValues('officephone', result.officephone);


        this._setValues('mobilephone', result.mobilephone);


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


        this._setValues('disabled', result.disabled);


        this._setValues('deactivate', result.deactivate);





        if (result.role)


            this._setValues('role', result.role);





        if (result.login)


            this._setValues('login', result.login);





        //2012.0918 scott harris: causing error if (result.pperrormessage) {


        if (typeof result.pperrormessage != 'undefined') {


            if (result.pperrormessage != null) {


                this._ppErrorMessage.innerHTML = result.pperrormessage;


                YAHOO.util.Dom.removeClass(this._ppErrorMessage, "rn_Hidden");


            }


            else {


                this._ppErrorMessage.innerHTML = "";


                this.Y.one("#"+this._ppErrorMessage.id).addClass("rn_Hidden");


            }


        }


        else {


            this._ppErrorMessage.innerHTML = "";


            YAHOO.util.Dom.addClass(this._ppErrorMessage, "rn_Hidden");


        }





        //if (result.country)
            this._setValues('country', result.country);
           

        //if (result.ek_phone_extension)
            this._setValues('ek_phone_extension', result.ek_phone_extension);




        this._checkLoginRoleRequired();


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


            } //end if


        }





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

    _waitPanel: function (val) {
    var loadingmessage=this.data.js.loadingmessage;
    YUI().use('panel', 'dd-plugin', function(Y) { 

                    var wait_panel = new Y.Panel({
                        srcNode      : '#panelContent_321321',
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

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});