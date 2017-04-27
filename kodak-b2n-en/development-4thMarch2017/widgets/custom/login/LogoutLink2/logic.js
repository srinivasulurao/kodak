RightNow.Widget.LogoutLink2 = function(data, instanceID){
    this.data = data;
    this.instanceID = instanceID;
    if(document.getElementById("rn_" + this.instanceID))
    {
        YAHOO.util.Event.addListener("rn_" + this.instanceID + "_LogoutLink", "click", this._onLogoutClick, null, this);
        RightNow.Event.subscribe("evt_logoutResponse", this._onLogoutCompleted, this);
    }
};

RightNow.Widget.LogoutLink2.prototype = {
    /**
     * Event handler for when logout has occured
     * @param {String} type Event name
     * @param {Object} arg Event arguments
     */
    _onLogoutCompleted: function(type, arg)
    {
        var result = arg[0],
            Url = RightNow.Url,
            eventData = arg[1];
        if(result.success === 1 && !RightNow.UI.Form.logoutInProgress)
        {
        	this._deleteResourcesCookie('cp_resources', '/', 'kodak.com');

            RightNow.UI.Form.logoutInProgress = true;
            if(this.data.js && this.data.js.redirectLocation)
            {
                Url.navigate(this.data.js.redirectLocation, true);
            }
            else

            {
                if(result.socialLogout)
                    Url.navigate(result.socialLogout, true);
                else if(this.data.attrs.redirect_url === '')
                    Url.navigate(result.url, true);
                else
                    Url.navigate(this.data.attrs.redirect_url + result.session, true);
            }
        }
    },

    _getResourcesCookie : function getCookie(c_name)
    {
    	if (document.cookie.length>0)
    	  {
    	  c_start=document.cookie.indexOf(c_name + "=");
    	  if (c_start!=-1)
    	    {
    	    c_start=c_start + c_name.length+1;
    	    c_end=document.cookie.indexOf(";",c_start);
    	    if (c_end==-1) c_end=document.cookie.length;
    	    return unescape(document.cookie.substring(c_start,c_end));
    	    }
    	  }
    	return null;
    },
    
    _deleteResourcesCookie : function Delete_Cookie( name, path, domain ) {
    	if ( this._getResourcesCookie( name ) ) 
    	{ document.cookie = name + "=" +
    		( ( path ) ? ";path=" + path : "") +
    		( ( domain ) ? ";domain=" + domain : "" ) +
    		";expires=Thu, 01-Jan-1970 00:00:01 GMT";
//    	  alert("cp_resources cookie has been expired");
    	}
    },

    /**
     * Event handler for when logout is clicked.
     */
    _onLogoutClick: function()
    {
        var eo = new RightNow.Event.EventObject();
        eo.w_id = this.instanceID;
        eo.data.currentUrl = window.location.pathname;
        eo.data.redirectUrl = this.data.attrs.redirect_url;
        RightNow.Event.fire("evt_logoutRequest", eo);
    }
};
