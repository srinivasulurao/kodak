RightNow.Widget.CustomProductCategoryInput = function(data, instanceID)
{
    this.data = data;
    this.instanceID = instanceID;
    this._eo = new RightNow.Event.EventObject();
    this._currentIndex = 0;
    this._noValueNodeIndex = 0;
    this._maxDepth = 6;
    this._displayField = document.getElementById("rn_" + this.instanceID + "_" + this.data.attrs.data_type + "_Button");
    this._displayFieldVisibleText = document.getElementById("rn_" + this.instanceID + "_Button_Visible_Text");
    this._accessibleView = document.getElementById("rn_" + this.instanceID + "_Links");
    this._outerTreeContainer = "rn_" + this.instanceID + ((this.data.attrs.show_confirm_button_in_dialog) ? "_TreeContainer" : "_Tree");

    if(this.data.js.readOnly || !this._displayField) return;    

    RightNow.Event.subscribe("evt_menuFilterGetResponse", this._getSubLevelResponse, this);
    RightNow.Event.subscribe("evt_formFieldValidateRequest", this._onValidateRequest, this);
    RightNow.Event.subscribe("evt_accessibleTreeViewGetResponse", this._getAccessibleTreeViewResponse, this);
	//gsh create Event
	RightNow.Event.create("cih_event", this);
	RightNow.Event.subscribe("cih_event", this._cihOnMenuFilterRequest, this);
	
    if(this.data.attrs.set_button)
        YAHOO.util.Event.addListener("rn_" + this.instanceID + "_" + this.data.attrs.data_type + "_SetButton", "click", this._setButtonClick, null, this);

    //toggle panel on/off when button is clicked
	YAHOO.util.Event.addListener(this._displayField, "click", this._toggleProductCategoryPicker, null, this);
	YAHOO.util.Event.addListener("rn_" + this.instanceID + "_LinksTrigger", "click", this._toggleAccessibleView, null, this);
	
    //setup event object
    this._eo.data = {"data_type" : this.data.attrs.data_type,
                     "hm_type" : this.data.js.hm_type,
                     "linking_on" : this.data.js.linkingOn,
                     "linkingProduct": 0,
                     "table" : this.data.attrs.table,
                     "cache" : [],
                     "name" : ((this.data.attrs.data_type.indexOf('prod') > -1) ? 'prod' : 'cat')};
    this._eo.w_id = this.instanceID;

    //build menu panel
    
    this._panel = new YAHOO.widget.Panel(this._outerTreeContainer, { close:false, width:"300px", visible:false, constraintoviewport:true });
    this._panel.setHeader("");
    this._panel.render();

    YAHOO.util.Dom.setStyle("rn_" + this.instanceID + "_Tree", "overflow-y", "auto");
	
    if(this.data.js.defaultData)
        this._buildTree();
		
	
};

