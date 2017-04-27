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
			
			if (!this.data.attrs.disable_result_handler)
				this.parent(type, args);
			
			if(args[0].data.status==1){
				
				RightNow.UI.Dialog.messageDialog(args[0].data.message, new RightNow.Event.EventObject(this, {
					'title': "Success",
					'width': "350px",
					'icon': 'TIP'
                    })).show();
			}
			
			if(args[0].data.status==0){
				this._showDialog(args[0].data.message, 'ALARM', dialogTitle);
			}
			
		}
	}, //Overrides ends here.
		

    _showDialog: function (message, icon, title) {
        RightNow.UI.Dialog.messageDialog(message, new RightNow.Event.EventObject(this, {
            'title': title,
            'width': "250px",
            'icon': icon
        })).show();
    }
});