RightNow.namespace('Custom.Widgets.login.OpenLogin');
Custom.Widgets.login.OpenLogin = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
    //this.data = data;
    //this.instanceID = instanceID;
    var id = "rn_" + this.instanceID;
    //YAHOO.util.Event.on(id + "_ProviderButton", "click", this._onClick, null, this);
    this.Y.one("#"+id + "_ProviderButton").on("click", this._onClick,this);
// immediately cause the click to send them to JANRAIN	when they set openid_placeholder to true
     if(this.data.attrs.openid_placeholder){
//alert ('hi5');
           this._onClick();
}
    //YAHOO.util.Event.on(id + "_LoginButton", "click", this._onLogin, null, this);
    this.Y.one("#"+id + "_LoginButton").on("click", this._onLogin,this);
    RightNow.Event.subscribe("evt_FederatedProviderSelected", function(){
//        YAHOO.util.Dom.addClass("rn_" + this.instanceID + "_ActionArea", "rn_Hidden");
    this.Y.one("rn_" + this.instanceID + "_ActionArea").addClass("rn_Hidden");
    }, this);
    if(RightNow.Url.getParameter("emailerror") && this.data.attrs.controller_endpoint.search(/twitter/i) > 0){
        this._displayMessage("email");
    }
    else if(this.data.js && this.data.js.error){
        this._displayMessage("error", this.data.js.error);
    }

    },
    
        /**
    * Displays an additional widget area when a provider is clicked.
    * @param event DOM Event The click event
    */
    _onClick: function(event){
        //	alert('click');
	this._selectOpenIDProvider();
        var loginButton = document.getElementById("rn_" + this.instanceID + "_LoginButton"),
            actionArea = document.getElementById("rn_" + this.instanceID + "_ActionArea"),
            dialog, eventObject, anim, selectProvider;
        if(this.data.attrs.display_in_dialog){
            this._dialog = this._dialog || RightNow.UI.Dialog.actionDialog(RightNow.Interface.getMessage("LOGIN_LBL"), actionArea, {
                        buttons: [{text: RightNow.Interface.getMessage("CANCEL_LBL"), handler: function(){this.hide();}}],
                        width: "330px",
                        cssClass: "rn_OpenLogin rn_OpenLoginDialog",
                        navButtons: true});
            if(this._dialog.submitEvent){
                this._dialog.cfg.setProperty("hideaftersubmit", false);
                this._dialog.submitEvent.subscribe(this._onLogin, null, this);
            }
            this._dialog.show();
        }
        else{
            //fire event to notify other widget instances to hide their actionArea
            eventObject = new RightNow.Event.EventObject();
            eventObject.w_id = this.instanceID;
            RightNow.Event.fire("evt_FederatedProviderSelected", eventObject);
        }
        selectProvider = function(){
//		alert('select provider');
            if(!this.data.attrs.openid && loginButton){
                loginButton.tabIndex = -1;
                this._setAriaSelected(loginButton);
                loginButton.focus();
            }
            else if(this.data.attrs.openid && this.data.attrs.openid_placeholder){
                this._selectOpenIDProvider();
            }            
        };
        if(this.data.attrs.display_in_dialog){
            //YAHOO.util.Dom.removeClass(actionArea, "rn_Hidden");
            this.Y.one("#" + actionArea).removeClass("rn_Hidden");
            selectProvider.call(this);
        }
        else{
//		alert('unhideing');
//            YAHOO.util.Dom.setStyle(actionArea, 'opacity', '0');
//            YAHOO.util.Dom.removeClass(actionArea, "rn_Hidden");
//            anim = new YAHOO.util.Anim("rn_" + this.instanceID + "_ActionArea", {opacity: {to: 1}}, 0.1, YAHOO.util.Easing.easeIn);
//            anim.onComplete.subscribe(selectProvider, null, this);
//            anim.animate();   
		this._onLogin();
        }
        
    },
    /**
    * Sets the aria-selected attribute on the specified element.
    * Maintains an internal list in order to ensure that only one element for
    * all widget instances is aria-selected="true".
    * @param selctedElement HTMLElement The element to set the attribute
    */
    _setAriaSelected: function(selectedElement){
        this._setAriaSelected._items = this._setAriaSelected._items || [];
//        var items = this._setAriaSelected._items,
//            Dom = YAHOO.util.Dom,
//            alreadyInList = false,
//            i, length;
        var items = this._setAriaSelected._items,
            alreadyInList = false,
            i, length;
        for(i = 0, length = items.length; i < length; i++){
            this.Y.one(items[i]).setAttribute('aria-selected', 'false');
            if(items[i].id === selectedElement.id)
                alreadyInList = true;
        }
        if(!alreadyInList)
            this._setAriaSelected._items.push(selectedElement);
        this.Y.one(selectedElement).setAttribute('aria-selected', 'true');
    },
    
        /**
    * Displays a dialog.
    * @param messageType String The type of message being displayed. Either 'email' or 'error'
    * @param errorMessage String optional error message
    */
    _displayMessage: function(messageType, errorMessage){
        if(!this._displayMessage._displayingMessage){
            //only display error dialog once across all widget instances
            this._displayMessage._displayingMessage = true;
            var dialog, title,
                dialogBody = document.createElement("div"),
                submitHandler = function(){
                    dialog.hide();
                    this._displayMessage._displayingMessage = false;
                },
                Dialog = RightNow.UI.Dialog;
            dialogBody.className = "rn_OpenLogin rn_OpenLoginDialog";
            if(messageType === "email"){
                var fieldID = "rn_" + this.instanceID + "_Email",
                    errorDiv, emailField,
                    successHandler = function(serverResponse){
                        this._submittingEmail = false;
                        dialog.enableButtons();
                        if(serverResponse.responseText === "true")
                            RightNow.Url.navigate(this.data.attrs.redirect_url);
                        else
                            Dialog.messageDialog(RightNow.Interface.getMessage("ERROR_REQUEST_ACTION_COMPLETED_MSG"), {"icon": "WARN"});
                    };
                title = this.data.attrs.label_email_prompt_title;
                dialogBody.innerHTML = this.data.attrs.label_email_prompt +
                    "<form method='post' action='' onsubmit='javascript:void(0);'>" + 
                    "<label class='rn_EmailLabel' for='" + fieldID + "'>" + this.data.attrs.label_email_address + 
                    "<span class='rn_Required'> * </span><span class='rn_ScreenReaderOnly'>" + RightNow.Interface.getMessage("REQUIRED_LBL") + "</span></label>" +
                    "<input type='text' id='" + fieldID + "' maxlength='80' class='rn_EmailInput'/></form>";
                submitHandler = function(){
//				alert('hi2');
                    if(!this._submittingEmail){
                        this._submittingEmail = true;
                        dialog.disableButtons();
                        emailField = emailField || document.getElementById(fieldID);
                        if(emailField && emailField.value){
                            emailField.value = this.Y.lang.trim(emailField.value);
                            if(RightNow.Text.isValidEmailAddress(emailField.value)){
                               // YAHOO.util.Dom.addClass(errorDiv, "rn_Hidden");
                                 this.Y.one(errorDiv).addClass("rn_Hidden");
                                RightNow.Ajax.makeRequest("/ci/openlogin/provideEmail", {email: emailField.value, userData: RightNow.Url.getParameter("emailerror")}, {successHandler: successHandler, scope: this});
                                return;
                            }
                            else if(errorDiv){
//                                YAHOO.util.Dom.removeClass(errorDiv, "rn_Hidden");
                                this.Y.one(errorDiv).removeClass("rn_Hidden");
                                errorDiv.firstChild.focus();
                            }
                            else{
                                errorDiv = document.createElement("div");
                                errorDiv.className = "rn_MessageBox rn_ErrorMessage";
                                errorDiv.innerHTML = "<a href='javascript:void(0);' onclick='document.getElementById(\"" + fieldID + "\").focus();'>" + 
                                    RightNow.Text.sprintf(RightNow.Interface.getMessage("PCT_S_IS_INVALID_MSG"), this.data.attrs.label_email_address) + "</a>";
                                //YAHOO.util.Dom.insertBefore(errorDiv, dialogBody);
                                this.Y.one(dialogBody).insert(errorDiv,'before');
                                errorDiv.firstChild.focus();
                            }
                        }
                        dialog.enableButtons();
                        this._submittingEmail = false;
                    }
                };
                dialog = Dialog.actionDialog(title, dialogBody, {
                            buttons: [{text: this.data.attrs.label_email_prompt_submit_button, handler: {fn: submitHandler, scope: this}, isDefault: true},
                                      {text: this.data.attrs.label_email_prompt_cancel_button, handler: function(){this.hide();}}], 
                            width: "330px"
                });
                Dialog.addDialogEnterKeyListener(dialog, submitHandler, this);
                dialog.show();
            }
            else if(messageType === "error"){
                RightNow.UI.Dialog.messageDialog(errorMessage, {icon: "WARN", title: RightNow.Interface.getMessage("OOPS_LBL"), width: "330px"});
            }
        }
    },
    
        
    /**
    * Called when an OpenID provider is clicked. Performs preselection on the provider's pre-filled URL.
    */
    _selectOpenIDProvider: function(){
//	alert('calling selectOpenIDProvider');
        if(this.data.attrs.openid_placeholder && this.data.attrs.openid_placeholder.indexOf("[") > -1){
            var input = document.getElementById("rn_" + this.instanceID + "_ProviderUrl"),
                start, end, selection;
            if(input){
                this._setAriaSelected(input);
                input.focus();
                start = input.value.indexOf("[");
                end = input.value.indexOf("]") + 1;
                if(input.value && start > -1 && end > start){
                    if(window.getSelection){
                        selection = window.getSelection();
                         if(selection.rangeCount > 0)
                            selection.removeAllRanges();
                        input.setSelectionRange(start, end);
                    }
                    else if(document.selection && document.selection.createRange){
                        //older IE
                        selection = input.createTextRange();
                        selection.collapse(true);
                        selection.moveStart("character", start);
                        selection.moveEnd("character", end - start);
                        selection.select();
                    }
                }
            }
        }
    },
    
        /**
    * Navigates the browser to the controller endpoint.
    */
    _onLogin: function(){
//				alert('hi3');

        var input = document.getElementById("rn_" + this.instanceID + "_ProviderUrl"),
            goToUrl = function(url){
                if(this.YUI.Env.UA.ie){
                    //IE doesn't set http_referer when window.location changes.
                    //IE9 says this behavior is a feature, not a bug.
                    var referLink = document.createElement("a"),
                        Url = RightNow.Url;
                    referLink.href = ((Url.getSession()) ? Url.addParameter(url, "session", Url.getSession()) : url);
                    referLink.className = "rn_Hidden";
                    document.body.appendChild(referLink);
                    referLink.click();
                }
                else{
                    RightNow.Url.navigate(url);
                }
            },
            urlToGoTo;
        if(this.data.attrs.openid){
            if(input){
		// partnerplacedev               input.value = 'https://kodak-test.opx.janrain.ws';
		// partnerplaceqa               input.value = 'https://kodak-staging.opx.janrain.ws';
		// production          input.value = 'https://openid.kodak.com';
		var loc = document.domain;
		loc = loc.toString();
		loc = loc.toUpperCase();
		//alert('loc is '+loc);
		if (loc == "DEV-SERVICES.KODAK.COM" || loc == "KODAK-B2B-EN--UPGRADE.CUSTHELP.COM" || loc == "KODAK-B2B-EN--PRO.CUSTHELP.COM")  {
//                input.value = 'https://kodak-staging.opx.janrain.ws';
                  input.value = 'https://openidqa.kodak.com';
		} 
		else
		{
                  input.value = 'https://openid.kodak.com';
		}
                if(this.data.attrs.preset_openid_url && input.value !== "" && input.value !== this.data.attrs.openid_placeholder && !RightNow.Text.isValidUrl(input.value)){
                    //username on a preset openid service
                    urlToGoTo = this.data.attrs.preset_openid_url.replace(/\[username\]/, input.value);
                }
//                else if(!this.data.attrs.preset_openid_url && input.value !== this.data.attrs.openid_placeholder && RightNow.Text.isValidUrl(input.value)){
// remove the check of the value being the same as entered in the widget
                else if(!this.data.attrs.preset_openid_url && RightNow.Text.isValidUrl(input.value)){
                    //url to an openid service
                    urlToGoTo = input.value;
                }
                if(urlToGoTo){
//				alert('urlToGoTo is '+this.data.attrs.controller_endpoint + encodeURIComponent(urlToGoTo) + "/" + encodeURIComponent(this.data.attrs.redirect_url));
                    goToUrl(this.data.attrs.controller_endpoint + encodeURIComponent(urlToGoTo) + "/" + encodeURIComponent(this.data.attrs.redirect_url));
                }
            }
        }
        else{
            goToUrl(this.data.attrs.controller_endpoint + encodeURIComponent(this.data.attrs.redirect_url));
        }
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    },

    /**
     * Makes an AJAX request for `default_ajax_endpoint`.
     */
    getDefault_ajax_endpoint: function() {
        // Make AJAX request:
        var eventObj = new RightNow.Event.EventObject(this, {data:{
            w_id: this.data.info.w_id,
            // Parameters to send
        }});
        RightNow.Ajax.makeRequest(this.data.attrs.default_ajax_endpoint, eventObj.data, {
            successHandler: this.default_ajax_endpointCallback,
            scope:          this,
            data:           eventObj,
            json:           true
        });
    },

    /**
     * Handles the AJAX response for `default_ajax_endpoint`.
     * @param {object} response JSON-parsed response from the server
     * @param {object} originalEventObj `eventObj` from #getDefault_ajax_endpoint
     */
    default_ajax_endpointCallback: function(response, originalEventObj) {
        // Handle response
    }
});