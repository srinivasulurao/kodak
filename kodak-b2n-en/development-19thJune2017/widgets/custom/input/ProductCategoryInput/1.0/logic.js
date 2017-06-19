RightNow.namespace('Custom.Widgets.input.ProductCategoryInput');
Custom.Widgets.input.ProductCategoryInput = RightNow.Widgets.ProductCategoryInput.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.ProductCategoryInput#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();
        }

        /**
         * Overridable methods from ProductCategoryInput:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _initializeHint: function()
        // buildPanel: function ()
        // _resetProductCategoryMenu: function()
        // _updatePermissionedHierData: function (dataType)
        // displaySelectedNodesAndClose: function(focus, fireSelectionEvent)
        // selectNode: function(node)
        // getSubLevelRequest: function (expandingNode)
        // getSubLevelRequestEventObject: function(expandingNode)
        // getSubLevelResponse: function(type, args)
        // _setButtonClick: function()
        // _onValidate: function(type, args)
        // _createHintElement: function(visibility)
        // _toggleHint: function(hideOrShow)
        // _realignHint: function(delay)
        // swapLabel: function(container, requiredLevel, label, template)
        // updateRequiredLevel: function(evt, constraint)
        // _checkSelectionErrors: function()
        // _removeErrorMessages: function()
        // _displayErrorMessage: function(message, currentNode)
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});