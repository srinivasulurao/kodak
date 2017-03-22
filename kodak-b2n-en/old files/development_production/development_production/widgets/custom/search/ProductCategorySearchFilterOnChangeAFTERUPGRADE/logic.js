RightNow.Widget.ProductCategorySearchFilterOnChange = function(data, instanceID)
{
    this.data = data,
    this.instanceID = instanceID;
    this._eo = new RightNow.Event.EventObject();
    this._currentIndex = 0;
    this._noValueNodeIndex = 0;
    this._displayField = document.getElementById("rn_" + this.instanceID + "_" + this.data.attrs.filter_type + "_Button");
    this._displayFieldVisibleText = document.getElementById("rn_" + this.instanceID + "_ButtonVisibleText");
    this._accessibleView = document.getElementById("rn_" + this.instanceID + "_Links");
    
    if(!this._displayField) return;

    RightNow.Event.subscribe("evt_getFiltersRequest", this._getFiltersRequest, this);
    RightNow.Event.subscribe("evt_menuFilterGetResponse", this._getSubLevelResponse, this);
    RightNow.Event.subscribe("evt_accessibleTreeViewGetResponse", this._getAccessibleTreeViewResponse, this);
    RightNow.Event.subscribe("evt_reportResponse", this._onReportResponse, this);
    RightNow.Event.subscribe("evt_resetFilterRequest", this._onResetRequest, this);

    //toggle panel on/off when button is clicked
    YAHOO.util.Event.addListener(this._displayField, "click", this._toggleProductCategoryPicker, null, this);
    YAHOO.util.Event.addListener("rn_" + this.instanceID + "_LinksTrigger", "click", this._toggleAccessibleView, null, this);

    this._initializeFilter();

    //build menu panel
    this._panel = new YAHOO.widget.Panel("rn_" + this.instanceID + "_Tree", { close:false, width:"235px", visible:false, constraintoviewport:true });
    this._panel.setHeader("");
    this._panel.render();
    YAHOO.util.Dom.setStyle(this._panel.innerElement, "overflow-y", "auto");

    if(this.data.js.defaultData)
        this._buildTree();

    this._toggleProductCategoryPicker(null);
};

