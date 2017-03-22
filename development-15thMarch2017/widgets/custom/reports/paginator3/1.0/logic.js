RightNow.namespace('Custom.Widgets.reports.paginator3');
Custom.Widgets.reports.paginator3 = RightNow.Widgets.Paginator.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.Paginator#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();
        }

        /**
         * Overridable methods from Paginator:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _onPageChange: function(evt, pageNumber)
        // _onDirection: function(evt, isForward)
        // _onReportChanged: function(type, args)
        // _shouldShowHellip: function(pageNumber, currentPage, endPage)
        // _shouldShowPageNumber: function(pageNumber, currentPage, endPage)
        // _cloneForwardAndBackwardButton: function()
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});