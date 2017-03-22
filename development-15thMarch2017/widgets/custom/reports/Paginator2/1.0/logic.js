RightNow.namespace('Custom.Widgets.reports.Paginator2');
Custom.Widgets.reports.Paginator2 = RightNow.Widgets.Paginator.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.Paginator#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
            this.parent();
            this.searchSource().fire("appendFilter", this._eo).fire("search", this._eo);
            
        },
        _onReportChanged: function(type, args){

            // this.parent(type,args);
            // var newData = args[0];
            // newData = newData.data;
            // current_page=newData.page;
            // alert(current_page);

            
            var newData = args[0];
            newData = newData.data;

        newData.total_pages=Math.ceil(parseInt(parseInt(newData.total_num)/parseInt(newData.per_page)));
        newData.truncated=0;

        // console.log("Current Page",this._currentPage);
        //                 console.log("startPage",startPage);
        //                 console.log("endPage",totalPages);

        if(args[0].filters.report_id == this.data.attrs.report_id)
        {
            this._currentPage = newData.page;
            var totalPages = newData.total_pages;

            if(totalPages < 2 || newData.truncated)
            {
                //RightNow.UI.hide(this._instanceElement);
            }
            else
            {
                //update all of the page links
                var pagesContainer = this.Y.one(this.baseSelector + " ul");
                if(pagesContainer)
                {
                    pagesContainer.set('innerHTML', "");

                    var startPage, endPage;
                    if(totalPages > this.data.attrs.maximum_page_links)
                    {


                        var split = Math.round(this.data.attrs.maximum_page_links / 2);
                        if(this._currentPage <= split)
                        {
                            startPage = 1;
                            endPage = this.data.attrs.maximum_page_links;
                        }
                        else
                        {
                            var offsetFromMiddle = this._currentPage - split;
                            var maxOffset = offsetFromMiddle + this.data.attrs.maximum_page_links;
                            if(maxOffset <= newData.total_pages)
                            {
                                startPage = 1 + offsetFromMiddle;
                                endPage = maxOffset;
                            }
                            else
                            {
                                startPage = newData.total_pages - (this.data.attrs.maximum_page_links - 1);
                                endPage = newData.total_pages;
                            }
                        }
 
                    }
                    else
                    {
                        startPage = 1;
                        endPage = totalPages;

                    }

                    pagesContainer.appendChild(this._backButton);


                    for(var i = startPage, link, titleString; i <= endPage; i++)
                    {
                        if(i === this._currentPage)
                        {
                            link = this.Y.Node.create("<span/>").addClass("rn_CurrentPage")
                                .set('innerHTML', i);
                        }
                        else if (this._shouldShowPageNumber(i, this._currentPage, endPage))
                        {
                            link = this.Y.Node.create("<a/>").set('id', this.baseDomID + "_PageLink_" + i)
                                .set('href', this.data.js.pageUrl + i)
                                .set('innerHTML', i + '<span class="rn_ScreenReaderOnly">' + RightNow.Text.sprintf(this.data.attrs.label_page, i, totalPages) + '</span>');
                            titleString = this.data.attrs.label_page;
                            if(titleString)
                            {
                                titleString = titleString.replace(/%s/, i).replace(/%s/, newData.total_pages);
                                link.set('title', titleString);
                            }
                        }
                        else if (this._shouldShowHellip(i, this._currentPage, endPage))
                        {
                            link = this.Y.Node.create("<span/>")
                                    .set('class', 'rn_PageHellip')
                                    .set('innerHTML', "&hellip;");
                        }
                        else {
                            continue;
                        }
                        pagesContainer.appendChild(this.Y.Node.create("<li/>").append(link));
                        link.on("click", this._onPageChange, this, i);
                    }

                    pagesContainer.appendChild(this._forwardButton);

                    RightNow.UI.show(this._instanceElement);
                }
            }
            //update the back button
            if(this._backButton)
            {
                if(newData.page > 1)
                    this._backButton.removeClass("rn_Hidden").set('href', this.data.js.pageUrl + (this._currentPage - 1));
                else
                    RightNow.UI.hide(this._backButton);
            }
            //update the forward button
            if(this._forwardButton)
            {
                if(newData.total_pages > newData.page)
                    this._forwardButton.removeClass("rn_Hidden").set('href', this.data.js.pageUrl + (this._currentPage + 1));
                else
                    RightNow.UI.hide(this._forwardButton);
            }
            this._cloneForwardAndBackwardButton();
        }
        this._currentlyChangingPage = false;
        
        /* URL Manipulation */    
        var original_url=window.location.href;
        ph_url=original_url.split("list");
        window.history.pushState("","Find Answers",ph_url[0]+"list");
        }

        /**
         * Overridable methods from Paginator:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _onPageChange: function(evt, pageNumber)
        // _onDirection: function(evt, isForward)
        // _onReportChanged: function(type, args)
        // _shouldShowHellip: function(pageNumber, currentPage, endPage)
        // _shouldShowPageNumber: function(pageNumber, currentPage, endPage)
        // _cloneForwardAndBackwardButton: function()
    },

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});