RightNow.namespace('Custom.Widgets.search.SearchRequestActivitySearchButton');
Custom.Widgets.search.SearchRequestActivitySearchButton = RightNow.Widgets.SearchButton.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.SearchButton#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();
        },
		_startSearch:function(evt){
		
		var internal_user=this.data.attrs.internal_user;
				if(internal_user=="Y" && document.getElementsByName('ek_customer_sapid')[0].value==""){
					document.getElementById('rn_search_ErrorLocation').innerHTML='<div><b><a href="javascript:void(0);" style="color:red" onclick="document.getElementById(&quot;rn_CustomTextInput_13_TextInput&quot;).focus(); return false;"> SAP Customer ID is required.</a></b></div>';
				}
				else{
					document.getElementById('rn_search_ErrorLocation').innerHTML="";
					this.parent(evt);  
				}
		 
	    }

        /**
         * Overridable methods from SearchButton:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _startSearch: function(evt)
        // _enableClickListener: function()
        // _disableClickListener: function()
    },
	
    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});