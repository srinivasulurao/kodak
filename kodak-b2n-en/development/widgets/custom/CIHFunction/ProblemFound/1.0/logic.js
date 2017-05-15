RightNow.namespace('Custom.Widgets.CIHFunction.ProblemFound');
Custom.Widgets.CIHFunction.ProblemFound = RightNow.Widgets.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
        /**
         * Overrides RightNow.Widgets.ProductCategoryInput#constructor.
         */
        constructor: function() {
            // Call into parent's constructor
			RightNow.Event.subscribe('evt_populateProductCategoryLinking', this._getProductCategoryHireData, this);
        },
		_getProductCategoryHireData:function(type,args){ 
			postData=new Array();
			postData['fired_products']=args[0].data.value;
			
			RightNow.Ajax.makeRequest("/cc/cihprodcatCustom/getFiredProductCategoryLinks", postData, {


                data: { eventName: "evt_categoriesRetrieveResponse" },


                successHandler: function (response) {

                 //First hide all the categories.
				     var groundPanel=(this.data.attrs.panel_name=="accordionRepairRequest2")?"#panelRepairRequest2":"#panelRepairRequest";;
				     var groundPanel=(this.data.attrs.panel_name=="accordionRepairRequest2")?"#panelRepairRequest2":"#panelRepairRequest";;
					 var pf_length=document.querySelectorAll(groundPanel+" .rn_show").length;
					 //pf_length=10;
					 for(i=0;i<parseInt(pf_length);i++){
						document.querySelectorAll(groundPanel+" .rn_Show")[i].classList.add('rn_Hidden');
						document.querySelectorAll(groundPanel+" .rn_Show")[i].classList.remove('rn_Show');
					 }
				
				//Now Show the list.
				
				    var cat_list_show=JSON.parse(response.responseText);
					for(j=0;j<=cat_list_show.length;j++){ 
					   document.querySelectorAll(groundPanel+" .cat_"+cat_list_show[j])[0].classList.add('rn_Show');
					   document.querySelectorAll(groundPanel+" .cat_"+cat_list_show[j])[0].classList.remove('rn_Hidden');
					}
				
                },
                failuerHandler: function (response) {
                    
                },
                scope: this
            });
			
			
		},
        selectNode(node){
        this.parent(node);
         //Srini's Customization.
         console.log(this.data.js);
         var selected_cat=node.valueChain.join(",");   
         //document.querySelectorAll("[name='cat']")[0].value=selected_cat;
        },

        /**
         * Overridable methods from ProductCategoryInput:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _initializeHint: function()
        // buildPanel: function ()
        // _resetProductCategoryMenu: function()
        // _updatePermissionedHierData: function (dataType)
        // displaySelectedNodesAndClose: function(focus, fireSelectionEvent)
        // selectNode: function(node)
        // getSubLevelRequest: function (expandingNode)
        // getSubLevelRequestEventObject: function(expandingNode)
        // getSubLevelResponse: function(type, args)
        // _setButtonClick: function()
        // _onValidate: function(type, args)
        // _createHintElement: function(visibility)
        // _toggleHint: function(hideOrShow)
        // _realignHint: function(delay)
        // swapLabel: function(container, requiredLevel, label, template)
        // updateRequiredLevel: function(evt, constraint)
        // _checkSelectionErrors: function()
        // _removeErrorMessages: function()
        // _displayErrorMessage: function(message, currentNode)
    

    /**
     * Sample widget method.
     */
    methodName: function() {

    }
});