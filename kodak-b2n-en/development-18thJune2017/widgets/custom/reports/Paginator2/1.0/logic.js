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
		getRequestedPage:function(){
			current_url=window.location.href;
			page_url=current_url.split("page/");
			page=page_url[1].split("/");
			return page[0];
			
		},		
        _targetBlank:function(){
			links=document.querySelectorAll(".rn_Content a").length;
			for(i=0;i<links;i++){
				document.querySelectorAll(".rn_Content a")[i].target="_blank";
			}
			
		},			
        constructor: function() {
            // Call into parent's constructor
            this.parent();  
            
            //My Fantastic Customization, this should never fail.
			prod_selected=this.data.attrs.prod_selected;
		    cat_selected=this.data.attrs.cat_selected;
			
			var text_entered=document.querySelectorAll(".rn_KeywordTextModified input[type='text']")[0].value;
			this.loss_calc=(text_entered)?parseInt(Math.floor((this.data.attrs.per_page/10))):0;
			
			//Take any of the entity and start the search.
			if(prod_selected || cat_selected){
				entity=(prod_selected)?prod_selected:cat_selected;
				prod_cat_levels=document.getElementsByClassName('ygtvlabel').length;
				entity_found=0;
				for(i=0;i<prod_cat_levels;i++){
					if(document.getElementsByClassName('ygtvlabel')[i].innerText==entity){
						document.getElementsByClassName('ygtvlabel')[i].click(); //Search by product or Category.
						entity_found=1;
						break;
					}
				}
				
			} 
			document.getElementsByClassName('rn_NoResults')[0].classList.add("rn_Hidden");
			this._eo.filters.page=1;
			this._eo.filters.per_page=(this._eo.filters.page==1)?parseInt(this.data.attrs.per_page+this.loss_calc):parseInt(this.data.attrs.per_page);
			this._eo.filters.report_id=this.data.attrs.report_id;
            if (RightNow.Event.fire("evt_switchPagesRequest", this._eo)) {
                this.searchSource().fire("appendFilter", this._eo).fire("search", this._eo);
            }
			
			//document.getElementsByClassName('rn_CurrentPage')[0].click();
			this._targetBlank();
			
        },
        _onPageChange: function(evt, pageNumber){
			
			
            evt.preventDefault();
			
			var text_entered=document.querySelectorAll(".rn_KeywordTextModified input[type='text']")[0].value;
			this.loss_calc=(text_entered)?parseInt(Math.floor((this.data.attrs.per_page/10))):0;
			
            /* if(this._currentlyChangingPage || !pageNumber || pageNumber === this._currentPage)
                return; */
            document.getElementsByClassName('rn_NoResults')[0].classList.add("rn_Hidden");
			
            this._currentlyChangingPage = true;
            pageNumber = (pageNumber < 1) ? 1 : pageNumber;
            this._eo.filters.page = this._currentPage = pageNumber;

            this._eo.filters.per_page=(this._eo.filters.page==1)?parseInt(this.data.attrs.per_page+this.loss_calc):parseInt(this.data.attrs.per_page);
            if (RightNow.Event.fire("evt_switchPagesRequest", this._eo)) {
                this.searchSource().fire("appendFilter", this._eo).fire("search", this._eo);
            }
			
			
        },
        _onReportChanged: function(type, args){
            
            var newData = args[0];
            newData = newData.data;
			
			//console.log(newData);

        // My Customization.

        if(this._currentPage==1){
	    var subjected_number=Math.ceil(newData.total_num/newData.per_page);			
        newData.total_pages=(this._currentPage==1)?subjected_number:subjected_number+this.loss_calc;
        newData.truncated=0;
        }

        if(args[0].filters.report_id == this.data.attrs.report_id)
        {
            this._currentPage = newData.page;
            var totalPages = newData.total_pages;

            if(totalPages < 2 || newData.truncated)
            {
                RightNow.UI.show(this._instanceElement);
				
				 if(newData.total_pages ==1)
                    RightNow.UI.hide(this._instanceElement);  
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
        //var original_url=window.location.href;
        //ph_url=original_url.split("list");
        //window.history.pushState("","Find Answers",ph_url[0]+"list");
		
		this._targetBlank();
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