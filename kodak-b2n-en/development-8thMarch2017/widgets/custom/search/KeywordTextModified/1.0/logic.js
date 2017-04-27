RightNow.namespace('Custom.Widgets.search.KeywordTextModified');
Custom.Widgets.search.KeywordTextModified = RightNow.Widgets.KeywordText.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.KeywordText#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.data.js.initialValue=this.data.attrs.default_value;
        
            this.parent();

        }

        /**
         * Overridable methods from KeywordText:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _onChange: function(evt)
        // _onGetFiltersRequest: function(type, args)
        // _setFilter: function()
        // _onChangedResponse: function(type, args)
        // _onResetRequest: function(type, args)
        // _decoder: function(value)
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});