RightNow.Widget.CustomProductCategoryInput.prototype = {
    /**
    * Constructs the YUI Treeview widget for the first time with initial data returned
    * from the server. Pre-selects and expands data that is expected to be populated.
    */
    _buildTree : function()
    {

		var selectedNode;
		
        this._tree = new YAHOO.widget.TreeView("rn_" + this.instanceID + "_Tree");
        if(this._tree)
        {
            this._tree.setDynamicLoad(RightNow.Event.createDelegate(this, this._getSubLevelRequest));
            //if there is no confirm button tab should close the panel 
            //but when there is tab should be ignored and by default take you to the confirm button
            if(!this.data.attrs.show_confirm_button_in_dialog) {
                YAHOO.util.Event.addListener(this._tree.getEl(), "keydown", function(ev){
                    if(YAHOO.util.Event.getCharCode(ev) === YAHOO.util.KeyListener.KEY.TAB)
                    {
                        var currentNode = this._tree.currentFocus;
                        if(currentNode.href) {
                            if(currentNode.target) {
                                window.open(currentNode.href, node.target);
                            }
                            else {
                                window.location(currentNode.href);
                            }
                        }
                        else {
                            currentNode.toggle();
                        }
                        this._tree.fireEvent('enterKeyPressed', currentNode);
                        YAHOO.util.Event.preventDefault(ev);
                    }
                }, null, this);
            }
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
						selectedNode = node;
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
            if(this.data.attrs.show_confirm_button_in_dialog)
            {
                YAHOO.util.Event.addListener("rn_" + this.instanceID + "_" + this.data.attrs.data_type + '_ConfirmButton', "click", function(){
                    this._selectNode({node: this._tree.currentFocus});
                }, null, this);
                YAHOO.util.Event.addListener("rn_" + this.instanceID + "_" + this.data.attrs.data_type + '_CancelButton', "click", this._toggleProductCategoryPicker, null, this);
                YAHOO.util.Event.addListener("rn_" + this.instanceID + "_" + this.data.attrs.data_type + '_CancelButton', "keydown", function(ev){
                    if(YAHOO.util.Event.getCharCode(ev) === YAHOO.util.KeyListener.KEY.TAB && !ev.shiftKey)
                        this._displaySelectedNodesAndClose(true);
                }, null, this);
            }
            else
            {
                this._tree.subscribe('clickEvent', this._selectNode, null, this);
            }            
            
            //scroll container to 20px above expanded node
			
            this._tree.subscribe('expandComplete', function(node) {
                document.getElementById('rn_' + this.instanceID + "_Tree").scrollTop = node.getEl().offsetTop - 20;
            }, null, this);
            this._tree.render();
            this._tree.collapseAll();
            if(this.data.attrs.show_confirm_button_in_dialog)
                YAHOO.util.Dom.setStyle("rn_" + this.instanceID + "_TreeContainer", "display", "block");
            YAHOO.util.Dom.setStyle("rn_" + this.instanceID + "_Tree", "display", "block");
            if(defaultValues)
                this._displaySelectedNodesAndClose(false);
		

			//gsh
			if(this._eo.data.data_type === "products")
	        {	
				this._getSubLevelRequest(selectedNode)
				
				YAHOO.util.Event.purgeElement("rn_" + this.instanceID + "_Tree", true)
			}

		
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
            YAHOO.util.Dom.removeClass(this._accessibleView, "rn_Hidden");
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
                selectedNodes = selectedNodes[0] ? selectedNodes.join(", ") : RightNow.Interface.getMessage("NO_VAL_LBL");
                currentlySelectedSpan.innerHTML = RightNow.Text.sprintf(RightNow.Interface.getMessage("SELECTION_PCT_S_ACTIVATE_LINK_JUMP_MSG"), selectedNodes);
            }
        }

        this._dialog.show();
        return false;
    },

    /**
    * Toggles accessible view.
    */
    _toggleAccessibleView: function()
    {
        if(this.data.attrs.data_type === "categories" && this.data.js.linkingOn)
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
        if(evtObj.data.data_type == this.data.attrs.data_type)
        {
            this._flatTreeViewData = evtObj.data.accessibleLinks;
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
                {
                    if(!isNaN(parseInt(i, 10)))
                        tempArray[i] = this._flatTreeViewData[i];    
                }
                
                this._flatTreeViewData = tempArray;
            }
            this._flatTreeViewData.unshift(noValue);
            var htmlList = "<p><a href='javascript:void(0)' id='rn_" + this.instanceID + "_Intro'" + 
            "onclick='document.getElementById(\"rn_" + this.instanceID + "_AccessibleLink_" + noValue[1] +
            "\").focus();'>" + RightNow.Text.sprintf(RightNow.Interface.getMessage("PLS_SEL_PCT_S_LINKS_DEPTH_ANNOUNCED_MSG"), this.data.attrs.label_input) + 
            " <span id='rn_" + this.instanceID + "_IntroCurrentSelection'>" + RightNow.Text.sprintf(RightNow.Interface.getMessage("SELECTION_PCT_S_ACTIVATE_LINK_JUMP_MSG"), noValue[0]) + "</span></a></p>";
            //loop through each hier_item to figure out nesting structure
            var previousLevel = -1;
            for(i in this._flatTreeViewData)
            {
                if(this._flatTreeViewData.hasOwnProperty(i))
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
            }
            //close list
            for(i = previousLevel; i >= 0; --i)
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
        //attempt to get the one they clicked first
        var i = hierArray.length-1;
        var currentNode = null;
        //walk up the chain looking for the first available node
        while(!currentNode && i>=0)
        {
            currentNode = this._tree.getNodeByProperty("hierValue", parseInt(hierArray[i], 10));
            i--;
        }
        //now currentNode should be something.
        //if we already have the one they selected, then we can go ahead and click it.
        i++;
        if(this._noValueNodeIndex === currentNode.index || currentNode.hierValue == hierArray[hierArray.length-1])
        {
            this._selectNode({node: currentNode});
        }
        else
        {
            var onExpandComplete = function(expandingNode)
            {
                if(expandingNode.nextToExpand)
                {
                    var nextNode = this._tree.getNodeByProperty("hierValue", parseInt(expandingNode.nextToExpand, 10));
                    if(nextNode)
                    {
                        nextNode.nextToExpand = hierArray[++i];
                        nextNode.expand();
                    }
                }
                else if(i === hierArray.length)
                {
                    //we don't want to subscribe to this more than once
                    this._tree.unsubscribe("expandComplete", onExpandComplete, null);
                    this._selectNode({node: expandingNode});
                }
                return true;
            };
            //walk back down to their selection from here expanding as you go
            this._tree.subscribe("expandComplete", onExpandComplete, null, this);
            currentNode.nextToExpand = hierArray[++i];
            currentNode.expand();
        }
        return false;
    },

    /**
    * Shows / hides Panel containing TreeView widget
    * Shows when user clicks button and the Panel is hidden.
    * Hides when user selects a node or the Panel loses focus.
    * @param event Event Select button's click event
    */
    _toggleProductCategoryPicker: function(event)
    {
        //build tree for the first time
        if(!this._tree)
            this._buildTree();
        //show panel
        if(this._panel.cfg.getProperty("visible") === false)
        {
            this._panel.syncPosition();
            this._panel.show();
            //focus on either the previously selected node or the first node
            var currentNode = this._tree.getNodeByIndex(this._currentIndex);
            if(currentNode && currentNode.focus)
            {
                currentNode.focus();
            }
            else if(this._tree.getRoot().children[0] && this._tree.getRoot().children[0].focus)
            {
                this._tree.getRoot().children[0].focus();
            }

            //create event listener (once)
            this._toggleProductCategoryPicker._closeListener = this._toggleProductCategoryPicker._closeListener ||
            function(event)
            {
                if(this._panel.cfg.getProperty("visible"))
                {
                    var coordinates = YAHOO.util.Event.getXY(event);
                    //return if target was the toggle button (either clicking or enter key)
                    if((event.type === "click" && YAHOO.util.Event.getTarget(event).id === this._displayField.id) || coordinates[0] === 0 || coordinates[1] === 0)
                        return;

                    coordinates = new YAHOO.util.Point(coordinates[0], coordinates[1]);
                    var panelRegion = YAHOO.util.Dom.getRegion(this._outerTreeContainer),
                        buttonRegion = YAHOO.util.Dom.getRegion(this._displayField);
                    if(panelRegion && buttonRegion && (!panelRegion.contains(coordinates) && !buttonRegion.contains(coordinates)))
                    {
                        //if click was anywhere outside of button or panel region, hide the panel
                        if(this.data.attrs.show_confirm_button_in_dialog && this._tree.currentFocus)
                            this._currentIndex = this._tree.currentFocus.index;
                        this._displaySelectedNodesAndClose(false);
                        YAHOO.util.Event.removeListener(document, this._toggleProductCategoryPicker._closeListener);
                    }
                }
            };
            YAHOO.util.Event.addListener(document, "click", this._toggleProductCategoryPicker._closeListener, null, this);
        }
        //hide panel
        else
        {
            this._displaySelectedNodesAndClose(false);
            YAHOO.util.Event.removeListener(document, this._toggleProductCategoryPicker._closeListener);
        }
    },

    /**
    * Navigates up from the selected node, generating an array
    * consisting of the labels of ea. hierarchy level in order.
    * @return array Array of labels
    */
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
        this._eo.data.value = this._currentIndex;
        RightNow.Event.fire("evt_productCategorySelected", this._eo);
        this._panel.hide();
        YAHOO.util.Dom.setAttribute(this._displayField, "aria-busy", "true");
        var description = document.getElementById("rn_" + this.instanceID + "_TreeDescription");
        //also close the dialog if it exists
        if(this._dialog && this._dialog.cfg.getProperty("visible"))
            this._dialog.hide();
        if(this._currentIndex <= this._noValueNodeIndex)
        {
            this._displayFieldVisibleText.innerHTML = this.data.attrs.label_nothing_selected;
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
            
            if(description)
               description.innerHTML = this.data.attrs.label_screen_reader_selected + hierValues;
        }
        YAHOO.util.Dom.setAttribute(this._displayField, "aria-busy", "false");
        //don't focus if the accessible dialog is in use or was in use during this page load.
        //the acccessible view and the treeview shouldn't really be mixed
        if(focus && this._displayField.focus && !this._dialog)
            try{this._displayField.focus();} catch(e){}
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
        var selectedNode = clickEvent.node;
        this._currentIndex = selectedNode.index;
        this._selected = true;
        //get next level if the node hasn't loaded children yet, isn't expanded, and isn't the 'No Value' node
        if((!selectedNode.expanded && this._currentIndex !== this._noValueNodeIndex && !selectedNode.dynamicLoadComplete) 
          || (this.data.js.linkingOn && selectedNode.depth + 1 < this._maxDepth)) //or if product linking's on and the selected node isn't a final-depth leaf
        {
            this._getSubLevelRequest(clickEvent.node);
        }
        else
        {
            this._errorLocation = "";
            this._checkRequiredLevel();
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
        if (this._nodeBeingExpanded) return;
        
        this._nodeBeingExpanded = true;
        this._eo.data.level = expandingNode.depth + 1;
        this._eo.data.value = expandingNode.hierValue;
        this._eo.data.label = expandingNode.label;
        
        //When the show_confirm_button_in_dialog attribute is set, we don't want to explicity change the users selection when they drill down
        //into an element. If we did that, the user wouldn't be able to use the cancel button correctly. We just want to set a
        //temporary value which we can use in the response event. If this attribute isn't set, keep the behavior the same as before.
        if(this.data.attrs.show_confirm_button_in_dialog)
            this._requestedIndex = expandingNode.index;
        else
            this._currentIndex = expandingNode.index;

        if(this.data.attrs.data_type === "products")
        {
            //Set namespace global for hier menu list linking display
            var Form = RightNow.UI.Form;
            Form.currentProduct = this._eo.data.value;
            Form.linkingOn = this.data.js.linkingOn;
            Form.linkingFilter = this.data.attrs.data_type;
        }

        this._eo.data.reset = false; //whether data should be reset for the current level
        if(this._eo.data.linking_on)
        {
			//prod linking
            if(this.data.attrs.data_type === "categories")
            {
				
                if(expandingNode.children.length)
                {
                    //data's already been loaded
                    this._nodeBeingExpanded = false;
                    return;
                }
                this._eo.data.reset = (this._eo.data.value < 1);
            }
            else if(this._eo.data.value < 1 && this.data.attrs.data_type === "products")
            {
                //product was set back to all: fire event for categories to re-show all
                var eo = new RightNow.Event.EventObject();
                eo.data = {"reset_linked_category" : true, "data_type" : "categories", "reset" : true};
                this._nodeBeingExpanded = false;
                RightNow.Event.fire("evt_menuFilterGetResponse", eo);
				
                return;
            }
        }

        if(this.data.js.link_map)
        {
			
            //pass link map (prod linking) to EventBus for first time
            this._eo.data.link_map = this.data.js.link_map;
			
            this.data.js.link_map = null;
        }
		
        //2011.08.09 scott harris: commented out RightNow.Event.fire("evt_menuFilterRequest", this._eo);
		//using a custom event to do filtering by a specific interface id for the categories
		RightNow.Event.fire("cih_event", this._eo);
        this._nodeBeingExpanded = false;

    },


	//gsh testing
	_cihOnMenuFilterRequest: function (type, eventObject)
	{
	
		eventObject = eventObject[0];
		var eo;
		if(eventObject.data.link_map && _productLinkingMap !== null)
		  _productLinkingMap = eventObject.data.link_map;
		//Currently only doing cache checking on non-linked menu filters
		if(!eventObject.data.linking_on)
		{
		  if(eventObject.data.level > 5)
		  return;
		  if(eventObject.data.value < 1)
		  {
		  eventObject.data.level = 1;
		  eventObject.data.hier_data = [];
		  RightNow.Event.fire("evt_menuFilterGetResponse", eventObject);
		  return;
		  }
		  if(eventObject.data.cache[eventObject.data.value])
		  {
		  //create new evt obj so that request evt obj isn't modified
		  eo = new this.EventObject();
		  eo.data = {"hier_data" : eventObject.data.cache[eventObject.data.value], "level" : eventObject.data.level + 1};
		  eo.w_id = eventObject.w_id;
		  RightNow.Event.fire("evt_menuFilterGetResponse", eo);
		  return;
		  }
		}
		else if((eventObject.data.data_type.indexOf("cat") > -1) && (typeof _productLinkingMap != "undefined") && _productLinkingMap[eventObject.data.value])
		{
		  eo = RightNow.Lang.cloneObject(eventObject);
		  if(!eo.data.reset)
			eo.data.level++;
		  eo.data.hier_data = RightNow.Lang.cloneObject(_productLinkingMap[eo.data.value]);
		  RightNow.Event.fire("evt_menuFilterGetResponse", eo);
		  return;
		}
		if(eventObject.data.level === 6)
		return;
		var postData = {"filter": eventObject.data.data_type, "lvl": (eventObject.data.level + 1), "id": eventObject.data.value, "linking": eventObject.data.linking_on};
		RightNow.Ajax.makeRequest("/ci/ajaxCustom/getHierValues", postData, {"successHandler": this._cih_menuFilterGetSuccess, "scope":this, "data": eventObject, "type":"GETPOST"});
	}, 

