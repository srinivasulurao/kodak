RightNow.namespace('Custom.Widgets.search.SearchButton2OnChange');
Custom.Widgets.search.SearchButton2OnChange = RightNow.Widgets.SearchButton.extend({ 
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
		_startSearch: function(evt) {
			 
			 if(document.getElementsByClassName('rn_CurrentPage')[0].innerText==1){
			   document.getElementsByClassName('rn_CurrentPage')[0].click();
			 }
		     else{
				 document.querySelectorAll(".rn_PaginationLinks li:nth-child(2) a")[0].click();
			 }
        
    },
        


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