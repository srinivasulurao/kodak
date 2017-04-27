RightNow.namespace('Custom.Widgets.CIHFunction.AjaxFormSubmit');
Custom.Widgets.CIHFunction.AjaxFormSubmit = RightNow.Widgets.FormSubmit.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.FormSubmit#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();

			this._requestInProgress =	 false;
			this._panelTrigger = false;
			this._challengeDivID = this.data.attrs.challenge_location;
            this._statusMessage = this.Y.one(this.baseSelector + "_StatusMessage");
			this._parentForm = RightNow.UI.findParentForm(this.baseDomID);

			this._eo = new RightNow.Event.EventObject(this, {
				w_id: this.instanceID,
				data: {
					"form": this._parentForm,
					"error_location": this._errorMessageDiv.id,
					"f_tok": this.data.js.f_tok}
			});

			if (this._formSubmitFlag.disabled == false) {
				this._toggleClickListener(true);
			}
			
			this.overrideFormSubmit();
			
        },
		
		overrideFormSubmit:function(){
			
			
				RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
				
					if(eo[0].url=="/cc/contact_custom/contact_update_submit"){
						
						  //we have to do the validation as well here.
							var fields=new Array('c_id','firstname','officephone','language1','optinglobal','country','rn_ListFailedOrgContacts','lastname','mobilephone','language2','optinincident','ek_phone_extension','emailaddress','faxnumber','language3','optincisurvey','role','disabled','login');
							
							var data=new Array();
							
							for(i=0;i<fields.length;i++){
								
								val=document.querySelectorAll('#panelManageContacts2 [name="'+fields[i]+'"]')[0].value;
                                data[i]='{"name":"'+fields[i]+'","value":"'+val+'"}';
							}
							
							form_Data="["+data.join(",")+"]";
							
							 //eo[0].post.form='[{"name":"c_id","value":"51589"},{"name":"firstname","value":"Cedric","required":true,"custom":false},{"name":"lastname","value":"Walsh","required":true,"custom":false},{"name":"emailaddress","value":"rahul.chanda@oracle.com","required":true,"custom":false},{"name":"officephone","value":"901 523 1561 ext 147","required":true,"custom":false},{"name":"mobilephone","value":"901 791 1191","required":false,"custom":false},{"name":"faxnumber","value":"","required":false,"custom":false},{"name":"language1","value":"19","required":true,"custom":false},{"name":"language2","value":"","required":false,"custom":false},{"name":"language3","value":"","required":false,"custom":false},{"name":"optinglobal","value":false,"required":false,"custom":false},{"name":"optinincident","value":true,"required":false,"custom":false},{"name":"optincisurvey","value":true,"required":false,"custom":false},{"name":"country","value":"246","required":true,"custom":false},{"name":"selectedOrg","value":405606},{"name":"ek_phone_extension","value":"","required":false,"custom":false},{"name":"disabled","value":false,"required":false,"custom":false},{"name":"login","value":"jbuescher@memphisdailynews.com.invalid","required":true,"custom":false},{"name":"role","value":"2","custom":false},{"name":"communication_optin_list","value":""},{"name":"sesslang","value":""}]';
							 eo[0].post.form=form_Data;
							 
							//console.log(eo[0].post.form);
					}
					
					
					if(eo[0].url=="/cc/incident_custom/incident_submit"){
						
			                 							
							var fields=new Array('c_id','firstname','officephone','language1','optinglobal','country','rn_ListFailedOrgContacts','lastname','mobilephone','language2','optinincident','ek_phone_extension','emailaddress','faxnumber','language3','optincisurvey','role','disabled','login',"orig_submit_id","orig_submit_name","secondarycontact","ek_ibase_updt_type","products","removal_reason","effective_date","thread","ibase_country","entitlement_type","ibaseupdate","ek_type","ek_enabling_partner","ek_mvs_manfacturer","ek_service_dist","ek_service_reseller","ek_corporate","ek_k_number","ek_serial_number","ek_equip_component_id","ek_sap_product_id","ek_sap_soldto_custid","equipment_location","storenumber","zipcode","state","city","ibase_address","sitecustomername","ibase_phone","ibase_lastname","ibase_firstname","product_identifier");
							
							var data=new Array();
							
							var i=0;
							var j=0;
							while(i<fields.length){
								
								if(document.querySelectorAll('#panelIbaseUpdate2 [name="'+fields[i]+'"]').length){
									val=document.querySelectorAll('#panelIbaseUpdate2 [name="'+fields[i]+'"]')[0].value;
									data[j]='{"name":"'+fields[i]+'","value":"'+val+'"}';
									j++;
								}
								i++;
							}
							
							
							
							//console.log(form_Data);
							
							 //eo[0].post.form='[{"name":"c_id","value":"51601"},{"name":"","value":"Brian","required":true,"custom":false},{"name":"","value":"Haskell","required":true,"custom":false},{"name":"","value":"brian.haskell@qg.com.invalid","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"language1","value":"19","required":true,"custom":false},{"name":"language2","value":"","required":false,"custom":false},{"name":"language3","value":"","required":false,"custom":false},{"name":"optinglobal","value":false,"required":false,"custom":false},{"name":"optinincident","value":true,"required":false,"custom":false},{"name":"optincisurvey","value":true,"required":false,"custom":false},{"name":"country","value":"246","required":true,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"orig_submit_id","value":11403,"table":"incidents","required":true,"prev":"11403","custom":true,"customID":219,"customType":5},{"name":"orig_submit_name","value":"Loretta Borovitcky","table":"incidents","required":true,"prev":"Loretta Borovitcky","custom":true,"customID":220,"customType":8},{"name":"secondarycontact","value":true,"required":false,"custom":false},{"name":"disabled","value":true,"required":false,"custom":false},{"name":"ek_ibase_updt_type","value":"460","required":true,"custom":false},{"data_type":"products","hm_type":13,"linking_on":1,"linkingProduct":0,"table":"incidents","name":"prod","value":[2754,2431,9484],"cache":[]},{"name":"removal_reason","value":"Can\'t locate equipment","required":true,"custom":false},{"name":"effective_date","value":"Test","required":true,"custom":false},{"name":"thread","value":"test","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"ibase_country","value":"--","required":true,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"","value":"","required":false,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"entitlement_type","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"","value":"","required":true,"custom":false},{"name":"panel","value":"ibaseupdate"},{"name":"sesslang","value":""},{"name":"ek_type","value":"Administrative"},{"name":"ek_enabling_partner","value":null},{"name":"ek_mvs_manfacturer","value":""},{"name":"ek_service_dist","value":""},{"name":"ek_service_reseller","value":""},{"name":"ek_corporate","value":""},{"name":"ek_k_number","value":"0000050B"},{"name":"ek_serial_number","value":"50"},{"name":"ek_equip_component_id","value":"1924285"},{"name":"ek_sap_product_id","value":"COI0191382"},{"name":"ek_sap_soldto_custid","value":"599428"}]';				 
							 form_Data="["+data.join(",")+"]";
							 eo[0].post.form=form_Data;
							 
						
							
							
					}
					
					
				
				}, this);
		},

        /**
         * Overridable methods from FormSubmit:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _onButtonClick: function(evt)
        // _fireSubmitRequest: function()
        // _onFormValidated: function()
        // _onFormValidationFail: function()
        // _clearFlashData: function()
        // _displayErrorMessages: function(messageArea)
        // _defaultFormSubmitResponse: function(type, args)
        // _formSubmitResponse: function(type, args)
        // _handleFormResponseSuccess: function(result)
        // fn: function()
        // _handleFormResponseFailure: function(responseObject)
        // _navigateToUrl: function(result)
        // _resetFormForSubmission: function()
        // _onFormUpdated: function()
        // _onErrorResponse: function()
        // _resetFormButton: function()
        // _removeFormErrors: function()
        // _displayErrorDialog: function(message)
        // _toggleLoadingIndicators: function(turnOn)
        // _toggleClickListener: function(enable)
		_formSubmitResponse: function (type, args) {
			if (this._panelTrigger == true) {
				this._toggleLoadingIndicators(false);
				var dialogTitle = args[0].dialog_title || '';
				if (args[0].status == 1) {
					this._eo.data.trigger = this._formButton.id;
					this._showDialog(args[0].message, 'TIP', dialogTitle);
					RightNow.Event.fire('evt_resetForm', this._eo);
				} else if (args[0].status == 0) {
					//We have an error that was handled
					this._showDialog(args[0].message, 'ALARM', dialogTitle);
				} else {
					if (args[0])
						this._showDialog('An error occurred while performing the last request.', 'ALARM', dialogTitle);
				}
			}
		},
		
		_formSubmitResponse: function(type, args) {
			
			if (!this.data.attrs.disable_result_handler)
				this.parent(type, args);
			
			if(args[0].data.status==1){
				
				RightNow.UI.Dialog.messageDialog(args[0].data.message, new RightNow.Event.EventObject(this, {
					'title': "Success",
					'width': "350px",
					'icon': 'TIP'
                    })).show();
			}
			
		}
    },

    _showDialog: function (message, icon, title) {
        RightNow.UI.Dialog.messageDialog(message, new RightNow.Event.EventObject(this, {
            'title': title,
            'width': "250px",
            'icon': icon
        })).show();
    }
});