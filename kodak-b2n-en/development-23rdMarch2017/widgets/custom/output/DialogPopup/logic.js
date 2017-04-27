RightNow.Widget.DialogPopup = function (data, instanceID) {
    //Data object contains all widget attributes, values, etc.
    this.data = data;
    this.instanceID = instanceID;
	

	simpledialog1 = new YAHOO.widget.SimpleDialog("simpledialog1", 
			 { width: this.data.attrs.width,
			   fixedcenter: true,
			   visible: false,
			   draggable: false,
			   close: true,
			   text: this.data.attrs.message,
			   icon: YAHOO.widget.SimpleDialog.ICON_INFO,
			   constraintoviewport: true,
			   buttons: [ { text:"Yes", handler:this._handleYes, isDefault:true },
						  { text:"No",  handler:this._handleNo } ]
			 } );
    //var msg = RightNow.UI.Dialog.messageDialog(this.data.attrs.message, {icon : "INFO", width: this.data.attrs.width, title: this.data.attrs.title});

    simpledialog1.setHeader(this.data.attrs.title);
    simpledialog1.render(document.body);
	simpledialog1.show();

};
RightNow.Widget.DialogPopup.prototype = {
    //Define any widget functions here

    _init: function () {
	
	   var msg = "msg";
    },
	
	// Define various event handlers for Dialog
 _handleYes: function() {
  	this.hide();
},

 _handleNo: function() {
	this.hide();
	RightNow.Url.navigate("/app/home", false);
}

};