_cih_menuFilterGetSuccess: function(o)
             {
                 if(o.argument!=null)
                 {
                     var eventObject = o.argument;
                     if(o.responseText !== undefined)
                     {
                         //results[0] - Actual filter results
                         //results[1] - Linking results if neccesary
                         var results = RightNow.JSON.parse(o.responseText);
                         if (results)
                         {
                             eventObject.data.cache[eventObject.data.value] = results[0];
                             eventObject.data.hier_data = results[0];
                             eventObject.data.level += 1;
                             RightNow.Event.fire("evt_menuFilterGetResponse", eventObject);
         
                             //If linking is on, populate link_map and fire event to category hier menus
                             if (eventObject.data.linking_on && eventObject.data.data_type.indexOf("prod") > -1)
                             {
                                 _productLinkingMap = results.link_map;
                                 var linkingEvtObj = new RightNow.Event.EventObject();
                                 linkingEvtObj.data = {"level": 1,
                                                       "hier_data": _productLinkingMap[0],
                                                       "data_type": eventObject.data.data_type.replace("products", "categories"),
                                                       "reset_linked_category": true};
                                 linkingEvtObj.filters.report_id = eventObject.filters.report_id;
                                 RightNow.Event.fire("evt_menuFilterGetResponse", linkingEvtObj);
                                 //If product changed to none selected, clear out link map
                                 if (eventObject.data.value === -1)
                                     _productLinkingMap = null;
                             }
                         }
                         else
                         {
                             RightNow.UI.Dialog.messageDialog(RightNow.Interface.getMessage("ERROR_REQUEST_ACTION_COMPLETED_MSG"), {"icon": "WARN"});
                         }
                     }
                 }
             },

    /**
     * Event handler when returning from ajax data request.
     * @param type String Event name
     * @param args Object Event arguments
     */
    _getSubLevelResponse: function(type, args)
    {
        var evtObj = args[0];

        //Check if we are supposed to update : only if the original requesting widget or if category widget receiving prod links
        if((evtObj.w_id && evtObj.w_id === this.instanceID) || (this.data.js.linkingOn && evtObj.data.data_type === "categories" && this.data.attrs.data_type === evtObj.data.data_type))
        {
            var currentRoot;
            //prod linking : data's being completely reset
            if(evtObj.data.reset_linked_category)
            {
                if(!this._tree || evtObj.data.reset)
                {
                    //restore category tree to its orig. state
                    this._buildTree();
                    this._linkedCategorySubset = false;
                }

                this._flatTreeViewData = null;
                //clear out the existing tree and add 'no value' node
                currentRoot = this._tree.getRoot();
                if(!evtObj.data.reset)
                {
                    this._linkedCategorySubset = true;
                    currentRoot.dynamicLoadComplete = false;
                    this._tree.removeChildren(currentRoot);
                    var tempNode = new YAHOO.widget.MenuNode(RightNow.Interface.getMessage("NO_VAL_LBL"), currentRoot, false);
                    tempNode.hierValue = 0;
                    tempNode.href='javascript:void(0);';
                    tempNode.isLeaf = true;
                    this._noValueNodeIndex = this._currentIndex = this._requestedIndex = tempNode.index;
                }
                //since the data's being reset, reset the button's label
                this._displayFieldVisibleText.innerHTML = this.data.attrs.label_nothing_selected;
                var description = document.getElementById("rn_" + this.instanceID + "_TreeDescription");
                if(description)
                    description.innerHTML = this.data.attrs.label_screen_reader_selected + this.data.attrs.label_nothing_selected;
            }
            else
            {
                //Get the current root based on what node was drilled into. Depending on this attribute, it'll either be the currently
                //selected node or it'll be the temporary value we set above
                currentRoot = this._tree.getNodeByIndex(this.data.attrs.show_confirm_button_in_dialog ? this._requestedIndex : this._currentIndex);
            }

            var hierLevel = evtObj.data.level,
                hierData = evtObj.data.hier_data;

            if(hierLevel <= this._maxDepth)
            {
                for(var i = 0, hierValue, hasChildrenIndex; i < hierData.length; i++)
                {
                    hierValue = hierData[i][0];
                    if(!currentRoot.children[i] || currentRoot.children[i].hierValue !== hierValue)
                    {
                        hasChildrenIndex = hierData[i].length - 1;
                        tempNode = new YAHOO.widget.MenuNode(hierData[i][1], currentRoot, false);
                        tempNode.hierValue = hierValue;
                        tempNode.href = 'javascript:void(0);';
                        if(!hierData[i][hasChildrenIndex])
                        {
                            //if it doesn't have children then turn off the +/- icon
                            //and notify that the node is already loaded
                            tempNode.dynamicLoadComplete = true;
                            tempNode.iconMode = 1;
                        }
                    }
                }
                currentRoot.loadComplete();
                //if a leaf node was expanded then display and close
                if(hierData.length === 0 && !this._selected)
                {
                    this._displaySelectedNodesAndClose(false);
                }
                else if(this._selected && this.data.attrs.required_lvl)
                {
                    this._errorLocation = "";
                    this._checkRequiredLevel();
                    this._selected = false;
                }
            }
        }
    },

    /**
     * Event handler if set_button attribute is set to true
     */
    _setButtonClick: function()
    {
        var hierValues = [];
        //collect node values: work back up the tree
        if(this._currentIndex > this._noValueNodeIndex)
        {
            YAHOO.util.Dom.addClass(this._errorMessageDiv, "rn_Hidden");
            var currentNode = this._tree.getNodeByIndex(this._currentIndex),
                index = currentNode.depth + 1;
            while(currentNode && !currentNode.isRoot())
            {
                hierValues[index] = {"id" : currentNode.hierValue, "label" : currentNode.label};
                currentNode = currentNode.parent;
                index--;
            }
            this._currentIndex = this._noValueNodeIndex;
            var description = document.getElementById("rn_" + this.instanceID + "_TreeDescription");
            if(this._displayField && description)
                description.innerHTML = this._displayFieldVisibleText.innerHTML = this.data.attrs.label_nothing_selected;
        }
        else
        {
            if(this._errorMessageDiv === undefined)
            {
                this._errorMessageDiv = document.createElement("div");
                this._errorMessageDiv = YAHOO.util.Dom.insertBefore(this._errorMessageDiv, "rn_" + this.instanceID);
                YAHOO.util.Dom.addClass(this._errorMessageDiv, "rn_MessageBox");
                YAHOO.util.Dom.addClass(this._errorMessageDiv, "rn_ErrorMessage");
            }
            this._errorMessageDiv.innerHTML = "<b><a href='javascript:void(0);' onclick='document.getElementById(\"" + this._displayField.id + "\").focus(); return false;'>" +
                this.data.attrs.label_nothing_selected + "</a></b>";
            YAHOO.util.Dom.removeClass(this._errorMessageDiv, "rn_Hidden");
            var errorLink = YAHOO.util.Dom.getElementBy(function(){return true;}, "A", this._errorMessageDiv);
            if(errorLink)
                errorLink.focus();
            return;
        }
        this._eo.data.hierSetData = hierValues;
        RightNow.Event.fire("evt_menuFilterSelectRequest", this._eo);
    },

    /**
     * Event handler for when form is being validated
     * @param type String Event name
     * @param args Object Event arguments
     */
    _onValidateRequest: function(type, args)
    {
        this._parentForm = this._parentForm || RightNow.UI.findParentForm("rn_" + this.instanceID);
        this._eo.data.form = this._parentForm;
        if (RightNow.UI.Form.form === this._parentForm)
        {
            this._errorLocation = args[0].data.error_location;

            if(this._checkRequiredLevel())
            {
                if(this.data.attrs.table === "contacts")
                    this._eo.data.profile = true;

                var hierValues = [];
                //collect node values: work back up the tree
                if(this._currentIndex !== this._noValueNodeIndex)
                {
                    var currentNode = this._tree.getNodeByIndex(this._currentIndex);
                    while(currentNode && !currentNode.isRoot())
                    {
                        hierValues.push(currentNode.hierValue);
                        currentNode = currentNode.parent;
                    }
                }
                this._eo.data.value = hierValues.reverse();
                var tempCache = this._eo.data.cache;
                delete this._eo.data.cache;
                RightNow.Event.fire("evt_formFieldValidateResponse", this._eo);
                this._eo.data.cache = tempCache;
            }
        }
        else
        {
            RightNow.Event.fire("evt_formFieldValidateResponse", this._eo);
        }
        RightNow.Event.fire("evt_formFieldCountRequest");
    },

    /**
     * Checks if field has met its required level for submission
     */
    _checkRequiredLevel: function()
    {
        if(this.data.attrs.required_lvl)
        {
            if(!this._tree)
            {
                this._buildTree();
                this._currentIndex = this._noValueNodeIndex;
                this._displaySelectedNodesAndClose(false);
            }
            var currentNode = this._tree.getNodeByIndex(this._currentIndex);
            this._removeRequiredError(currentNode);
            var currentDepth = (currentNode) ? currentNode.depth + 1 : 1;
            if(this.data.js.linkingOn && this.data.attrs.data_type === "categories" && this._linkedCategorySubset)
            {
                //if there's some subset of categories that have been loaded then
                //allow submission if either there's only a single 'no value' node...
                if(this._tree.getNodeCount() === 1)
                {
                    return true;
                }
                //...or if a category meeting requirement lvl or a leaf node
                else if(currentDepth < this.data.attrs.required_lvl && ((currentNode.dynamicLoadComplete === false) || currentNode.hasChildren(false) || this._currentIndex === this._noValueNodeIndex))
                {
                    this._displayRequiredError(currentNode);
                    return false;
                }
            }
            //requirement error : if (nothing's selected) or ('no value's selected) or (current node still has children and the req level hasn't been hit)
            else if((!currentNode) || (this._currentIndex === this._noValueNodeIndex) || ((currentNode.dynamicLoadComplete === false) || (currentNode.hasChildren(false)) && (currentDepth < this.data.attrs.required_lvl)))
            {
                this._displayRequiredError(currentNode);
                return false;
            }
        }
        return true;
    },

    /**
    * Removes any previously set error classes from the widget's label,
    * selection button, and previously erroneous node.
    * @param currentNode MenuNode the currently selected node
    */
    _removeRequiredError: function(currentNode)
    {
        var Dom = YAHOO.util.Dom;
        Dom.removeClass(this._displayField, "rn_ErrorField");
        Dom.removeClass("rn_" + this.instanceID + "_Label", "rn_ErrorLabel");
        currentNode = this._displayRequiredError.errorNode || currentNode;
        if(currentNode)
            Dom.removeClass(currentNode.getEl(), "rn_ErrorField");
        Dom.replaceClass("rn_" + this.instanceID + "_RequiredLabel", "rn_RequiredLabel", "rn_Hidden");
        if(this._accessibleErrorMessageDiv)
            Dom.addClass(this._accessibleErrorMessageDiv, "rn_Hidden");
    },

    /**
     * Adds error classes to the widget's label, selection button,
     * and the currently selected node. Adds the required message
     * to the form's common error location.
     * @param currentNode MenuNode the currently selected node
     */
    _displayRequiredError: function(currentNode)
    {
        var Dom = YAHOO.util.Dom;
        //indicate the error
        Dom.addClass(this._displayField, "rn_ErrorField");
        Dom.addClass("rn_" + this.instanceID + "_Label", "rn_ErrorLabel");

        currentNode = currentNode || this._tree.getRoot().children[0];
        Dom.addClass(currentNode.getEl(), "rn_ErrorField");
        //save a local reference to the error node so that the error class can be removed from it later
        this._displayRequiredError.errorNode = currentNode;

        var message = this.data.attrs.label_nothing_selected;
        if (this._currentIndex !== this._noValueNodeIndex)
        {
            message = (this.data.attrs.label_required.indexOf("%s") > -1) ?
                RightNow.Text.sprintf(this.data.attrs.label_required, currentNode.label) :
                this.data.attrs.label_required;
        }
        //write out the required label
        var requiredLabel = document.getElementById("rn_" + this.instanceID + "_RequiredLabel");
        if(requiredLabel)
        {
            requiredLabel.innerHTML = message;
            Dom.replaceClass(requiredLabel, "rn_Hidden", "rn_RequiredLabel");
        }

        var Form = RightNow.UI.Form;
        //report error on common form button area
        if(this._errorLocation)
        {
            Form.errorCount++;
            var commonErrorDiv = document.getElementById(this._errorLocation);
            if(commonErrorDiv){
                if(Form.chatSubmit && Form.errorCount === 1)
                    commonErrorDiv.innerHTML = "";
                commonErrorDiv.innerHTML += "<div><b><a href='#' onclick='document.getElementById(\"" + this._displayField.id + "\").focus(); return false;'>" 
                    + this.data.attrs.label_input + " - " + message + "</a></b></div> ";
            }
        }
        Form.formError = true;
        //if the accessible dialog is created & open, add the error message to it
        if(this._dialog && this._dialog.cfg.getProperty("visible"))
        {
            this._accessibleErrorMessageDiv = this._accessibleErrorMessageDiv || document.getElementById("rn_" + this.instanceID + "_AccessibleErrorLocation");
            if(this._accessibleErrorMessageDiv)
            {
                this._accessibleErrorMessageDiv.innerHTML = "<div><b><a id='rn_" + this.instanceID + "_FocusLink' href='javascript:void(0);' " + 
                    " onclick='document.getElementById(\"" + "rn_" + this.instanceID + "_AccessibleLink_" + currentNode.hierValue + "\").focus(); return false;'>" +
                    this.data.attrs.label_input + " - " + message + "</a></b></div> ";
                Dom.addClass(this._accessibleErrorMessageDiv, "rn_MessageBox");
                Dom.addClass(this._accessibleErrorMessageDiv, "rn_ErrorMessage");
                Dom.removeClass(this._accessibleErrorMessageDiv, "rn_Hidden");
            }
            var errorLink = document.getElementById("rn_" + this.instanceID + "_FocusLink");
            RightNow.UI.updateVirtualBuffer();
            if(errorLink)
                errorLink.focus();
        }
    }
};

