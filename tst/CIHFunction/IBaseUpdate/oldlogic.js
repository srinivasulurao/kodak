RightNow.Widget.IBaseUpdate = function (data, instanceID) {
    //Data object contains all widget attributes, values, etc.
    this.data = data;
    this.instanceID = instanceID;
    this._waitPanel = null;
    this._eo = new RightNow.Event.EventObject();
    this._form_name = "rn_" + this.instanceID + "_form";
    this._form = document.getElementById("rn_" + this.instanceID + "_form");
    this._parentForm = RightNow.UI.findParentForm("rn_" + this.instanceID + "_form");
    this._contactForm = document.getElementById("rn_" + this.instanceID + "_contactForm");
    this._createContactContainer = document.getElementById("rn_" + this.instanceID + "_createContact");
    this._createContactButton = document.getElementById("rn_" + this.instanceID + "_createContactButton");
    this._ibaseUpdateType = document.getElementsByName("ek_ibase_updt_type")[this.data.attrs.widget_index];

    this._equipmentRelocationForm = document.getElementById("form_EquipmenRelocationForm" + this.instanceID);
    this._equipmentRemovalForm = document.getElementById("form_EquipmenRemovalForm" + this.instanceID);
    this._entitlementChangeForm = document.getElementById("form_EntitlementChangeForm" + this.instanceID);
    this._iBaseUpdateOtherForm = document.getElementById("form_IBaseUpdateOtherForm" + this.instanceID);
    this._eo.data.form = this._form_name;

    this._scrollTarg = document.getElementById("scroll_target");

    YAHOO.util.Event.addListener(this._ibaseUpdateType, "change", this._onIbaseTypeChange, null, this);

    YAHOO.util.Event.on(this._createContactButton, "click", this._showHideContactForm, true, this);

    RightNow.Event.subscribe('evt_populateIbaseUpdateData', this._populateIbaseUpdateData, this);

    RightNow.Event.subscribe('evt_managePanel', this._onManagePanel, this);

    this._createWaitPanel();

    if (this._form.isDisabled != true) {
        this._disableEvents();
        this._enableEvents();
    }

};
RightNow.Widget.IBaseUpdate.prototype = {
    //Define any widget functions here

    _enableEvents: function (evt, args) {
        RightNow.Event.subscribe("evt_contactSelectChanged", this._contactSelectChanged, this);
        RightNow.Event.subscribe("evt_resetForm", this._resetForm, this);

    },

    _disableEvents: function (evt, args) {
        RightNow.Event.unsubscribe("evt_contactSelectChanged", this._contactSelectChanged);
        RightNow.Event.unsubscribe("evt_resetForm", this._resetForm);
    },

    _onIbaseTypeChange: function (evt, args) {
        var selectedValue = this._ibaseUpdateType.options[this._ibaseUpdateType.selectedIndex].value

        switch (selectedValue) {

            case "460": //Equipment Removal
                this._showIBaseForm(this._equipmentRemovalForm);
                break;
            case "461": //Equipment Relocation
                this._showIBaseForm(this._equipmentRelocationForm);
                break;
            case "462": //Entitlement Change
                this._showIBaseForm(this._entitlementChangeForm);
                break;
            case "463": //IBase Update Other
                this._showIBaseForm(this._iBaseUpdateOtherForm);
                break;
            default:
                this._hideAllIBaseForms();
                break;

        }
    },

    _onManagePanel: function (evt, args) {

        var hideList, showList;
        hideList = args[0].data.hidelist;
        showList = args[0].data.showlist;

        if (showList != undefined) {
            for (i = 0; i < showList.length; i++) {
                if (showList[i] == this.data.attrs.panel_name) {

                    //2012.12.07 scott harris: to scroll to display form
                    var yval = YAHOO.util.Dom.getY(this._scrollTarg);
                    window.scrollTo(0,yval);

                    this._disableEvents(evt, args);
                    this._enableEvents(evt, args);
                    RightNow.Event.fire("evt_resetForm", this._eo);
                }
            }
        }
        if (hideList != undefined) {
            for (i = 0; i < hideList.length; i++) {
                if (hideList[i] == this.data.attrs.panel_name) {
                    this._disableEvents(evt, args);
                }
            }
        }
                
    },

    _showIBaseForm: function (ibaseform) {

        this._hideAllIBaseForms();
        this._toggleFormElements(ibaseform, false);
        YAHOO.util.Dom.removeClass(ibaseform, "rn_Hidden");
    },

    _hideAllIBaseForms: function () {
        if (!YAHOO.util.Dom.addClass(this._equipmentRelocationForm, "rn_Hidden"));
        {
            this._toggleFormElements(this._equipmentRelocationForm, true);
            YAHOO.util.Dom.addClass(this._equipmentRelocationForm, "rn_Hidden");
        }
        if (!YAHOO.util.Dom.addClass(this._equipmentRemovalForm, "rn_Hidden"));
        {
            this._toggleFormElements(this._equipmentRemovalForm, true);
            YAHOO.util.Dom.addClass(this._equipmentRemovalForm, "rn_Hidden");
        }
        if (!YAHOO.util.Dom.addClass(this._entitlementChangeForm, "rn_Hidden"));
        {
            this._toggleFormElements(this._entitlementChangeForm, true);
            YAHOO.util.Dom.addClass(this._entitlementChangeForm, "rn_Hidden");
        }

        if (!YAHOO.util.Dom.addClass(this._iBaseUpdateOtherForm, "rn_Hidden"));
        {
            this._toggleFormElements(this._iBaseUpdateOtherForm, true);
            YAHOO.util.Dom.addClass(this._iBaseUpdateOtherForm, "rn_Hidden");
        }
    },

    _toggleFormElements: function (container, isDisabled) {
        var tagNames = ["INPUT", "SELECT", "TEXTAREA"];
        for (var i = 0; i < tagNames.length; i++) {
            var elems = container.getElementsByTagName(tagNames[i]);
            for (var j = 0; j < elems.length; j++) {
                elems[j].disabled = isDisabled;
            }
        }
    },


    _contactSelectChanged: function (evt, args) {
        if (this._form.id != args[0].data.form)
            return;
        var c_id = args[0].filters.data;
        this._disablePanel(this._contactForm, false);
        if (c_id > 0) {
            this._disablePanel(this._contactForm, true);
            var postData = {};
            postData['c_id'] = c_id;
            if (evt == 'evt_contactSelectChanged')
                this._overrideAjaxMethod('evt_cih_ibase_customer');            
            this._waitPanel.show();
            RightNow.Ajax.makeRequest("/cc/contact_custom/contact_get", postData, {
                data: { eventName: "evt_contactRetrieveResponse" },
                successHandler: function (response) {
                    var resp = RightNow.JSON.parse(response.responseText);
                    this._ajaxResponse(resp);
                    this._waitPanel.hide();
                    //console.dir(response);
                },
                failuerHandler: function (response) {
                    this._waitPanel.hide();
                },
                scope: this
            });

        }

    },

    _resetForm: function () {
        this._hideAllIBaseForms();
    },

    _disablePanel: function (el, disabled) {

        try {
            el.disabled = disabled;
        }
        catch (E) {
        }
        if (el.childNodes && el.childNodes.length > 0) {
            for (var x = 0; x < el.childNodes.length; x++) {
                this._disablePanel(el.childNodes[x], disabled);
            }
        }

    },

    _showHideContactForm: function (evt, args) {

        if (args == true) {

            //YAHOO.util.Dom.removeClass(this._contactForm, "rn_Hidden");
            //YAHOO.util.Dom.addClass(this._createContactContainer, "rn_Hidden");
            RightNow.Event.fire("evt_resetContactSelection", this._eo);
            this._disablePanel(this._contactForm);
        }
        else {

            //YAHOO.util.Dom.addClass(this._contactForm, "rn_Hidden");
            //YAHOO.util.Dom.removeClass(this._createContactContainer, "rn_Hidden");
            this._disablePanel(this._contactForm);
        }
    },

    _ajaxResponse: function (args) {
        // First check for errors
        var result = args;
        if (!result) {
            alert("Unknown Ajax Error");
        } else if (result.status === 1) {
            this._processValues(result);
        } else {
            // handle the failure, see if there is a result.message for an error
        }

    },

    _processValues: function (result) {

        this._setValues('firstname', result.firstname);
        this._setValues('lastname', result.lastname);
        this._setValues('emailaddress', result.emailaddress);
        this._setValues('officephone', result.officephone);

        if (result.mobilephone)
            this._setValues('mobilephone', result.mobilephone);

        if (result.faxnumber)
            this._setValues('faxnumber', result.faxnumber);

        if (result.language1)
            this._setValues('language1', result.language1);

        if (result.language2)
            this._setValues('language2', result.language2);

        if (result.language3)
            this._setValues('language3', result.language3);

        this._setValues('optinglobal', result.optinglobal);
        this._setValues('optinincident', result.optinincident);
        this._setValues('optincisurvey', result.optincisurvey);


        if (result.country)
            this._setValues('country', result.country);
        if (result.ek_phone_extension)            this._setValues('ek_phone_extension', result.ek_phone_extension);

    },

    _setValues: function (name, value) {

        for (var obj in this._form) {

            if (typeof obj != null && this._form[obj] != null && this._form[obj].tagName != null) {

                switch (this._form[obj].tagName) {
                    case 'INPUT':
                        if (this._form[obj].name == name) {
                            this._form[obj].value = value;
                            if (this._form[obj].type == 'checkbox') {
                                this._form[obj].checked = value;
                            }
                            return;
                        }
                        break;
                    case 'SELECT':
                        if (this._form[obj].name == name && value != "") {
                            for (var i = 0; i < this._form[obj].length; i++) {
                                if (this._form[obj].options[i].value == value) {
                                    this._form[obj].selectedIndex = i;
                                    this._form[obj].options[i].selected = true;
                                    return;
                                }
                            }
                        }
                        break;

                }
            } //end if

        }

    },


    _populateIbaseUpdateData: function (evt, args) {

        //var sds = args[0].data.sds;
        //fire event to set value

        var eor = new RightNow.Event.EventObject();
        eor.data.name = 'ek_k_number';
        eor.data.value = args[0].data.knum;
        RightNow.Event.fire("evt_setHiddenField", eor);

        var eor = new RightNow.Event.EventObject();
        eor.data.name = 'ek_serial_number';
        eor.data.value = args[0].data.sn;
        RightNow.Event.fire("evt_setHiddenField", eor);

        var eor = new RightNow.Event.EventObject();
        eor.data.name = 'ek_equip_component_id';
        eor.data.value = args[0].data.equip_id;
        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();
        eor.data.name = 'default_product_array';
        eor.data.value = args[0].data.ibase_product_hier;
        RightNow.Event.fire('evt_setProductSelection', eor);

        eor = new RightNow.Event.EventObject();
        eor.data.name = 'ek_sap_product_id';
        eor.data.value = args[0].data.sap_prod_id;
        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();
        eor.data.name = 'ek_sap_soldto_custid';
        eor.data.value = args[0].data.sold_to;
        RightNow.Event.fire("evt_setHiddenField", eor);


        eor = new RightNow.Event.EventObject();
        eor.data.name = 'ek_enabling_partner';
        eor.data.value = args[0].data.enablingPartner;
        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();
        eor.data.name = 'ek_mvs_manfacturer';
        eor.data.value = args[0].data.mfgPartner;
        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();
        eor.data.name = 'ek_service_dist';
        eor.data.value = args[0].data.distrPartner;
        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();
        eor.data.name = 'ek_service_reseller';
        eor.data.value = args[0].data.resellPartner;
        RightNow.Event.fire("evt_setHiddenField", eor);

        eor = new RightNow.Event.EventObject();
        eor.data.name = 'ek_corporate';
        eor.data.value = args[0].data.corporatePartner;
        RightNow.Event.fire("evt_setHiddenField", eor);

   
    },

    /*
    * Override the submission controller ajax method
    */
    _overrideAjaxMethod: function (str) {

        RightNow.Event.unsubscribe('on_before_ajax_request', function (str, eo) {
            //if(str == "evt_cih_ibase_customer") {
            eo[0].url = '/cc/contact_custom/contact_get';
            //}
        });

        RightNow.Event.subscribe('on_before_ajax_request', function (str, eo) {
            //if(str == "evt_cih_ibase_customer") {
            eo[0].url = '/cc/contact_custom/contact_get';
            //}
        }, this);
    },


    _createWaitPanel: function () {
        this._waitPanel =
                    new YAHOO.widget.Panel("wait",
                                                    { width: "240px",
                                                        fixedcenter: true,
                                                        close: false,
                                                        draggable: false,
                                                        zindex: 4,
                                                        modal: true,
                                                        visible: false
                                                    }
                                                );

        this._waitPanel.setHeader(this.data.js.loadingmessage);
        this._waitPanel.setBody("<img src=\"/euf/assets/images/rel_interstitial_loading.gif\"/>");
        this._waitPanel.render(document.body);
    }
};
