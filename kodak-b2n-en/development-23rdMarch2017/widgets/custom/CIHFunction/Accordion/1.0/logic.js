RightNow.namespace('Custom.Widgets.CIHFunction.Accordion');
Custom.Widgets.CIHFunction.Accordion = RightNow.Widgets.Accordion.extend({ 
    /**
     * Place all properties that intend to
     * override those of the same name in
     * the parent inside `overrides`.
     */
    overrides: {
        /**
         * Overrides RightNow.Widgets.Accordion#constructor.
         */
        constructor: function() {
			RightNow.Event.subscribe('evt_managePanel', this._onManagePanel, this);
			RightNow.Event.subscribe('evt_updatePanelHeaderLabel', this._onUpdatePanelHeader, this);

			this._toggle = this.Y.one(this.baseSelector + "_trigger");
			this.data.attrs.toggle = this.baseDomID + "_trigger";
			this._header_label = this.Y.one(this.baseSelector + "_header_label");

            // Call into parent's constructor
            this.parent();

			this._parentContainer = this.Y.one(this.baseSelector + "_header").parentNode;
			this._toggleParent(this.data.attrs.visible);

			
			if (this.data.attrs.expanded == true) {
				this._expand();
			} else {
				this._collapse();
			}
        },

        /**
         * Overridable methods from Accordion:
         *
         * Call `this.parent()` inside of function bodies
         * (with expected parameters) to call the parent
         * method being overridden.
         */
        // _onClick: function(clickEvent)
		_onClick: function(clickEvent) {
			var target = clickEvent.target, cssClassToAdd, cssClassToRemove;
			if(this._currentlyShowing) {
				cssClassToAdd = 'rn_Accordion_panel_down';
				cssClassToRemove = 'rn_Accordion_panel_up';
			}
			else {
				cssClassToAdd = 'rn_Accordion_panel_up';
				cssClassToRemove = 'rn_Accordion_panel_down';
			}
			if(target) {
				target.addClass(cssClassToAdd)
						.removeClass(cssClassToRemove);
			}
			this.parent(clickEvent);
		}
    },

    _toggleTrigger: function (show) {
		this._currentlyShowing = !show;
		this._onClick(new RightNow.Event.EventObject(this, {target: this._toggle}));
    },

    _toggleParent: function (show) {
        this._parentContainer=this.Y.one(".rn_Accordion_container");
		if (show == true) {
			this._parentContainer.removeClass('rn_Hidden');
		} else {
			this._parentContainer.addClass('rn_Hidden');
		}
        this._disablePanel(this._parentContainer, false);
    },

    _onManagePanel: function (evt, args) {
        var expandList, collapseList, showList, hideList;
        expandList = args[0].data.expandlist;
        collapseList = args[0].data.collapselist;
        showList = args[0].data.showlist;
        hideList = args[0].data.hidelist;

        if (expandList != undefined) {
            for (i = 0; i < expandList.length; i++) {
                if (expandList[i].toLowerCase() == this.data.attrs.name.toLowerCase()) {
                    this._toggleTrigger(true);
                }
            }
        }

        if (collapseList != undefined) {
            for (i = 0; i < collapseList.length; i++) {
                if (collapseList[i].toLowerCase() == this.data.attrs.name.toLowerCase()) {
                    this._toggleTrigger(false);
                }
            }
        }

        if (showList != undefined) {
            for (i = 0; i < showList.length; i++) {
                if (showList[i].toLowerCase() == this.data.attrs.name.toLowerCase()) {
                    this._toggleParent(true);
                }
            }
        }

        if (hideList != undefined) {
            for (i = 0; i < hideList.length; i++) {
                if (hideList[i].toLowerCase() == this.data.attrs.name.toLowerCase()) {
                    this._toggleParent(false);
                }
            }
        }
    },

    _onUpdatePanelHeader: function (evt, args) {
        var headerLabelList;
        headerLabelList = args[0].data.headerlabellist;

        if (headerLabelList != undefined) {
            for (i = 0; i < headerLabelList.length; i++) {
                if (headerLabelList[i].name.toLowerCase() == this.data.attrs.name.toLowerCase()) {
                    this._header_label.setHTML(headerLabelList[i].value);
                }
            }
        }

    },

    _collapse: function () {
        
        document.getElementById(this.data.attrs.item_to_toggle).style.display = "none";
        this._screenReaderMessageCarrier.alt = this.data.attrs.label_collapsed;
        this.Y.one(this._toggle).addClass('rn_Accordion_panel_down');
        this.Y.one(this._toggle).removeClass('rn_Accordion_panel_up');
        this.Y.one(this._toggle).removeClass(this.data.attrs.expanded_css_class);

    },

    _expand: function () {
       
        document.getElementById(this.data.attrs.item_to_toggle).style.display = "block";
        this._screenReaderMessageCarrier.alt = this.data.attrs.label_expanded;
        this.Y.one(this._toggle).addClass('rn_Accordion_panel_up');
        this.Y.one(this._toggle).removeClass('rn_Accordion_panel_down');
        this.Y.one(this._toggle).addClass(this.data.attrs.expanded_css_class);
    },

    _disablePanel: function (el, disabled) {
        try {
            el.set('disabled',  disabled);
            RightNow.Event.fire("evt_fieldVisibilityChanged", new RightNow.Event.EventObject(this, {id: el.id}));
        }
        catch (E) {}
		el.get('childNodes').each(function (node) {
			//this._disablePanel(node, disabled);
		});
    }
});