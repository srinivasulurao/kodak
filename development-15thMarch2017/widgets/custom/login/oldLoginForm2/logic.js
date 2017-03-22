RightNow.Widget.LoginForm2 = function(data, instanceID){
    this.data = data;
    this.instanceID = instanceID;
    if(document.getElementById("rn_" + this.instanceID))
    {
        YAHOO.util.Event.addListener("rn_" + this.instanceID + "_Submit", "click", this._onSubmit, null, this);
        RightNow.Event.subscribe("evt_loginFormSubmitResponse", this._onLoginResponse, this);
        this._usernameField = document.getElementById("rn_" + this.instanceID + "_Username");
        this._passwordField = document.getElementById("rn_" + this.instanceID + "_Password");
        if(this._usernameField)
        {
            if(this._usernameField.value !== '')
            {
                if(this.data.attrs.initial_focus && this._passwordField)
                    this._passwordField.focus();
            }
            else if(this.data.attrs.initial_focus)
            {
                this._usernameField.focus();
            }
        }
    }
};
RightNow.Widget.LoginForm2.prototype = {

    /**
     * Function used to parse out the URL where we should redirect to
     * after a successful login
     * @param result Object The response object returned from the server
      * @return String The URL to redirect to
      */
    _getRedirectUrl : function(result)
    {
         var redirectUrl;
         if(this.data.js && this.data.js.redirectOverride)
             redirectUrl = RightNow.Url.addParameter(this.data.js.redirectOverride, 'session', result.sessionParm.substr(result.sessionParm.lastIndexOf("/") + 1));
         else
             redirectUrl = (this.data.attrs.redirect_url || result.url) + ((result.addSession) ? result.sessionParm : "");

         return redirectUrl + this.data.attrs.append_to_url;
     },
    /**
     * Event handler for when login has returned. Handles either successful login or failed login
     * @param type String Event name
     * @param arg Object Event arguments
     */
    _onLoginResponse : function(type, result)
    {
        result = result[0];
        var body = document.getElementById("rn_" + this.instanceID + "_Content");
        new YAHOO.util.Anim(body, { opacity: { to: 1 } }, 0.5, YAHOO.util.Easing.easeIn).animate();
        YAHOO.util.Dom.removeClass("rn_" + this.instanceID, 'rn_ContentLoading');
        if(this.instanceID == result.w_id)
        {
            if(result.success == 1)
            {
            	this._setResourcesCookie('cp_resources', '42', 6, '/', 'kodak.com', '');
                
			if(body)
                    body.innerHTML = result.message;
                RightNow.Url.navigate(this._getRedirectUrl(result));
            }
            else
            {
                this._addErrorMessage(result.message, 'rn_' + this.instanceID + '_Username', result.showLink);
            }
        }
    },

    _setResourcesCookie : function Set_Cookie( name, value, expires, path, domain, secure )
    {
    	// set time, it's in milliseconds
    	var today = new Date();
    	today.setTime( today.getTime() );

    	/*
    	if the expires variable is set, make the correct
    	expires time, the current script below will set
    	it for x number of days, to make it for hours,
    	delete * 24, for minutes, delete * 60 * 24
    	*/
    	if ( expires )
    	{
    	expires = expires * 1000 * 60 * 60 * 24;
    	}
    	var expires_date = new Date( today.getTime() + (expires) );

    	document.cookie = name + "=" +escape( value ) +
    	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
    	( ( path ) ? ";path=" + path : "" ) +
    	( ( domain ) ? ";domain=" + domain : "" ) +
    	( ( secure ) ? ";secure" : "" );
		
	//console.log(document.cookie) ;

        
    },

    /**
     * Event handler for when login button is clicked
     */
    _onSubmit : function()
    {
        var eo = new RightNow.Event.EventObject();
        eo.w_id = this.instanceID;
        eo.data.username = (this._usernameField) ? YAHOO.lang.trim(this._usernameField.value) : "";
        var errorMessage = "";
        if(eo.data.username.indexOf(' ') > -1)
            errorMessage = RightNow.Interface.getMessage("USERNAME_FIELD_CONTAIN_SPACES_MSG");
        else if(eo.data.username.indexOf("'") > -1 || eo.data.username.indexOf('"') > -1)
            errorMessage = RightNow.Interface.getMessage("USERNAME_FIELD_CONT_QUOTE_CHARS_MSG");

        if(errorMessage !== "")
        {
            this._addErrorMessage(errorMessage, 'rn_' + this.instanceID + '_Username');
            return false;
        }
        if(!this.data.attrs.disable_password && this._passwordField)
            eo.data.password = this._passwordField.value;
        else
            eo.data.password = "";
        eo.data.url = window.location.pathname;

        RightNow.Event.fire("evt_loginFormSubmitRequest", eo);
        new YAHOO.util.Anim("rn_" + this.instanceID + "_Content", { opacity: { to: 0 } }, 0.5, YAHOO.util.Easing.easeOut).animate();
        YAHOO.util.Dom.addClass("rn_" + this.instanceID, 'rn_ContentLoading');
        //since this form is submitted by script, force ie to do auto_complete
        if(YAHOO.env.ua.ie > 0)
        {
            if(window.external && "AutoCompleteSaveForm" in window.external)
            {
                var form = document.getElementById("rn_" + this.instanceID + "_Form");
                if(form)
                    window.external.AutoCompleteSaveForm(form);
            }
        }
        return false;
    },

    /**
     * Utility function to display an error message
     * @param message String  The error message to display
     * @param focusElement String The ID of the element to focus when clicking on the error message
     * @param showLink [optional] Boolean Denotes if error message should be surrounded in a link tag
     */
    _addErrorMessage: function(message, focusElement, showLink){
        var error = document.getElementById("rn_" + this.instanceID + "_ErrorMessage");
        if(error)
        {
            YAHOO.util.Dom.addClass(error, 'rn_MessageBox rn_ErrorMessage');
            //add link to message so that it can receive focus for accessibility reasons
            if(showLink === false)
            {
                error.innerHTML = message;
            }
            else
            {
                error.innerHTML = '<a href="javascript:void(0);" onclick="document.getElementById(\'' + focusElement + '\').focus(); return false;">' + message + '</a>';
                error.firstChild.focus();
            }
        }
    }
};
