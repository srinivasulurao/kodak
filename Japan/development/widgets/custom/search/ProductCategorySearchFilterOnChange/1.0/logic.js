RightNow.namespace('Custom.Widgets.search.ProductCategorySearchFilterOnChange');
Custom.Widgets.search.ProductCategorySearchFilterOnChange = RightNow.Widgets.ProductCategorySearchFilter.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.ProductCategorySearchFilter#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();
        }

        /**
         * Overridable methods from ProductCategorySearchFilter:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // selectNode: function(node)
        // getSubLevelRequestEventObject: function(expandingNode)
        // getSubLevelResponse: function(type, args)
        // _onReportResponse: function(type, args)
        // _getFiltersRequest: function(type, args)
        // _onResetRequest: function(type, args)
        // _initializeFilter: function()
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});