RightNow.namespace('Custom.Widgets.search.SearchButtonOnChange');
Custom.Widgets.search.SearchButtonOnChange = RightNow.Widgets.SearchButton.extend({ 
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