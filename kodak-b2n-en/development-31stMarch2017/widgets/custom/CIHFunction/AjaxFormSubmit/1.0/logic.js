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