RightNow.Widget.WaitingPanel = function (data, instanceID) {
    //Data object contains all widget attributes, values, etc.
    this.data = data;
    this.instanceID = instanceID;
	
    YAHOO.namespace("request.container");
	
	var content = document.getElementById("rn_Container");
        
    content.innerHTML = "";
		
	var requestArgs = {
        divId: 'responseContainer',
        loadingLabel: 'Fetching',
        loadingBody: '',
        target: '/app/account/request_ibase'
    };

	var waitContainer = this._getWaitContainer(requestArgs);
    waitContainer.show();

    var callback = {
            success : function(o) {
                content.innerHTML = o.responseText;
                content.style.visibility = "visible";
                YAHOO.request.container.wait.hide();

            },
            failure : function(o) {
              content.innerHTML = o.responseText;
                content.style.visibility = "visible";
                content.innerHTML = "CONNECTION FAILED!";
                YAHOO.request.container.wait.hide();

            }

	}   
    var conn = YAHOO.util.Connect.asyncRequest("GET", "/app/account/ibase", callback);


};
RightNow.Widget.WaitingPanel.prototype = {
    //Define any widget functions here

    _init: function () {
	
	   var msg = ""; 
	},
	
	_getWaitContainer: function(args) {
		
		YAHOO.request.container.wait = new YAHOO.widget.Panel("wait",
			{ width: "400px",
			  fixedcenter: true,
			  visible: false,
			  draggable: false,
			  close: false,
			  modal: true,
			  zindex:4
			}
		);
		
   	    YAHOO.request.container.wait.setHeader(this.data.js.loadingmessage);
        YAHOO.request.container.wait.setBody("<img src=\"/euf/assets/images/rel_interstitial_loading.gif\"/>");
        YAHOO.request.container.wait.render(document.body);
 
		return YAHOO.request.container.wait;
    }
	
    
};

