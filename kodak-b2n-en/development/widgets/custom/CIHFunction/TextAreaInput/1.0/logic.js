RightNow.namespace('Custom.Widgets.CIHFunction.TextAreaInput');
Custom.Widgets.CIHFunction.TextAreaInput = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
		
		this._formErrorLocation = null;
		this._validated = false;
		this._parentForm = RightNow.UI.findParentForm('rn_' + this.instanceID + '_TextAreaInput');
		this._inputField = document.getElementById('rn_' + this.instanceID + '_TextAreaInput');
		RightNow.Event.subscribe('evt_formFieldValidateRequest', this._onValidate, this);
		RightNow.Event.subscribe('evt_setTextField', this.onSetTextField, this);
		RightNow.Event.subscribe('evt_resetForm', this.onResetForm, this);

    },
onSetTextField: function (evt, args) {
        var eo = args[0];

        if (eo.name != this.data.attrs.name)
            return;

        this.data.js.value = eo.value;
    },

    /**
    * Validation routine to check if field is required, and if so, ensure it has a value
    * @return Boolean denoting if required check passed
    */
    _checkRequired2: function(args) {
		
		if(args[0].data.hasOwnProperty('coming_form') && args[0].data.coming_form==this._parentForm){
							input_field_val=document.getElementById('rn_'+this.instanceID+'_TextAreaInput').value;
							input_field_instance=document.getElementById('rn_'+this.instanceID+'_TextAreaInput');  
							
								if((input_field_val=="" || input_field_val==null) && this.data.attrs.required==true && input_field_instance.disabled==false){
								  console.log("Tirupati Balaji");
								  this._displayError(this.data.attrs.label_required);	
								  RightNow.Event.fire("evt_formFieldValidationFailure", eo);
								  //Fire a custom Event, for manageContact, IRepairRequest and IBaseRequest
								  var eo = new RightNow.Event.EventObject();
								  eo.data.error_field=this.data.attrs.name;
								  RightNow.Event.fire("evt_formErrorExist", eo);  
								  return false;
								}
        }
		
		return true;
    },
	
	_checkRequired:function(){
		
		if (this.data.attrs.required) {
            if (this._inputField.value === "" && this._inputField.disabled == false) {
                this._displayError(this.data.attrs.label_required);
                return false;
            }
        }
        return true;
	},

    /**
    * Displays error by appending message above submit button
    * @param errorMessage String Message to display
    */
    _displayError: function (errorMessage) {
        var commonErrorDiv = document.getElementById(this._formErrorLocation);	
		
        if (commonErrorDiv) {
            RightNow.UI.Form.errorCount++;
            if (RightNow.UI.Form.chatSubmit && RightNow.UI.Form.errorCount === 1)
                commonErrorDiv.innerHTML = "";

            var errorLink = "<div><b><a href='javascript:void(0);' style='color:red' onclick='document.getElementById(\"" + this._inputField.id +
                "\").focus(); return false;'>" + this.data.attrs.label_input + " ";

            if (errorMessage.indexOf("%s") > -1)
                errorLink = RightNow.Text.sprintf(errorMessage, errorLink);
            else
                errorLink = errorLink + errorMessage;

            errorLink += "</a></b></div> ";
            commonErrorDiv.innerHTML += errorLink;
        }
		if(document.getElementById(this._inputField.id)!=null)
         this.Y.one("#"+this._inputField.id).addClass("rn_ErrorField");
	    if(document.getElementById("rn_" + this.instanceID + "_Label")!=null)
         this.Y.one("#rn_" + this.instanceID + "_Label").addClass("rn_ErrorLabel");
    },

    onResetForm: function (evt, args) {
        this._blurValidate();
        this.Y.one("#"+this._inputField.id).removeClass("rn_ErrorField");
		if(document.getElementById("rn_" + this.instanceID + "_Label")!=null)
        this.Y.one("#rn_" + this.instanceID + "_Label").removeClass("rn_ErrorLabel");
        this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID + "_TextAreaInput");
        if (args[0].data.form === this._parentForm) {
            this._inputField.value = '';
        }
    },

    /**
    * Event handler for when form is being submitted
    *
    * @param {String} type Event name
    * @param {Object} args Event arguments
    */

    /*
    _onValidate: function (type, args) {
    this._validated = true;
    this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID + "_TextAreaInput");

    var eo = new RightNow.Event.EventObject();
    eo.data.name = this.data.attrs.name;
    eo.data.value = this._inputField.value;
    eo.data.form = RightNow.UI.findParentForm(this._inputField.id);

    if (RightNow.UI.Form.form === this._parentForm) {
    this._formErrorLocation = args[0].data.error_location;
    }

    if (this._checkRequired()) {
    RightNow.Event.fire('evt_formFieldValidateResponse', eo);

    RightNow.Event.fire('evt_formFieldCountRequest');
    }

    },

    */

    _onValidate: function (type, args) {
        this._validated = true;
        this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID);

        var eo = new RightNow.Event.EventObject();
        eo.data = { "name": (this._inputField.disabled == true ? '' : this.data.attrs.name),
            "value": this._getValue(),
            "table": this.data.js.table,
            "required": (this.data.attrs.required ? true : false),
            "prev": this.data.js.prev,
            "form": this._parentForm
        };
        if (RightNow.UI.Form.form === this._parentForm) {
            this._formErrorLocation = args[0].data.error_location;
            this._trimField();

            if (this._checkRequired()) {
                     this.Y.one("#"+this._inputField.id).removeClass("rn_ErrorField");
					if(document.getElementById("rn_" + this.instanceID + "_Label")!=null)
                     this.Y.one("#rn_" + this.instanceID + "_Label").removeClass("rn_ErrorLabel");
                if(this.data.attrs.require_validation) {
                     this.Y.one("#"+this._validationField.id).removeClass("rn_ErrorField");
					if(document.getElementById("rn_" + this.instanceID + "_LabelValidate")!=null)
                     this.Y.one("#rn_" + this.instanceID + "_LabelValidate").removeClass("rn_ErrorLabel");
                }

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
                if (this.data.js.channelID) {
                    eo.data.channelID = this.data.js.channelID;
                }
                eo.w_id = this.data.info.w_id;
                RightNow.Event.fire("evt_formFieldValidateResponse", eo); 
            }
            else {
                RightNow.UI.Form.formError = true;
            }
        }
		else if(this._parentForm.indexOf("rn_ManageContacts") > -1 || this._parentForm.indexOf("rn_IBaseUpdate") > -1 || this._parentForm.indexOf("rn_RepairRequest") > -1){
			
				//Srini's Customization.
				this._formErrorLocation = args[0].data.error_location;
				this._trimField();

				if (this._checkRequired2(args)) {
						 this.Y.one("#"+this._inputField.id).removeClass("rn_ErrorField");
						if(document.getElementById("rn_" + this.instanceID + "_Label")!=null)
						 this.Y.one("#rn_" + this.instanceID + "_Label").removeClass("rn_ErrorLabel"); 
				    if(this.data.attrs.require_validation) {
						 this.Y.one("#"+this._validationField.id).removeClass("rn_ErrorField");
						if(document.getElementById("rn_" + this.instanceID + "_LabelValidate")!=null)
						 this.Y.one("#rn_" + this.instanceID + "_LabelValidate").removeClass("rn_ErrorLabel");
					}

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
					if (this.data.js.channelID) {
						eo.data.channelID = this.data.js.channelID;
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
        this._validated = false;
        RightNow.Event.fire("evt_formFieldCountRequest");
    },

    _blurValidate: function () {

        this._trimField();
        if (this._checkRequired()) {
            var eo = new RightNow.Event.EventObject();
            eo.data.name = this.data.attrs.name;
            eo.data.value = this._inputField.value;
            eo.data.form = RightNow.UI.findParentForm('rn_' + this.instanceID + '_TextAreaInput');

               this.Y.one("#"+this._inputField.id).removeClass("rn_ErrorField");
			if(document.getElementById("rn_" + this.instanceID + "_Label")!=null)
               this.Y.one("#rn_" + this.instanceID + "_Label").removeClass("rn_ErrorLabel");
			   RightNow.Event.fire('evt_formFieldValidateResponse', eo);
			   return true;
        }
        return false;

    },


    /**
    * Returns the field's value
    * @return Mixed String or Int (for Int data type)
    */
    _getValue: function () {
        if (this.data.js.type === RightNow.Interface.Constants.EUF_DT_INT) {
            if (this._inputField.value !== "")
                return parseInt(this._inputField.value);
        }
        if (this.data.js.mask)
            return this._stripMaskFromFieldValue();
        return encodeURI(this._inputField.value);

    },

    _trimField: function () {
        if (this._inputField.value !== "" && this.data.js.type !== RightNow.Interface.Constants.EUF_DT_PASSWORD)
            this._inputField.value = YAHOO.lang.trim(this._inputField.value);
        return true;
    }

});