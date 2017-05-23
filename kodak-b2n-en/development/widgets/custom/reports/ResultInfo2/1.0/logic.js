RightNow.namespace('Custom.Widgets.reports.ResultInfo2');
Custom.Widgets.reports.ResultInfo2 = RightNow.Widgets.ResultInfo.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.ResultInfo#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();
        },

        /**
         * Overridable methods from ResultInfo:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _onReportChanged: function(type, args)
        // _updateSearchResults: function(options)
        // _determineNewResults: function(eventObject)
        // _reportCombinedResults: function(evt, args)
        // _watchSearchFilterChange: function(evt, args)
        _updateSearchResults: function(options)
    {

        options = options || {};
        var noResultsDiv = this.Y.one(this.baseSelector + "_NoResults"),
            resultsDiv = this.Y.one(this.baseSelector + "_Results"),
            searchTermToDisplay = options.searchTermToDisplay,
            displayedNoResultsMsg = false;

        if(noResultsDiv)
        {
            if(this.data.js.totalResults === 0 && options.userSearchedOn && (!options.topics || options.topics.length === 0))
            {
                noResultsDiv.set('innerHTML', this.data.attrs.label_no_results + "<br/><br/>" + this.data.attrs.label_no_results_suggestions)
                            .removeClass('rn_Hidden');
                displayedNoResultsMsg = true;
            }
            else
            {
                //RightNow.UI.hide(noResultsDiv);
            }
        }
        if(resultsDiv || 1)
        {
            //this.data.js.firstResult=(this.data.js.firstResult)?this.data.js.firstResult:1;
            //this.data.js.lastResult=(this.data.js.lastResult)?this.data.js.lastResult:this.data.attrs.per_page; 

            if(this.data.js.totalResults){
            displayedNoResultsMsg=0;
            options.truncated=0;
            }
            else{
                displayedNoResultsMsg=1;
                options.truncated=1;
            }

            if(!displayedNoResultsMsg && !options.truncated)
            {
                resultsDiv.set('innerHTML', (searchTermToDisplay && searchTermToDisplay.length > 0)
                    ? RightNow.Text.sprintf(this.data.attrs.label_results_search_query, this.data.js.firstResult, this.data.js.lastResult, this.data.js.totalResults, searchTermToDisplay)
                    : RightNow.Text.sprintf(this.data.attrs.label_results, this.data.js.firstResult, this.data.js.lastResult, this.data.js.totalResults));
                RightNow.UI.show(resultsDiv);

            }
            else
            { 
                //RightNow.UI.hide(resultsDiv);
				document.getElementsByClassName('rn_Results')[0].innerHTML="Results <span>0</span> - <span>0</span> of <span>0</span>";
            }
        }
    }

    },



    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});