RightNow.Widget.ProductCategorySearchFilterOnChange.prototype = {
    /**
    * Constructs the YUI Treeview widget for the first time with initial data returned
    * from the server. Pre-selects and expands data that is expected to be populated.
    */
    _buildTree : function()
    {
        this._initializeKeyBindings();
        this._tree = new YAHOO.widget.TreeView("rn_" + this.instanceID + "_Tree");
        
        if(this._tree)
        {
//            this._tree.singleNodeHighlight = true; 
            this._tree.setDynamicLoad(RightNow.Event.createDelegate(this, this._getSubLevelRequest));
            var root = this._tree.getRoot(),
                  defaultValues = false;

            for(var i = 0, node, length = this.data.js.hierData.length; i < length; i++)
            {
                for(var j = 0, nodeData; j < this.data.js.hierData[i].length; j++)
                {
                    //if this node has a parent, it needs to be retrieved so that this node
                    //can properly attach itself to it
                    nodeData = this.data.js.hierData[i][j];
                    if(i !== 0 && nodeData.parentID)
                        root = this._tree.getNodeByProperty("hierValue", nodeData.parentID);

                    node = new YAHOO.widget.MenuNode(nodeData.label, root);
                    node.hierValue = nodeData.value;
                    node.href = 'javascript:void(0);';
                    if(nodeData.selected)
                    {
                        //if it should be pre-selected by default
                        defaultValues = true;
                        this._currentIndex = node.index;
                    }
                    if(!nodeData.hasChildren)
                    {
                        //if it doesn't have children then turn off the +/- icon
                        //and notify that the node is already loaded
                        node.dynamicLoadComplete = true;
                        node.iconMode = 1;
                    }
                }
                root.loadComplete();
            }
            var noValueNode = this._tree.getRoot().children[0];
            noValueNode.isLeaf = true;
            this._noValueNodeIndex = noValueNode.index;

            this._tree.subscribe("enterKeyPressed", this._enterPressed, null, this);
            this._tree.subscribe('clickEvent', this._selectNode, null, this);
            //scroll container to 20px above expanded node
            this._tree.subscribe('expandComplete', function(node) {
                    this._panel.innerElement.scrollTop = node.getEl().offsetTop - 20;
            }, null, this);
            this._tree.render();
            //this._tree.collapseAll();
            YAHOO.util.Dom.setStyle("rn_" + this.instanceID + "_Tree", "display", "block");
            if(defaultValues)
                this._displaySelectedNodesAndClose(false);
        }
    },

    /**
    * Creates and displays a dialog consisting of an accessible list of items.
    */
    _displayAccessibleDialog: function()
    {
        //build tree for the first time
        if(!this._tree)
            this._buildTree();
        // If the dialog doesn't exist, create it.  (Happens on first click).
        if(!(this._dialog))
        {
            // Set up buttons with handler functions.
            var handleDismiss = function()
            {
                this.hide();
            };

            this._buttons = [ { text: RightNow.Interface.getMessage("CANCEL_CMD"), handler: handleDismiss, isDefault: false} ];
            // Create the dialog.
            YAHOO.util.Dom.removeClass(this._accessibleView, "rn_Hidden")
            this._dialog = RightNow.UI.Dialog.actionDialog(this.data.attrs.label_nothing_selected, this._accessibleView, {"buttons": this._buttons, "width": "400px"});
        }
        else
        {
            var currentlySelectedSpan = document.getElementById("rn_" + this.instanceID + "_IntroCurrentSelection");
            var introLink = document.getElementById("rn_" + this.instanceID + "_Intro");
            if(currentlySelectedSpan && introLink)
            {
                var currentNode = this._tree.getNodeByIndex(this._currentIndex);
                if(!currentNode)
                {
                    currentNode = {};
                    currentNode.hierValue = 0;
                }
                var localInstanceID = this.instanceID;
                introLink.onclick = function(){document.getElementById("rn_" + localInstanceID + "_AccessibleLink_" + currentNode.hierValue).focus();};
                var selectedNodes = this._getSelectedNodesMessage();
                currentlySelectedSpan.innerHTML = RightNow.Text.sprintf(RightNow.Interface.getMessage("SELECTION_PCT_S_ACTIVATE_LINK_JUMP_MSG"), selectedNodes);
            }
        }

        YAHOO.lang.later(1000, this._dialog, 'show');
        return false;
    },

    /**
    * Toggles accessible view.
    */
    _toggleAccessibleView: function(e)
    {
        if(this._dataType === "categories" && this.data.js.linkingOn)
            this._eo.data.linkingProduct = RightNow.UI.Form.currentProduct;

        if(this._flatTreeViewData)
            this._displayAccessibleDialog();
        else
            RightNow.Event.fire("evt_accessibleTreeViewRequest", this._eo);
    },

    /**
    * Listens to response from the server and constructs an HTML tree according to
    * the flat data structure given.
    * @param e String Event name
    * @param args Object Event arguments
    */
    _getAccessibleTreeViewResponse: function(e, args)
    {
        if(args[0].data.hm_type != this._eo.data.hm_type)
            return;
        var evtObj = args[0];
        if(evtObj.data.data_type == this._dataType)
        {
            this._flatTreeViewData = evtObj.data.accessibleLinks;
            //add the No Value node
            //add the No Value node
            var noValue = {0: RightNow.Interface.getMessage("NO_VAL_LBL"),
                           1: 0,
                           hier_list: 0,
                           level: 0};
            if(!YAHOO.lang.isArray(this._flatTreeViewData))
            {
                //convert object to array because objects don't support unshift drop off the nonNumeric values
                var tempArray = [];
                for(var i in this._flatTreeViewData)
                    if(!isNaN(parseInt(i)))
                        tempArray[i] = this._flatTreeViewData[i];    
                
                this._flatTreeViewData = tempArray;
            }
            this._flatTreeViewData.unshift(noValue);
            var htmlList = "<p><a href='javascript:void(0)' id='rn_" + this.instanceID + "_Intro'" + 
            "onclick='document.getElementById(\"rn_" + this.instanceID + "_AccessibleLink_" + noValue[1] +
            "\").focus();'>" + RightNow.Text.sprintf(RightNow.Interface.getMessage("PCT_S_LINKS_DEPTH_ANNOUNCED_MSG"), this.data.attrs.label_input) + 
             
            " <span id='rn_" + this.instanceID + "_IntroCurrentSelection'>" + RightNow.Text.sprintf(RightNow.Interface.getMessage("SELECTION_PCT_S_ACTIVATE_LINK_JUMP_MSG"), noValue[0]) + "</span></a></p>";
            //loop through each hier_item to figure out nesting structure
            var previousLevel = -1;
            for(var i in this._flatTreeViewData)
            {
                var item = this._flatTreeViewData[i];
                //print down html
                if(item.level > previousLevel)
                    htmlList += "<ol>";

                //print up html
                while(item.level < previousLevel)
                {
                    htmlList += "</li></ol>";
                    previousLevel--;
                }
                //print across html
                if(item.level === previousLevel)
                    htmlList += "</li>";
                //print current node
                htmlList += "<li>" + '<a href="javascript:void(0)" id="rn_' +  this.instanceID + '_AccessibleLink_' + item[1] + '" class="rn_AccessibleHierLink" hierList="' + item['hier_list'] + '">' + item[0] + '</a>';
                previousLevel = item.level;
            }
            //close list
            for(var i = previousLevel; i >= 0; --i)
                htmlList += "</li></ol>";
            
            htmlList += "<div id='rn_" + this.instanceID + "_AccessibleErrorLocation'></div>";
            this._accessibleView.innerHTML = htmlList;
            //set up click handlers
            var allNodes = YAHOO.util.Dom.getElementsByClassName("rn_AccessibleHierLink", "a", this._accessibleView);
            YAHOO.util.Event.addListener(allNodes, "click", this._accessibleLinkClick, null, this);
            this._displayAccessibleDialog();
        }
    },
    
    /**
    * Executed when a tree item is selected from the accessible view.
    * @param e Event DOM click event
    */
    _accessibleLinkClick: function(e)
    {
        //basically transfer this click to the visible control
        //find the node in this._tree. If it's not there, expand it's parents until it is there.
        //call click on that node.
        var element = YAHOO.util.Event.getTarget(e);
        var hierArray = element.getAttribute("hierList").split(",");
        this._expandAndCreateNodes(hierArray);
        return false;
    },

    /**
    * Shows / hides Panel containing TreeView widget
    * Shows when user clicks button and the Panel is hidden.
    * Hides when user selects a node or the Panel loses focus.
    * @param event Event The click event
    */
    _toggleProductCategoryPicker: function(event)
    {
        //build tree for the first time
        if(!this._tree)
            this._buildTree();
        //show panel
        if(this._panel.cfg.getProperty("visible") === false)
        {
            //Set the panel to line up with the button (by default it's left-aligned in the dom)
            if(!this._toggleProductCategoryPicker._buttonPos || this._toggleProductCategoryPicker._buttonPos !== this._panel.cfg.getProperty("x"))
            {
                this._toggleProductCategoryPicker._buttonPos = YAHOO.util.Dom.getX(this._displayField);
                this._panel.cfg.setProperty("x", this._toggleProductCategoryPicker._buttonPos);
                if(YAHOO.env.ua.ie) {
                    this._panel.cfg.setProperty("y", YAHOO.util.Dom.getY(this._displayField)+28);
                }
            }
            this._panel.syncPosition();
            this._panel.show();
            //focus on either the previously selected node or the first node
            var currentNode = this._tree.getNodeByIndex(this._currentIndex);
            if(currentNode && currentNode.focus)
            {
                currentNode.focus();
            }
			/*
			**  Description: 	Remove selected/highlighted item from category list
			**	Author:	     	Baljeet Singh
			**  Commented On: 	2011-04-15
				else if(this._tree.getRoot().children[0] && this._tree.getRoot().children[0].focus)
				{
					this._tree.getRoot().children[0].focus();
				}
			*/
            

            //create event listener (once)
            this._toggleProductCategoryPicker._closeListener = this._toggleProductCategoryPicker._closeListener ||
            function(event)
            {
                if(this._panel.cfg.getProperty("visible"))
                {
                    var coordinates = YAHOO.util.Event.getXY(event);
                    //return if target was the toggle button (either clicking or enter key)
                    if((event.type === "click" && YAHOO.util.Event.getTarget(event).id === this._displayField.id) || coordinates[0] === 0 && coordinates[1] === 0)
                        return;

                    coordinates = new YAHOO.util.Point(coordinates[0], coordinates[1]);
                    var panelRegion = YAHOO.util.Dom.getRegion("rn_" + this.instanceID + "_Tree"),
                          buttonRegion = YAHOO.util.Dom.getRegion(this._displayField);
                    if(panelRegion && buttonRegion && (!panelRegion.contains(coordinates) && !buttonRegion.contains(coordinates)))
                    {
                        //if click was anywhere outside of button or panel region, hide the panel
                        this._displaySelectedNodesAndClose();
                        YAHOO.util.Event.removeListener(document, this._toggleProductCategoryPicker._closeListener);
                    }
                }
            };
            YAHOO.util.Event.addListener(document, "click", this._toggleProductCategoryPicker._closeListener, null, this);
        }
        //hide panel
        else
        {
            this._displaySelectedNodesAndClose();
            YAHOO.util.Event.removeListener(document, this._toggleProductCategoryPicker._closeListener);
        }
    },

    _getSelectedNodesMessage: function()
    {
        //work back up the tree from the selected node
        this._currentIndex = this._currentIndex || 1;
        var hierValues = [],
              currentNode = this._tree.getNodeByIndex(this._currentIndex);
        while(currentNode && !currentNode.isRoot())
        {
            hierValues.push(currentNode.label);
            currentNode = currentNode.parent;
        }
        return hierValues.reverse();
    }, 

    /**
    * Displays the hierarchy of the currently selected node up to it's root node,
    * hides the panel, and focuses on the selection button (if directed).
    * @param focus Boolean Whether or not the button should be focused
    */
    _displaySelectedNodesAndClose: function(focus)
    {
//        this._panel.hide();
        //also close the dialog if it exists
        if(this._dialog && this._dialog.cfg.getProperty("visible"))
            this._dialog.hide();
        if(this._currentIndex <= this._noValueNodeIndex)
        {
            this._displayFieldVisibleText.innerHTML = this.data.attrs.label_nothing_selected;
            var description = document.getElementById("rn_" + this.instanceID + "_TreeDescription");
            if(description)
               description.innerHTML = this.data.attrs.label_nothing_selected;
        }
        else
        {
            var hierValues = this._getSelectedNodesMessage().join("<br/>"),
                field = this._displayFieldVisibleText;
            if(YAHOO.env.ua.webkit) {
                //webkit doesn't allow setting the innerHTML of the button during keypress event,
                //so set it one millisecond later...
                setTimeout(function(){field.innerHTML = hierValues;}, 1);
            }
            else {
                field.innerHTML = hierValues;
            }
            
            var description = document.getElementById("rn_" + this.instanceID + "_TreeDescription");
            if(description)
               description.innerHTML = this.data.attrs.label_screen_reader_selected + " - " + hierValues;
        }
        //don't focus if the accessible dialog is in use or was in use during this page load.
        //the acccessible view and the treeview shouldn't really be mixed
        if(focus && !this._dialog)
            try{this._displayField.focus();} catch(e){}

        //alert('tree closed...');
        if(focus) {
            RightNow.Event.fire("evt_startSearchOnChange");

        }
    },

    /**
    * Handler for when enter was pressed while focused on a node
    * Emulates mouse click
    * @param {Event} keyEvent The node's enterPressed event.
    */
    _enterPressed: function(keyEvent)
    {
        this._selectNode({node:keyEvent});
    },

    /**
    * Selected a node by clicking on its label
    * (as opposed to expanding it via the expand image).
    * @param clickEvent Event The node's click event.
    */
    _selectNode: function(clickEvent)
    {
//        this._tree.onEventToggleHighlight(clickEvent);
        this._currentIndex = clickEvent.node.index;
        this._selected = true;
        //static variable
        this._selectNode._selectedWidget = this.data.info.w_id;
        if(clickEvent.node.expanded || this._noValueNodeIndex === clickEvent.node.index)
        {
            this._eo.data.level = clickEvent.node.depth + 1;
            //setup filter data for report's filter request
            if(this._eo.data.level !== this._eo.filters.data[0].length)
            {
                //filter's been reset or user skipped a level: make sure to always pass correct values
                this._eo.filters.data[0] = [];
                var currentNode = clickEvent.node;
                while(currentNode && !currentNode.isRoot())
                {
                    this._eo.filters.data[0][currentNode.depth] = currentNode.hierValue;
                    currentNode = currentNode.parent;
                }
            }
            else
            {
                this._eo.filters.data[0][this._eo.data.level - 1] = this._eo.data.value;
                for(var i = this._eo.data.level; i < this._eo.filters.data[0].length; i++)
                    delete this._eo.filters.data[0][i];
            }
        }
        else
        {
            this._getSubLevelRequest(clickEvent.node);
            //this._tree.collapseAll();
        }
        this._displaySelectedNodesAndClose(true);
        if(clickEvent.event)
            YAHOO.util.Event.preventDefault(clickEvent.event);
        
        return false;
    },

    /**
     * Event handler when a node is expanded.
     * Requests the next sub-level of items from the server.
     * @param expandingNode Event The node that's expanding
     */
    _getSubLevelRequest: function(expandingNode)
    {
        //only allow one node at-a-time to be expanded
        if(this._nodeBeingExpanded || expandingNode.expanded) return;
        
        this._nodeBeingExpanded = true;
        this._eo.data.level = expandingNode.depth + 1;
        this._eo.data.label = expandingNode.label;
        this._currentIndex = expandingNode.index;
        this._eo.data.value = expandingNode.hierValue;
        //static variable for different widget instances but the same data type
        this._getSubLevelRequest._origRequest = this._getSubLevelRequest._origRequest || [];
        this._getSubLevelRequest._origRequest[this._dataType] = expandingNode.hierValue;

        if(this._dataType === "products")
        {
            //Set namespace global for hier menu list linking display
            RightNow.UI.Form.currentProduct = this._eo.data.value;
        }
        if(this._eo.data.value < 1 && this._eo.data.linking_on)
        {
            //prod linking
            this._eo.data.reset = true;
            if(this._eo.data.value === 0 && this._dataType === "products")
            {
                //product was set back to all: fire event for categories to re-show all
                this._eo.data.reset = false;
                var eo = new RightNow.Event.EventObject();
                eo.data = {"name" : "c", "reset" : true};
                eo.filters.report_id = this.data.attrs.report_id;
                RightNow.Event.fire("evt_resetFilterRequest", eo);
                this._nodeBeingExpanded = false;
                return;
            }
            else
            {
                this._eo.data.value = 0;
            }
        }
        else
        {
            this._eo.data.reset = false;
        }

        if(this.data.js.link_map)
        {
            //pass link map (prod linking) to EventBus for first time
            this._eo.data.link_map = this.data.js.link_map;
            this.data.js.link_map = null;
        }
        //setup filter data for report's filter request
        if(this._eo.data.level !== this._eo.filters.data[0].length)
        {
            //filter's been reset or user skipped a level: make sure to always pass correct values
            this._eo.filters.data[0] = [];
            var currentNode = expandingNode;
            while(currentNode && !currentNode.isRoot())
            {
                this._eo.filters.data[0][currentNode.depth] = currentNode.hierValue;
                currentNode = currentNode.parent;
            }
        }
        else
        {
            this._eo.filters.data[0][this._eo.data.level - 1] = this._eo.data.value;
            for(var i = this._eo.data.level; i < this._eo.filters.data[0].length; i++)
                delete this._eo.filters.data[0][i];
        }
        RightNow.Event.fire("evt_menuFilterRequest", this._eo);
        this._nodeBeingExpanded = false;
    },


    /**
     * Event handler when report has been updated
     * @param type String Event name
     * @param args Object Event arguments
     */
    _onReportResponse: function(type, args)
    {
        if(RightNow.Event.isSameReportID(args, this.data.attrs.report_id)) {
            var data = RightNow.Event.getDataFromFiltersEventResponse(args, this.data.js.searchName, this.data.attrs.report_id);
            if(data[0] && data[0].length) {
                if(!this._tree)
                    this._buildTree();
                //remove empties
                if(typeof data[0] === "string")
                    data[0] = data[0].split(",");
                var finalData = RightNow.Lang.arrayFilter(data[0]);
                this._expandAndCreateNodes(finalData);
                this._eo.filters.data[0] = finalData;
                this._lastSearchValue = finalData.slice(0);
                if(this._eo.filters.data.reconstructData) {
                    this._eo.filters.data.level = this._eo.filters.data.reconstructData.level;
                    this._eo.filters.data.label = this._eo.filters.data.reconstructData.label;
                }
            }
            else if(this._tree) {
                //going from some selection back to no selection
                this._eo.filters.data[0] = [];
                this._currentIndex = this._noValueNodeIndex;
                this._displaySelectedNodesAndClose();
            }
        }
    },
    
    /**
    * Used to set the tree to a specific state; programatically expands nodes 
    * in order to build up the hierarchy tree to the specified array of IDs.
    * @param hierArray Array IDs denoting the specified prod/cat chain
    */
    _expandAndCreateNodes: function(hierArray)
    {
        var i = hierArray.length - 1,
            currentNode = null;
        //walk up the chain looking for the first available node
        while(!currentNode && i >= 0) {
            currentNode = this._tree.getNodeByProperty("hierValue", parseInt(hierArray[i]));
            i--;
        }
        //now currentNode should be something:
        //already selected? return
        if(this._currentIndex === currentNode.index)
        {
            //close the basic view if it is in use
            if(this._dialog && this._dialog.cfg.getProperty("visible"))
                this._dialog.hide();
            return;
        }
        //if we already have the one selected, then we can go ahead and select it.
        i++;
        if(this._noValueNodeIndex === currentNode.index || currentNode.hierValue == hierArray[hierArray.length - 1]) {
            this._selectNode({node: currentNode});
        }
        else {
            var onExpandComplete = function(expandingNode) {
                if(expandingNode.nextToExpand) {
                    var nextNode = this._tree.getNodeByProperty("hierValue", parseInt(expandingNode.nextToExpand));
                    if(nextNode) {
                        nextNode.nextToExpand = hierArray[++i];
                        nextNode.expand();
                    }
                }
                else if(i === hierArray.length) {
                    //we don't want to subscribe to this more than once
                    this._tree.unsubscribe("expandComplete", onExpandComplete, null);
                    expandingNode.expanded = false;
                    this._selectNode({node: expandingNode});
                }
                return true;
            };
            //walk back down to their selection from here expanding as you go
            this._tree.subscribe("expandComplete", onExpandComplete, null, this);
            currentNode.nextToExpand = hierArray[++i];
            currentNode.expand();
        }
    },

    /**
     * Event handler when returning from ajax data request
     * @param type String Event name
     * @param args Object Event arguments
     */
    _getSubLevelResponse: function(type, args)
    {
        var evtObj = args[0];

        //Check if this widget is supposed to update
        if((evtObj.data.data_type !== this._dataType) || (evtObj.filters.report_id !== this.data.attrs.report_id))
            return;

        var hierLevel = evtObj.data.level,
              hierData = evtObj.data.hier_data,
              redisplaySelectedNode = false,
              currentRoot = null;

        if(!this._tree)
            this._buildTree();
        
        if(!evtObj.data.reset_linked_category && this._getSubLevelRequest._origRequest && this._getSubLevelRequest._origRequest[this._dataType])
        {
            //get the node by its hierValue
            currentRoot = this._tree.getNodeByProperty("hierValue", this._getSubLevelRequest._origRequest[this._dataType]);
            if(currentRoot.index !== this._currentIndex)
            {
                this._currentIndex = currentRoot.index;
                redisplaySelectedNode = true;
            }
        }
        //prod linking : data's being completely reset
        else if(evtObj.data.reset_linked_category)
        {
            //clear out the existing tree and add 'no value' node
            currentRoot = this._tree.getRoot();
            currentRoot.dynamicLoadComplete = false;
            this._tree.removeChildren(currentRoot);
            this._flatTreeViewData = null;
            var tempNode = new YAHOO.widget.MenuNode(RightNow.Interface.getMessage("NO_VAL_LBL"), currentRoot, false);
            tempNode.hierValue = 0;
            tempNode.href='javascript:void(0);';
            tempNode.isLeaf = true;
            this._noValueNodeIndex = this._currentIndex = tempNode.index;
            //since the data's being reset, reset the button's label
            this._displayFieldVisibleText.innerHTML = this.data.attrs.label_nothing_selected;
            var description = document.getElementById("rn_" + this.instanceID + "_TreeDescription");
            if(description)
                description.innerHTML = this.data.attrs.label_nothing_selected;
        }

        //add the new nodes to the currently selected node
        if(hierLevel < 7 && !currentRoot.dynamicLoadComplete)
        {
            var isLeafIndex = (this.data.js.linkingOn && this._dataType === "categories") ? 2 : 3;
            for(var i = 0, tempNode; i < hierData.length; i++)
            {
                tempNode = new YAHOO.widget.MenuNode(hierData[i][1], currentRoot, false);
                tempNode.hierValue = hierData[i][0];
                tempNode.href = 'javascript:void(0);';
                if(!hierData[i][isLeafIndex] || hierLevel === 6)
                {
                    //if it doesn't have children then turn off the +/- icon
                    //and notify that the node is already loaded
                    tempNode.dynamicLoadComplete = true;
                    tempNode.iconMode = 1;
                }
            }
            currentRoot.loadComplete();
        }
        //leaf node was expanded : display and close
        if(hierData.length === 0 && !this._selected)
        {
            this._displaySelectedNodesAndClose();
        }
        //node was selected : its already selected and closed
        else if(this._selected)
        {
            this._selected = false;
        }
        else if(redisplaySelectedNode && this._selectNode._selectedWidget)
        {
            this._selectNode._selectedWidget = null;
            this._displaySelectedNodesAndClose();
        }
    },

    /**
    * Returns event object for search event if the report matches.
    * @param type String Event name
    * @param args Object Event object
    */
    _getFiltersRequest: function(type, args)
    {
        if(this._tree)
        {
            this._eo.filters.data.reconstructData = [];
            if(this._currentIndex !== this._noValueNodeIndex)
            {
                var currentNode = this._tree.getNodeByIndex(this._currentIndex || this._noValueNodeIndex),
                      hierValues,
                      level;
                this._eo.data.level = currentNode.depth + 1;
                this._eo.data.label = currentNode.label;
                this._eo.data.value = currentNode.hierValue;
                while(currentNode && !currentNode.isRoot())
                {
                    level = currentNode.depth + 1;
                    hierValues = this._eo.filters.data[0].slice(0, level).join(",");
                    this._eo.filters.data.reconstructData.push({"level" : level, "label" : currentNode.label, "hierList" : hierValues});
                    currentNode = currentNode.parent;
                }
                this._eo.filters.data.reconstructData.reverse();
            }
            else
            {
                this._eo.filters.data[0] = [];
                this._eo.data.value = 0;
            }
        }
        this._lastSearchValue = this._eo.filters.data[0].slice(0);
        RightNow.Event.fire("evt_searchFiltersResponse", this._eo);
    },

    /**
    * Responds to the filterReset event by setting the internal eventObject's data to blank.
    * @param type String Event name
    * @param args Object Event object
    */
    _onResetRequest: function(type, args)
    {
        if(this._tree && RightNow.Event.isSameReportID(args, this.data.attrs.report_id) && (args[0].data.name === this.data.js.searchName || args[0].data.name === "all"))
        {
            if(args[0].data.name === "all" && this._lastSearchValue)
            {
                //setting back to last search-on value
                this._eo.filters.data[0] = this._lastSearchValue;
                this._currentIndex = this._tree.getNodeByProperty("hierValue", this._lastSearchValue[this._lastSearchValue.length - 1]).index;
            }
            else
            {
                if(args[0].data.reset && this.data.js.linkingOn && this._dataType === "categories")
                {
                    this._buildTree();
                }
                //setting to no value
                this._eo.filters.data[0] = [];
                this._currentIndex = this._noValueNodeIndex;
            }
            this._displaySelectedNodesAndClose();
        }
    },

    /**
     * Sets filters for searching on report
     */
    _initializeFilter: function()
    {
        this._eo.w_id = this.instanceID;
        this._eo.data.data_type = this._dataType = this.data.attrs.filter_type;
        this._eo.data.linking_on = this.data.js.linkingOn;
        this._eo.data.cache = [];
        this._eo.data.hm_type = this.data.js.hm_type;
        this._eo.data.linkingProduct = 0;
        this._eo.filters = {"rnSearchType" : "menufilter",
                                    "searchName" : this.data.js.searchName,
                                    "report_id" : this.data.attrs.report_id,
                                    "fltr_id" : this.data.js.fltr_id,
                                    "oper_id" : this.data.js.oper_id,
                                    "data" : []};
        this._eo.filters.data[0] = (this.data.js.initial) ? this.data.js.initial : [];
        this._lastSearchValue = this._eo.filters.data[0].slice(0);
        //Set namespace global for hier menu list linking display
        if(this._dataType === "products")
        {
            RightNow.UI.currentProduct = this._eo.filters.data[0][this._eo.filters.data[0].length - 1];
            RightNow.UI.linkingOn = this.data.js.linkingOn;
            RightNow.UI.linkingFilter = this.data.attrs.filter_name;
        }
    },
    
    /**
    * Resets the native YAHOO.widgetTreeView keyboard bindings to a simpler,
    * easier-to-use scheme.
    */
    _initializeKeyBindings: function()
    {
        if(!this._initializeKeyBindings._initialized) {
            this._initializeKeyBindings._initialized = true;
            YAHOO.widget.TreeView.prototype._onKeyDownEvent = function(ev) {
                var target = YAHOO.util.Event.getTarget(ev),
                      node = this.getNodeByElement(target),
                      newNode = node,
                      KEY = YAHOO.util.KeyListener.KEY;
                switch(ev.keyCode) {
                    case KEY.UP:
                        do {
                            if(newNode.previousSibling) {
                                var currentNode = newNode.previousSibling;
                                while(currentNode && currentNode.expanded && currentNode.children.length) {
                                    currentNode = currentNode.children[currentNode.children.length - 1];
                                }
                                newNode = currentNode;
                            }
                            else {
                                newNode = newNode.parent;
                            }
                        }
                        while(newNode && !newNode._canHaveFocus());
                        if (newNode)
                            newNode.focus();
                        YAHOO.util.Event.preventDefault(ev);
                        break;
                    case KEY.DOWN:
                        do {
                            if(newNode.children.length && newNode.expanded) {
                                newNode = newNode.children[0];
                            }
                            else if(newNode.nextSibling) {
                                newNode = newNode.nextSibling;
                            }
                            else {
                                var currentNode = newNode.parent;
                                while(currentNode) {
                                    if(currentNode.nextSibling) {
                                        newNode = currentNode.nextSibling;
                                        break;
                                    }
                                    else {
                                        currentNode = currentNode.parent;
                                    }
                                }
                            }
                        }
                        while(newNode && !newNode._canHaveFocus);
                        if(newNode)
                            newNode.focus();
                        YAHOO.util.Event.preventDefault(ev);
                        break;
                    case KEY.LEFT:
                        node.collapse();
                        YAHOO.util.Event.preventDefault(ev);
                        break;
                    case KEY.RIGHT:
                        node.expand();
                        YAHOO.util.Event.preventDefault(ev);
                        break;
                    case KEY.ENTER:
                    case KEY.TAB:
                        if(node.href) {
                            if(node.target) {
                                window.open(node.href,node.target);
                            }
                            else {
                                window.location(node.href);
                            }
                        }
                        else {
                            node.toggle();
                        }
                        this.fireEvent('enterKeyPressed', node);
                        YAHOO.util.Event.preventDefault(ev);
                        break;
                    case KEY.HOME:
                        newNode = this.getRoot();
                        if(newNode.children.length)
                            newNode = newNode.children[0];
                        if(newNode._canHaveFocus())
                            newNode.focus();
                        YAHOO.util.Event.preventDefault(ev);
                        break;
                    case KEY.END:
                        newNode = newNode.parent.children;
                        newNode = newNode[newNode.length -1];
                        if(newNode._canHaveFocus())
                            newNode.focus();
                        YAHOO.util.Event.preventDefault(ev);
                        break;
                    case 107:  //plus key
                        if(ev.shiftKey) {
                            node.parent.expandAll();
                        }
                        else {
                            node.expand();
                        }
                        break;
                    case 109: //minus key
                        if(ev.shiftKey) {
                            node.parent.collapseAll();
                        }
                        else {
                            node.collapse();
                        }
                        break;
                    default:
                        break;
                }
            };
        }
    }
};

