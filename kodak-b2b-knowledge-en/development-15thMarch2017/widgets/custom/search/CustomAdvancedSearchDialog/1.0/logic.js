RightNow.namespace('Custom.Widgets.search.CustomAdvancedSearchDialog');
Custom.Widgets.search.CustomAdvancedSearchDialog = RightNow.Widgets.AdvancedSearchDialog.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.SearchButton#constructor.
         */
        constructor: function() {

            // Call into parent's constructor
            this.parent();
           
        },
        _openDialog:function(evt){
        
         this.parent();
         document.getElementById('rnDialog1_c').style.display="block";
         this._dialog.show();


        },
        _performSearch: function() {

            
        
        document.getElementById('rn_SearchButton2OnChange_10_SubmitButton').click();
        document.getElementById('rnDialog1_c').style.display="none";
        this._dialog.hide();
        
    },
    _cancelFilters: function() {
        if(this._dialogClosed) return;

        this._closeDialog();
        this.searchSource().fire("reset", new RightNow.Event.EventObject(this, {data: {name: "all"}, filters: {report_id: this.data.attrs.report_id}}));
    },

    /**
    * Closes the dialog
    */
    _closeDialog: function() {
        this._dialogClosed = true;
        if(this._dialog){
            document.getElementById('rnDialog1_c').style.display="none";
            this._dialog.hide();
        }
    }
    
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});