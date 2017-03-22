RightNow.Widget.Accordion = function (data, instanceID) {
    this.data = data;
    this.instanceID = instanceID;
    this._header_label = document.getElementById("rn_" + this.instanceID + "_header_label");
    this._eo = new RightNow.Event.EventObject();

    RightNow.Event.subscribe('evt_managePanel', this._onManagePanel, this);
    RightNow.Event.subscribe('evt_updatePanelHeaderLabel', this._onUpdatePanelHeader, this);

    this._toggle = document.getElementById("rn_" + this.instanceID + "_" + "trigger");

    this._itemToToggle = document.getElementById(this.data.attrs.item_to_toggle);
    this._parentContainer = document.getElementById("rn_" + this.instanceID + "_" + "header").parentNode;

    if (this._toggle) {
        if (!this._itemToToggle) {
            var current = this._toggle.nextSibling;
            while (current) {
                if (current.nodeType === 1)
                    break;
                else
                    current = current.nextSibling;
            }
            if (current)
                this._itemToToggle = current;
            else return;
        }

        //trick to get voiceover to announce state to screen readers.
        this._screenReaderMessageCarrier = document.createElement("img");
        YAHOO.util.Dom.setStyle(this._screenReaderMessageCarrier, "opacity", "0");
        this._screenReaderMessageCarrier.src = "/euf/rightnow/images/whitePixel.png";
        this._screenReaderMessageCarrier.alt = this._currentlyShowing() ? this.data.attrs.label_expanded : this.data.attrs.label_collapsed;
        this._toggle.appendChild(this._screenReaderMessageCarrier);
        YAHOO.util.Event.on(this._toggle, "click", this._onClick, null, this);
    }

    if (this.data.attrs.visible == true) {
        this._show();
    }
    else if (this.data.attrs.visible == false) {
        this._hide();
    }

    if (this.data.attrs.expanded == true) {
        this._expand();
    }
    else if (this.data.attrs.expanded == false) {
        this._collapse();
    }
};

RightNow.Widget.Accordion.prototype = {
    /**
    * Toggles the display of the element.
    * @param clickEvent Event Click event
    */
    _onClick: function (clickEvent) {

        if (this._currentlyShowing()) {

            this._collapse();
        }
        else {

            this._expand();
        }

    },

    _currentlyShowing: function () {
        return YAHOO.util.Dom.hasClass(this._toggle, this.data.attrs.expanded_css_class)
    },

    _collapse: function () {
        this._itemToToggle.style.display = "none";
        this._screenReaderMessageCarrier.alt = this.data.attrs.label_collapsed;
        YAHOO.util.Dom.addClass(this._toggle, 'rn_Accordion_panel_down');
        YAHOO.util.Dom.removeClass(this._toggle, 'rn_Accordion_panel_up');
        YAHOO.util.Dom.removeClass(this._toggle, this.data.attrs.expanded_css_class);

    },

    _expand: function () {
        this._itemToToggle.style.display = "block";
        this._screenReaderMessageCarrier.alt = this.data.attrs.label_expanded;
        YAHOO.util.Dom.addClass(this._toggle, 'rn_Accordion_panel_up');
        YAHOO.util.Dom.removeClass(this._toggle, 'rn_Accordion_panel_down');
        YAHOO.util.Dom.addClass(this._toggle, this.data.attrs.expanded_css_class);
    },

    _show: function () {
        YAHOO.util.Dom.removeClass(this._parentContainer, 'rn_Hidden');
        this._disablePanel(this._parentContainer, false);
    },

    _hide: function () {
        YAHOO.util.Dom.addClass(this._parentContainer, 'rn_Hidden');
        this._disablePanel(this._parentContainer, true);
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
                    this._expand();
                }
            }
        }

        if (collapseList != undefined) {
            for (i = 0; i < collapseList.length; i++) {
                if (collapseList[i].toLowerCase() == this.data.attrs.name.toLowerCase()) {
                    this._collapse();
                }
            }
        }

        if (showList != undefined) {
            for (i = 0; i < showList.length; i++) {
                if (showList[i].toLowerCase() == this.data.attrs.name.toLowerCase()) {
                    this._show();
                }
            }
        }

        if (hideList != undefined) {
            for (i = 0; i < hideList.length; i++) {
                if (hideList[i].toLowerCase() == this.data.attrs.name.toLowerCase()) {
                    this._hide();
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
                    this._header_label.innerHTML = headerLabelList[i].value;
                }
            }
        }

    },

    _disablePanel: function (el, disabled) {

        try {
            el.disabled = disabled;
            var eo = new RightNow.Event.EventObject();
            eo.id = el.id;
            RightNow.Event.fire("evt_fieldVisibilityChanged", eo);
        }
        catch (E) {
        }
        if (el.childNodes && el.childNodes.length > 0) {
            for (var x = 0; x < el.childNodes.length; x++) {
                this._disablePanel(el.childNodes[x], disabled);
            }
        }

    }
};
