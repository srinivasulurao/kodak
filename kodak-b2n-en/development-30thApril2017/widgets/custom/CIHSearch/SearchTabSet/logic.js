/* Originating Release: February 2012 */
RightNow.Widget.SearchTabSet = function(data, instanceID) {
    this.data = data;
    this.instanceID = instanceID;
 

    this.mySearchTabView = new YAHOO.widget.TabView("searchtabset");

    RightNow.Event.subscribe('evt_tab', this._setTabFocus, this);
   
};

RightNow.Widget.SearchTabSet.prototype = {


     _setTabFocus: function (evt, args) {

       this.mySearchTabView.selectTab(0);
   },


};


