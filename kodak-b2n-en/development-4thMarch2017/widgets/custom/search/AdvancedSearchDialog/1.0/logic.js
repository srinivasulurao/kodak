RightNow.namespace('Custom.Widgets.search.AdvancedSearchDialog');
Custom.Widgets.search.AdvancedSearchDialog = RightNow.Widgets.AdvancedSearchDialog.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.AdvancedSearchDialog#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();
        }

        /**
         * Overridable methods from AdvancedSearchDialog:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _openDialog: function(evt)
        // _performSearch: function()
        // _cancelFilters: function()
        // _closeDialog: function()
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});