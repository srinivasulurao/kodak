RightNow.namespace('Custom.Widgets.CIHSearch.SearchTabSet');
Custom.Widgets.CIHSearch.SearchTabSet = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
        this.Y.on("click", this.showHide, "#left",this,'#custSearch','#prodSearch','#left');
        this.Y.on("click", this.showHide, "#right",this,'#prodSearch','#custSearch','#right');

        RightNow.Event.subscribe('evt_tab', this._setTabFocus, this);
		
		this.Y.all(".pid_tabs").on("click",this.show_hide_pid_tabs,this);
		
		for(i=0;i<document.getElementsByClassName('pid_tabs').length;i++){
			tab=document.getElementsByClassName('pid_tabs')[i];
			display_content=tab.getAttribute('display_content');
			document.getElementById(display_content).style.display="none";
			tab.classList.remove("selected");
		}
    },
	show_hide_pid_tabs:function(evt,eo){
		
		//First Disable all the content display divs.
		
		for(i=0;i<document.getElementsByClassName('pid_tabs').length;i++){
			tab=document.getElementsByClassName('pid_tabs')[i];
			display_content=tab.getAttribute('display_content');
			document.getElementById(display_content).style.display="none";
			tab.classList.remove("selected");
		}
		
		//Now Show the correct content with selected tab.
		target_id=evt._currentTarget.id;
		
		document.getElementById(target_id).classList.add('selected');
		dc=document.getElementById(target_id).getAttribute('display_content');
		document.getElementById(dc).style.display="block";
		
	},
    showHide: function(evt,arg1,arg2,arg3) {
     //Remove Selected.
     this.Y.one("#left").removeClass("selected");
     this.Y.one("#right").removeClass("selected");

     // Hide Both.
     this.Y.one(arg1).hide();
     this.Y.one(arg2).hide();

     //Start Operation.
     this.Y.one(arg3).addClass("selected");
     this.Y.one(arg1).show();
    },
    _setTabFocus:function(evt,args){
      this.showHide(evt,'#custSearch','#prodSearch','#left');
    }

});