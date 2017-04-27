RightNow.namespace('Custom.Widgets.CIHFunction.HiddenInput');
Custom.Widgets.CIHFunction.HiddenInput = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function(data,instanceID) {
        this.data=data;
        this.instanceID=instanceID;
         RightNow.Event.subscribe('evt_formFieldValidateRequest', this.onValidate, this);
        RightNow.Event.subscribe('evt_setHiddenField', this.onSetHiddenField, this);
    },
    onValidate: function(type, args)
    {
         var eo        = new RightNow.Event.EventObject();
        eo.data.name  = this.data.attrs.name;
        eo.data.value = this.data.js.value;
        eo.data.form       = RightNow.UI.findParentForm('rn_' + this.instanceID + '_HiddenInput');
        RightNow.Event.fire('evt_formFieldValidateResponse', eo);

        RightNow.Event.fire('evt_formFieldCountRequest');
    },
    onSetHiddenField: function(evt, args)
    {
        
        var eo = args[0];
		
		//console.log(args);

        if (eo.data.name != this.data.attrs.name)
            return;
		
		if(document.getElementsByName(eo.data.name).length){
			document.getElementsByName(eo.data.name)[0].value=eo.data.value; 
			document.getElementById('rn_' + this.instanceID + '_HiddenInput').value=eo.data.value;
			this.data.js.value = eo.data.value;
		}
		
    }
	
	

});