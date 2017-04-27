RightNow.namespace('Custom.Widgets.CIHFunction.CheckBox');
Custom.Widgets.CIHFunction.CheckBox = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function(data,instanceID) 
    {
        this.data=data;
        this.instanceID=instanceID;
        this._checkBox=document.getElementById('rn_'+ this.instanceID + '_CheckBox');
        this._parentForm=RightNow.UI.findParentForm('rn_'+this.instanceID+'_CheckBox');
        RightNow.Event.subscribe('evt_formFieldValidateRequest', this._onValidate, this);
        RightNow.Event.subscribe('evt_resetForm', this.onResetForm, this);
    },

    onResetForm: function (evt, args){
        this._parentForm=this._parentForm || RightNow.UI.findParentForm("rn_"+ this.instanceID+ "_CheckBox");
        if(args[0].data.form === this._parentForm){
            this._checkBox.checked = this.data.attrs.checked;
        }
    },
    _checkRequired: function() {
      if(this.data.attrs.required){
          if(this._checkBox.checked==false){
              this._displayError(this.data.attrs.label_required);
              return false;
          }
      }
        return true;
    },
    _displayError: function (errorMessage){
        var commonErrorDiv = document.getElementById(this._formErrorLocation);
        if (commonErrorDiv){
            RightNow.UI.Form.errorCount++;
            if(Rightnow.UI.Form.chatSubmit && Rightnow.UI.Form.errorCount===1)
                commonErrorDiv.innerHTML="";
            
            var errorLink = "<div><b><a href='javascript:void(0);' onclick='document.getElementById(\"" + this._checkBox.id +
                "\").focus(); return false;'>" + this.data.attrs.label_required + " ";

            if (errorMessage.indexOf("%s") > -1)
                errorLink = RightNow.Text.sprintf(errorMessage, errorLink);
            else
                errorLink = errorLink;

            errorLink += "</a></b></div> ";
            commonErrorDiv.innerHTML += errorLink;
        }
        this.Y.one(this._checkBox).addClass("rn_ErrorField");
        this.Y.one("rn_"+this.instanceID+"_label").addClass("rn_ErrorField");
    },
  onValidate: function (type,args){
     var eo=new RightNow.Event.EventObject(this, {data: {
                                    name: this.data.attrs.name,
                                    value: this._checkBox.checked,
                                    form: RightNow.UI.findParentForm('rn_'+ this.instanceID + '_CheckBox')
         
     }});
    RightNow.Event.fire('evt_formFieldValidateResponse', eo);
    RightNow.Event.fire('evt_formFieldCountRequest');  
  },
  _onValidate: function (type,args){
      this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID);
        var eo = new RightNow.Event.EventObject(this, {data : {
            "name": this.data.attrs.name,
            "value": this._checkBox.checked,
            "table": this.data.js.table,
            "required": (this.data.attrs.required ? true: false),
            "prev" : this.data.js.prev,
            "form": this._parentForm
        }});
      if (RightNow.UI.Form.form === this._parentForm) {
            this._formErrorLocation = args[0].data.error_location;

            if (this._checkRequired()) {
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
        RightNow.Event.fire("evt_formFieldCountRequest");
  }    
});
    