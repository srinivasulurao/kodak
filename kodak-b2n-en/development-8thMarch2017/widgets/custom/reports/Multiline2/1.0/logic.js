RightNow.namespace('Custom.Widgets.reports.Multiline2');
Custom.Widgets.reports.Multiline2 = RightNow.Widgets.Multiline.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.Multiline#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();
            //this._setFilter();
        },

        /**
         * Overridable methods from Multiline:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _setFilter: function()
        // _searchInProgress: function(evt, args)
        // _setLoading: function(loading)
        // _onReportChanged: function(type, args)
        // _displayDialogIfError: function(error)
        // _updateAriaAlert: function(text)
_setFilter: function() {
        var eo = new RightNow.Event.EventObject(this, {filters: {
            token: this.data.js.r_tok,
            format: this.data.js.format,
            report_id: this.data.attrs.report_id,
            allFilters: this.data.js.filters
        }});
        eo.filters.format.parmList = this.data.attrs.add_params_to_url;
        this.searchSource().fire("setInitialFilters", eo);

        

    },
    _searchInProgress: function(evt, args) {
       var params = args[1];

       if(!params || !params.newPage)
           this._setLoading(true);
    },
         _onReportChanged: function(type, args){
                   var newdata = args[0].data,
                    ariaLabel, firstLink,
                    newContent = "";

                    //console.log(newdata);

                    //My Customizations.
                    // var attribs=this.data.attrs;
                    // newdata.per_page=attribs.per_page;
                    // newdata.end_num=parseInt(newdata.page)*parseInt(attribs.per_page);

                    //console.log(newdata);


                this._displayDialogIfError(newdata.error);

                if (!this._contentDiv) return;

                if(newdata.total_num > 0) {
                    ariaLabel = this.data.attrs.label_screen_reader_search_success_alert;
                    newdata.hide_empty_columns = this.data.attrs.hide_empty_columns;
                    newdata.hide_columns = this.data.js.hide_columns;
                    newContent = new EJS({text: this.getStatic().templates.view}).render(newdata);
                }
                else {
                    ariaLabel = this.data.attrs.label_screen_reader_search_no_results_alert;
                }

                this._updateAriaAlert(ariaLabel);
                this._contentDiv.set("innerHTML", newContent);

                if (this.data.attrs.hide_when_no_results) {
                    this.Y.one(this.baseSelector)[((newContent) ? 'removeClass' : 'addClass')]('rn_Hidden');
                }

                this._setLoading(false);
                RightNow.Url.transformLinks(this._contentDiv);

                if (newdata.total_num && (firstLink = this._contentDiv.one('a'))) {
                    //focus on the first result
                    firstLink.focus();
                }
        }
    },

    /**
     * Sample widget method.
     */
    methodName: function() {
       
    }
});