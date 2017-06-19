RightNow.namespace('Custom.Widgets.CIHFunction.PanelValidator');
Custom.Widgets.CIHFunction.PanelValidator = RightNow.Field.extend({ 

overrides: {
    constructor: function() {
			
			var form = RightNow.Form.find(this.baseDomID, this.instanceID);
			form.on("submit", this.onValidate, this);  
			
			if(this.data.attrs.dynamic_field)
				form.on("submit",this.onValidateDynamicForm,this);
		    
    },
	
	onValidate: function(type, args) {

            var form = RightNow.Form.find(this.baseDomID, this.instanceID);
			
			var required_fields=this.data.attrs.required_field.split("|");
			
			var error_exist=0;
			
			for(i=0;i<required_fields.length;i++){
				    var field_value=this._getValue(required_fields[i]);
				    var eo = new RightNow.Event.EventObject(this, {data: {
								"name" : this.data.js.name,
								"table" : this.data.js.table,
								"value":field_value,
								"form": form._parentForm
								}});
                    
					if(field_value==''){
						error_exist=1;
						 errorLocation= args[0].data.error_location;
						 //document.getElementById(this.instanceID+"_Label").className = "rn_Label rn_ErrorLabel";
						 //document.getElementsByClassName("yahoo_date_selected")[0].className="rn_Text yahoo_date_selected rn_ErrorField";
						 var commonErrorDiv = this.Y.one("#" + errorLocation);
						 errorString="<b><a href='javascript:void(0)' onclick=\"document.getElementsByName('"+required_fields[i]+"')[0].focus(); return false;\">"+this._getStatement(required_fields[i])+"</a></b><br>";
						 commonErrorDiv.append(errorString);
						 RightNow.Event.fire("evt_formFieldValidateFailure", eo);
						
					}else{
						//document.getElementById(this.instanceID+"_Label").className = "rn_Label";
						//document.getElementsByClassName("yahoo_date_selected")[0].className="rn_Text yahoo_date_selected";
						RightNow.Event.fire("evt_formFieldValidationPass", eo);
						//return eo;
					}
					
			}
			if(error_exist)
			    return false;
	},
	
    onValidateDynamicForm(type,args){
		
		var dynamic_fields=this.data.attrs.dynamic_field.split("|");
		var error_exist=0;
		
		for(i=0;i<dynamic_fields.length;i++){
		    var ground="#"+this.data.attrs.panel;
			var field=dynamic_fields[i];
			var df_length=document.querySelectorAll(ground+' [name="'+field+'"]').length;
			var form = RightNow.Form.find(this.baseDomID, this.instanceID);
			
			for(j=0;j<df_length;j++){
				var eo = new RightNow.Event.EventObject(this, {data: {
								"name" : this.data.js.name,
								"table" : this.data.js.table,
								"value":document.querySelectorAll(ground+' [name="'+field+'"]')[j].value,
								"form": form._parentForm
								}});
				if(document.querySelectorAll(ground+' [name="'+field+'"]')[j].disabled==false && document.querySelectorAll(ground+' [name="'+field+'"]')[j].value==""){
					 error_exist=1;
					 var commonErrorDiv = this.Y.one("#" + errorLocation);
					 errorString="<b><a href='javascript:void(0)' onclick=\"document.getElementsByName('"+field+"')["+j+"].focus(); return false;\">"+this._getStatement(dynamic_fields[i])+"</a></b><br>";
					 commonErrorDiv.append(errorString);
					 RightNow.Event.fire("evt_formFieldValidateFailure", eo);
				}
				else{
					RightNow.Event.fire("evt_formFieldValidationPass", eo);
				}
			}
		}
		
		if(error_exist)
			return false;
		
	},
	
	_getValue(field){
	
			var ground="#"+this.data.attrs.panel;
			return document.querySelectorAll(ground+' [name="'+field+'"]')[0].value;

	},
	
	_getStatement(field){
		var field_statement=new Array();
		field_statement['firstname']="First Name is required !";
		field_statement['lastname']="Last Name is required !";
		field_statement['emailaddress']="Email Address is required !";
		field_statement['officephone']="Telephone #(Office) is required !";
		field_statement['language1']="Preferred Language 1 is required !";
		field_statement['country']="Country is required !";
		field_statement['cat']="Problem Found is required !";
		field_statement['ek_severity']="Severity is required !";
		field_statement['ek_repeatability']="Repeatability is required !";  
		field_statement['thread']="Comments is required !";
		field_statement['ek_ibase_updt_type']="Ibase Update Type is required !";
		//removal_reason|effective_date|thread|product_identifier|ibase_firstname|ibase_lastname|ibase_phone|sitecustomername|ibase_address|street|city|state|zipcode|ibase_country|entitlement_type
		field_statement['removal_reason']="Request Detail's Removal Reason is required !";
		field_statement['effective_date']="Request Detail's Effective Date is required !";
		field_statement['product_identifier']="Request Detail's Product identifier(K#) is required !";
		field_statement['ibase_firstname']="Request Detail's First Name is required !";
		field_statement['ibase_lastname']="Request Detail's Last Name is required !";
		field_statement['ibase_phone']="Request Detail's Telephone #(Office): is required !";
		field_statement['sitecustomername']="Request Detail's Site Customer Name is required !";
		field_statement['ibase_address']="Request Detail's Site Customer Address is required !";
		field_statement['state']="Request Detail's State/Province is required";
		field_statement['street']="Request Detail's Street Address 1 is required !";
		field_statement['city']="Request Detail's City is required !";
		field_statement['zipcode']="Request Detail's Postal Code/Zip is required !";
		field_statement['ibase_country']="Request Detail's Country is required !";
		field_statement['entitlement_type']="Request Detail's Entititlement Type is required !";
		
		return field_statement[field];
	},

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
	
}//Overrides working here.

});