RightNow.namespace('Custom.Widgets.login.CustomLoginDialog');
Custom.Widgets.login.CustomLoginDialog = RightNow.Widgets.LoginDialog.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.LoginDialog#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();
        }

        /**
         * Overridable methods from LoginDialog:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _initializeTriggerLink: function(id)
        // _toggleForms: function ()
        // _swapDialogLabelsForForm: function (form)
        // _onLoginEventFired: function(evt, args)
        // _onLoginTriggerClick: function(event, args, triggeredFromSocialAction)
        // _createDialog: function()
        // _onCancel: function()
        // _clearErrorMessage: function()
        // _shouldIgnoreEnterKey: function(name, event)
        // _processLoginForm: function(form)
        // _validateUsername: function(username)
        // _onSubmit: function(event, args)
        // _submitCreateAccountForm: function()
        // _submitLoginForm: function()
        // _sendLoginForm: function(eventObject)
        // _onResponseReceived: function(response, originalEventObject)
        // _getRedirectUrl: function(result)
        // _onHasSocialUserResponse: function(response, originalEventObject)
        // _createSocialUserForm: function(description)
        // _submitSocialUserInfo: function()
        // _onSocialUserInfoResponse: function(response, originalEventObject)
        // _addLoginErrorMessage: function(message, focusElement, showLink)
        // _toggleLoading: function(turnOn)
        // _setDialogTitleText: function(text)
        // _conformLoginPlaceholders: function()
        // _adjustForOpenLoginExplanationArea: function()
        // _toggleWarningMessageOnSocialAction: function(shouldHide)
        // _toggleErrorClass: function()
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});