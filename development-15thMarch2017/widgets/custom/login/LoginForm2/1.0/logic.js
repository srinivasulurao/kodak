RightNow.namespace('Custom.Widgets.login.LoginForm2');
Custom.Widgets.login.LoginForm2 = RightNow.Widgets.LoginForm.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.LoginForm#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();
        }

        /**
         * Overridable methods from LoginForm:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _getRedirectUrl: function(result)
        // _onLoginResponse: function(response, originalEventObject)
        // _onSubmit: function(e)
        // _addErrorMessage: function(message, focusElement, showLink)
        // _toggleLoading: function(turnOn)
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    },

    /**
     * Makes an AJAX request for `login_ajax`.
     */
    getLogin_ajax: function() {
        // Make AJAX request:
        var eventObj = new RightNow.Event.EventObject(this, {data:{
            w_id: this.data.info.w_id,
            // Parameters to send
        }});
        RightNow.Ajax.makeRequest(this.data.attrs.login_ajax, eventObj.data, {
            successHandler: this.login_ajaxCallback,
            scope:          this,
            data:           eventObj,
            json:           true
        });
    },

    /**
     * Handles the AJAX response for `login_ajax`.
     * @param {object} response JSON-parsed response from the server
     * @param {object} originalEventObj `eventObj` from #getLogin_ajax
     */
    login_ajaxCallback: function(response, originalEventObj) {
        // Handle response
    }
});