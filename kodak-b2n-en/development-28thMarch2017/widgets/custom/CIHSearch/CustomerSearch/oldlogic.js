/* Originating Release: February 2012 */
RightNow.Widget.CustomerSearch = function(data, instanceID) {
    this.data = data;    this.instanceID = instanceID;
    this.siteDataTable = null;
    this.productDataTable = null;
    this.subcomponentsDataTable = null;
    this.contractsDataTable = null;
    this._waitPanel = null;

    this.countryValue = null;
    this.provinceValue = null;
    this.countryParamInstance = null;
    this.provinceParamInstance = null;
    this.partnerSelectInstance = null;
    this.partnerTypeAbbrev = null;
    this.partnerTypeValue = "00000002";  //default to Direct
    this._createWaitPanel();

    this.allowManageContact = false;
    this.allowRepairRequest = false;
    this.allowIbaseUpdate = false;

    RightNow.Event.subscribe('evt_registerPartnerType', this._setPartnerTypeSelector, this);
    RightNow.Event.subscribe('evt_MultipleOverride', this._displayMultipleResult, this);
    RightNow.Event.subscribe('evt_NewProductData', this._setProductData, this);
  
    //if(this.data.js.internal_user == "N" && this.data.js.role_functions) {
    if(this.data.js.role_functions) {

      for(var i=0; i < this.data.js.role_functions.length; i++) {

        switch(this.data.js.role_functions[i].Function_Name) {

          case "Manage Contacts":
            this.allowManageContact = true;
            break;

          case "Repair Request":
            this.allowRepairRequest = true;
            break;

          case "Ibase Update":
            this.allowIbaseUpdate = true;
            break;

        }
      }

    }

    this._scrollTarg = document.getElementById("scroll_target");

    if(this.data.js.direct_site_data) {
      this._myAjaxCustomerResponse(this.data.js.direct_site_data);

      if(this.data.js.direct_site_data[0]['ibase_list'].length > 0) {

        if(this.data.js.defaultPartnerSearch == "00000002") {
          this._getIbaseList(this.data.js.direct_site_data[0]['ibase_list'][0]['ibaseID'], this.partnerTypeValue, this.data.js.direct_site_data[0]['ibase_list'][0]['custSAPId']);
        }

        else { 
          this._getIbaseList(this.data.js.direct_site_data[0]['ibase_list'][0]['ibaseID'], this.data.js.defaultPartnerSearch, this.data.js.direct_site_data[0]['ibase_list'][0]['partnerID']);
        }
      }
    }

    //for advanced search
    this._advanceSearchPanel = document.getElementById("rn_" + this.instanceID + "_advSearchPanel");
    this._advanceSearchPanelTrigger = document.getElementById("rn_" + this.instanceID + "_advSearchPanelTrigger");

    YAHOO.util.Event.on(this._advanceSearchPanelTrigger, "click", this._toggleAdvanceSearchPanel, null, this);

    //for Customer Search
    this._custCityField = document.getElementById("rn_" + this.instanceID + "_CustCityField");
    this._custStreetField = document.getElementById("rn_" + this.instanceID + "_CustStreetField");
    this._custNameField = document.getElementById("rn_" + this.instanceID + "_CustNameField");
    this._custPostalCode = document.getElementById("rn_" + this.instanceID + "_CustPostalCodeField");
    this._custIDField = document.getElementById("rn_" + this.instanceID + "_CustIDField");
    this._partnerTypeField = document.getElementById("rn_" + this.instanceID + "_PartnerTypeField");
    this._custMaterialField = document.getElementById("rn_" + this.instanceID + "_CustMaterial");

    //var x = document.getElementById("rn_" + this.instanceID + "_CountryParam");
    //alert(x.selectedIndex);

    //if(!this._custCityField) return;


    if(this.data.attrs.initial_focus && this._custCityField.focus)
        this._custCityField.focus();

    if(this.data.attrs.label_hint)
    {
        YAHOO.util.Event.addListener(this._custCityField, "focus", this._onFocus, null, this);
        YAHOO.util.Event.addListener(this._custCityField, "blur", this._onBlur, null, this);
    }

    YAHOO.util.Event.addListener("rn_" + this.instanceID + "_CustSubmit", "click", this._onCustSearch, null, this);

    var mySearchTabView = new YAHOO.widget.TabView("tvsearchcontainer");

    var demoTabView = new YAHOO.widget.TabView("demo");


    RightNow.Event.subscribe('evt_country_filter', this._setCountryValue, this);
    RightNow.Event.subscribe('evt_province_filter', this._setProvinceValue, this);

    YAHOO.util.Event.addListener( window, "load", this._initPage, this);

};

RightNow.Widget.CustomerSearch.prototype = {

    _initPage: function (evt, args) {
        var eoSites = new RightNow.Event.EventObject();
        eoSites.data.expandlist = new Array('accordionSites2');
        eoSites.data.showlist = new Array('accordionSites2');
        RightNow.Event.fire("evt_managePanel", eoSites);
    },

    _setProductData: function (evt, args) {

       this._myAjaxProductSearchResponse(args[0].data.productData);
    },

    _setPartnerTypeSelector: function (evt, args) {
        if (args[0].data.name == "partnerTypeSelect2")
            this.partnerSelectInstance = args[0].data.partnerTypeSelect;
    },

    _toggleAdvanceSearchPanel: function (evt, args) {
        var advState = this._advanceSearchPanel.style.display == 'none' ? 'block' : 'none'
        this._advanceSearchPanel.style.display = advState;
    },

    _setCountryValue: function (evt, args) {

        this.countryValue = args[0].filters.data;
        this.countryParamInstance = args[0].data.instanceID;

        //populate the states list

    },

    _setProvinceValue: function (evt, args) {

        this.provinceValue = args[0].filters.data;
        this.provinceParamInstance = args[0].data.instanceID;
    },

    _getActionLinks: function (type) {


        var htmlRepair = "";
        var htmlUpdate = "";
        var br = "";
        var htmlLinks = "";

        if (this.allowRepairRequest && type == "RepairRequest")
            htmlRepair = "<a id=\"lnkRepairRequest\" class=\"actionlink\">"+this.data.js.repairrequest+"</a>";
        if (this.allowIbaseUpdate && type == "IbaseUpdate") 
		    htmlUpdate = "<a id=\"lnkIbaseUpdate\" class=\"actionlink\">"+this.data.js.ibaseupdate+"</a>&nbsp;&nbsp;";
				
        if (htmlRepair != "" && htmlUpdate != "")
            br = "<br/>";
        htmlLinks = htmlRepair + br + htmlUpdate;

        return htmlLinks;

    },

    _getManageLink: function () {

        var htmlManage = "";
        var partnerType = document.getElementById(this.partnerSelectInstance);

        if (partnerType != null) {

            var partnerIDValue = partnerType.options[partnerType.selectedIndex].text;
            //2013.05.16 scott harris: allow manage contacts for any corporate partner
            if (this.allowManageContact && (partnerIDValue == this.data.js.direct || partnerIDValue == this.data.js.corporate)) {
                htmlManage = "<a class=\"actionlink\" id=\"lnkManageContacts\">"+this.data.js.managecontacts+"</a>";
            }

        }

        if (this.allowManageContact && this.data.js.isDirect == "Y")
            htmlManage = "<a class=\"actionlink\" id=\"lnkManageContacts\">"+this.data.js.managecontacts+"</a>";

        return htmlManage;
    },

    _onCustSearch: function () {

        this._resetDisplay();

        var eop = new RightNow.Event.EventObject();
        eop.data.hidelist = new Array('accordionProducts2');
        RightNow.Event.fire("evt_managePanel", eop);

        var mypostData = {};

        var partnerType = document.getElementById(this.partnerSelectInstance);
        var partnerIDValue = partnerType.options[partnerType.selectedIndex].value;

        if (partnerType.options[partnerType.selectedIndex].text == "--") {
            alert("A valid partner type is required on search.");
            return false;
        }

        switch (partnerType.options[partnerType.selectedIndex].text) {

            case this.data.js.direct :
                this.partnerTypeValue = "00000002";
                break;

            case "Enabling Partner":
                this.partnerTypeValue = "ZENABPRN";
                break;

            case "Manufacturing Partner":
                this.partnerTypeValue = "ZMVSMFG";
                break;

            case "Service Distributor":
                this.partnerTypeValue = "ZSVCDIST";
                break;

            case "Service Reseller":
                this.partnerTypeValue = "ZSVCRESL";
                break;

            case this.data.js.corporate :
                this.partnerTypeValue = "ZCORPACC";
                break;

            default:
                this.partnerTypeValue = "";

        }

        var searchByCountry = document.getElementById("rn_" + this.countryParamInstance + "_CountryParam");
        var searchByProvince = document.getElementById("rn_" + this.provinceParamInstance + "_ProvinceParam");
        mypostData['partner_type'] = this.partnerTypeValue;
        mypostData['sap_partner_id'] = partnerIDValue;

        if(this._custCityField != null)
          mypostData['city'] = this._custCityField.value;

        if(this._custNameField != null)
          mypostData['name'] = this._custNameField.value;

        if(this._custPostalCode != null)
          mypostData['zip'] = this._custPostalCode.value;

        if(this._custStreeField != null)
          mypostData['street'] = this._custStreetField.value;

        if(this._custMaterialField != null)
          mypostData['material_id'] = this._custMaterialField.value;

        mypostData['ibase_partner_id'] = this._custIDField.value;

        mypostData['internal'] = this.data.js.internal_user;

        if (searchByCountry != null && searchByCountry.options[searchByCountry.selectedIndex].value != "--") {
            mypostData['country'] = this.countryValue;
        }

        if (searchByProvince != null && searchByProvince.options[searchByProvince.selectedIndex].value != "--") {
            mypostData['province'] = this.provinceValue;
        }


        //this._custSearchField.value;

        this._waitPanel.show();

        this._overrideAjaxMethodCustomer();
        RightNow.Ajax.makeRequest("/cc/ibase_search/get_ibase_list", mypostData, {
            data: { eventName: "evt_getIbaseResponse" },
            successHandler: function (myresponse) {
                var resp = RightNow.JSON.parse(myresponse.responseText);

                this._waitPanel.hide();
                this._myAjaxCustomerResponse(resp);
                this._overrideAjaxMethodCustomerUnsubscribe();

            },
            failureHandler: function (myresponse) {
                this._waitPanel.hide();
            },
            timeout: 120000,
            scope: this
        });

    },

    _displayMultipleResult: function (evt, args) {

      this._resetDisplay();
      this._myAjaxCustomerResponse(args[0].data.result);
    },

    /**
    * Called when the search field is focused. Removes initial_value text
    */
    _onFocus: function () {
        if (this._custCityField.value === this.data.attrs.label_hint)
            this._custCityField.value = "";
    },
    /**
    * Called when the search field is blurred. Removes initial_value text
    */
    _onBlur: function () {
        if (this._custCityField.value === "")
            this._custCityField.value = "";
        //this._custCityField.value = this.data.attrs.label_hint;
    },

    _resetDisplay: function () {


        var eo = new RightNow.Event.EventObject();
        eo.data.hidelist = new Array('accordionComponents2', 'accordionComponentDetails2', 'accordionManageContacts2', 'accordionIbaseUpdate2', 'accordionRepairRequest2');
        RightNow.Event.fire("evt_managePanel", eo);


        //clear sites
        if (this.siteDataTable != null) {
            this.siteDataTable.deleteRows(0, this.siteDataTable.getRecordSet()._records.length);
        }

        //clear products 
        if (this.productDataTable != null) {
            this.productDataTable.deleteRows(0, this.productDataTable.getRecordSet()._records.length);
        }

        //clear subcomponents
        if (this.subcomponentsDataTable != null) {
            this.subcomponentsDataTable.deleteRows(0, this.subcomponentsDataTable.getRecordSet()._records.length);
        }


        //clear data from contracts table
        if (this.contractsDataTable != null) {
            this.contractsDataTable.deleteRows(0, this.contractsDataTable.getRecordSet()._records.length);
        }


        var tblEquipmentSite = document.getElementById("equipment_site_info2");
        if (tblEquipmentSite.rows.length > 0) {
            //clear equipment site info
            while (tblEquipmentSite.rows.length > 0) {
                tblEquipmentSite.removeChild(tblEquipmentSite.firstChild);
            }
        }

        var tblPayer = document.getElementById("contract_payer_info2");
        if (tblPayer.rows.length > 0) {
            //clear payer info
            while (tblPayer.rows.length > 0) {
                tblPayer.removeChild(tblPayer.firstChild);
            }
        }

    },

    _resetProductDisplay: function () {


        var eo = new RightNow.Event.EventObject();
        eo.data.hidelist = new Array('accordionComponents2', 'accordionComponentDetails2', 'accordionManageContacts2', 'accordionIbaseUpdate2', 'accordionRepairRequest2');
        RightNow.Event.fire("evt_managePanel", eo);

        //clear products
        if (this.productDataTable != null) {
            this.productDataTable.deleteRows(0, this.productDataTable.getRecordSet()._records.length);
        }

        //clear subcomponents
        if (this.subcomponentsDataTable != null) {
            this.subcomponentsDataTable.deleteRows(0, this.subcomponentsDataTable.getRecordSet()._records.length);
        }


        //clear data from contracts table
        if (this.contractsDataTable != null) {
            this.contractsDataTable.deleteRows(0, this.contractsDataTable.getRecordSet()._records.length);
        }


        var tblEquipmentSite = document.getElementById("equipment_site_info2");
        if (tblEquipmentSite.rows.length > 0) {
            //clear equipment site info
            while (tblEquipmentSite.rows.length > 0) {
                tblEquipmentSite.removeChild(tblEquipmentSite.firstChild);
            }
        }

        var tblPayer = document.getElementById("contract_payer_info2");
        if (tblPayer.rows.length > 0) {
            //clear payer info
            while (tblPayer.rows.length > 0) {
                tblPayer.removeChild(tblPayer.firstChild);
            }
        }

    },


    _myAjaxIbaseResponse: function (searchArgs) {


        var actionRRLink = this._getActionLinks("RepairRequest");
        var actionIULink = this._getActionLinks("IbaseUpdate");

        //searchArgs[0]['products'][0]['hasActiveContract']
        var repairCustomFormatter = function (elCell, oRecord, oColumn, oData) {
            if (oRecord._oData.hasActiveContract == "Y" && oRecord._oData.contract.toLowerCase().indexOf("parts only") == -1)
                elCell.innerHTML = actionRRLink + "</br>" + actionIULink;
            else
                elCell.innerHTML = actionIULink;

        };

        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.repairCustom = repairCustomFormatter;


        //parse
        productDataSource = new YAHOO.util.DataSource(searchArgs[0]['products']);
       
        productDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

        productDataSource.responseSchema = {
            resultsList: "", // String pointer to result data
            fields: [
                { key: "componentID" },
                { key: "contract" },
                { key: "description" },
                { key: "startDate" },
                { key: "endDate" },
                { key: "knum" },
                { key: "material" },
                { key: "sn" },
                { key: "repair" },
                { key: "hasActiveContract" },
                { key: "partnerType" },
                { key: "requestingPartner" }
                ]
        };


        var productColumnDefs = [
                { key: "knum", label: this.data.js.prodidentifier, sortable: false },
                { key: "sn", label: this.data.js.serialnumber, sortable: false },
                { key: "description", label: this.data.js.pic_proddescription, sortable: false },
                { key: "contract", label: this.data.js.pic_entitlement, sortable: false },
                { key: "startDate", label: this.data.js.pic_contractstart,  minWidth: 75, sortable: false },
                { key: "endDate", label: this.data.js.pic_contractend,  minWidth: 75, sortable: false },
                { key: "repair", label: this.data.js.pid_action, minWidth: 120, sortable: false, formatter: "repairCustom" }
             ];


        this.productDataTable = new YAHOO.widget.DataTable("rn_" + this.instanceID + "_div_producttable", productColumnDefs, productDataSource);

        if(searchArgs[0]['products'].length > 0) {
          var eo = new RightNow.Event.EventObject();
          eo.data.expandlist = new Array('accordionProducts2');
          eo.data.showlist = new Array('accordionProducts2');
          RightNow.Event.fire("evt_managePanel", eo);
        }
        //events
        this.productDataTable.set("selectionMode", "single");

        this.productDataTable.subscribe("linkClickEvent", this._linkClicked, searchArgs[0]['products'], this);
        this.productDataTable.subscribe("rowClickEvent", this._productSelectedHandler, searchArgs[0]['products'], this);  //pass array of products
        this.productDataTable.subscribe("rowMouseoverEvent", this.productDataTable.onEventHighlightRow);
        this.productDataTable.subscribe("rowMouseoutEvent", this.productDataTable.onEventUnhighlightRow);
        this.productDataTable.subscribe("rowClickEvent", this.productDataTable.onEventSelectRow);

    },

    _myAjaxCustomerResponse: function (searchArgs) {


        // First check for errors
        var result = searchArgs;
        if (!result) {
            alert("Unknown Ajax Error");
        } else if (result.status === 0) {
            alert("No Results Found.");
        } else if (result.status === 99) {
            alert(result.message);
        } else if (result.status === 1) {

            var aSite = new Array();
            for (var z = 0; z < searchArgs[0]['ibase_list'].length; z++) {
                aSite[z] = searchArgs[0]['ibase_list'][z];
            }


            if(this.data.js.internal_user == 'N') {
			var displaytxt = this.data.js.selectsiterecord;
            var _showManageContacts = function (mArgs) {

                if(this._oRecordSet.getLength() > 1) {
                  alert(displaytxt);
                  return false;

                }


                var oRec = this.getRecord(mArgs.target);
                var eo = new RightNow.Event.EventObject();
                eo.data.expandlist = new Array('accordionManageContacts2');
                eo.data.showlist = new Array('accordionManageContacts2');
                eo.data.hidelist = new Array('accordionIbaseUpdate2', 'accordionRepairRequest2');
                RightNow.Event.fire("evt_managePanel", eo);
                (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelManageContacts2')[0]).focus();
                //VS - Keep an eye on this as it may need to be removed. Added to fix issue with manage contacts panel not being displayed
                //An event is firing after this manage panel event that ends up hiding the manage contacts panel.
                //fire for selectedOrg 
                var reo = new RightNow.Event.EventObject();
                reo.data.selectedOrg = oRec.getData().orgID;           
				RightNow.Event.fire("evt_selectedOrgToManage", reo);

                return false;
            };
            }


            if(this.data.js.internal_user == 'Y') {

              if(aSite[0]) {
              var reo = new RightNow.Event.EventObject();
              reo.data.selectedOrg = aSite[0]['orgID'];
              RightNow.Event.fire("evt_selectedOrgToManage", reo);

              var eoSite = new RightNow.Event.EventObject();
              eoSite.w_id = this.instanceID;
              eoSite.data.orgID = aSite[0]['orgID'];  //val of org id
              RightNow.Event.fire('evt_changeSite', eoSite);
              }

              var _showManageContacts = function (mArgs) {

                if(this._oRecordSet.getLength() > 1) {
                  alert("Select the site record row before attempting to manage contacts.");
                  return false;

                }

                var oRec = this.getRecord(mArgs.target);
                var eo = new RightNow.Event.EventObject();
                eo.data.expandlist = new Array('accordionManageContacts2');
                eo.data.showlist = new Array('accordionManageContacts2');
                eo.data.hidelist = new Array('accordionIbaseUpdate2', 'accordionRepairRequest2');
                RightNow.Event.fire("evt_managePanel", eo);
                (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelManageContacts2')[0]).focus();
                //VS - Keep an eye on this as it may need to be removed. Added to fix issue with manage contacts panel not being displayed
                //An event is firing after this manage panel event that ends up hiding the manage contacts panel.

                //fire for selectedOrg
                var reo = new RightNow.Event.EventObject();
                reo.data.selectedOrg = oRec.getData().orgID;
                RightNow.Event.fire("evt_selectedOrgToManage", reo);

                return false;
            };
            }

            var _selectCustomer = function () {
              alert("select customer");
              return true;
            };

            var htmlManageLink = this._getManageLink();
            //var htmlManageLink = ""; 

            var myCustomFormatter = function (elCell, oRecord, oColumn, oData) {
                elCell.innerHTML = htmlManageLink;
            };

            // Add the custom formatter to the shortcuts 
            YAHOO.widget.DataTable.Formatter.myCustom = myCustomFormatter;

            var siteDataSource = new YAHOO.util.DataSource(aSite);
            siteDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

            siteDataSource.responseSchema = {
                resultsList: "", // String pointer to result data
                fields: [
                { key: "OrgName" },
                { key: "street" },
                { key: "city" },
                { key: "zip" },
                { key: "province" },
                { key: "country" },
                { key: "customerID" },
                { key: "ibaseID" },
                { key: "name" },
                { key: "partnerfunction" },
                { key: "partnerID" },
                { key: "orgID" },
                { key: "manage" }
                ]
            };
            var siteColumnDefs = [
                { key: "customerID", label: this.data.js.sitecustid, sortable: false },
                { key: "name", label: this.data.js.mysites_name, sortable: false },
                { key: "street", label: this.data.js.mysites_street, sortable: false },
                { key: "city", label: this.data.js.mysites_city, sortable: false },
                { key: "zip", label: this.data.js.mysites_postalcode, sortable: false },
                { key: "province", label: this.data.js.mysites_province, sortable: false },
                { key: "country", label: this.data.js.mysites_country, sortable: false },
                { key: "manage", label: this.data.js.mysites_action, minWidth: 120, sortable: false, formatter: "myCustom" }
            ];

            this.siteDataTable = new YAHOO.widget.DataTable("div_sitetable2", siteColumnDefs, siteDataSource);

            var eoSites = new RightNow.Event.EventObject();
            eoSites.data.expandlist = new Array('accordionSites2');
            eoSites.data.showlist = new Array('accordionSites2');
            RightNow.Event.fire("evt_managePanel", eoSites);

            this.siteDataTable.set("selectionMode", "single");

            this.siteDataTable.subscribe("rowClickEvent", this._siteSelectedHandler, searchArgs, this);  //pass array of products
            this.siteDataTable.subscribe("rowMouseoverEvent", this.siteDataTable.onEventHighlightRow);
            this.siteDataTable.subscribe("rowMouseoutEvent", this.siteDataTable.onEventUnhighlightRow);
            //            this.siteDataTable.subscribe("rowClickEvent", this.siteDataTable.onEventSelectRow);

            this.siteDataTable.subscribe("linkClickEvent", _showManageContacts, this);

            //show site panel
            var eo = new RightNow.Event.EventObject();
            eo.data.showlist = new Array('accordionSites2');
            RightNow.Event.fire("evt_managePanel", eo);

        }
    },


    _myAjaxResponse: function (searchArgs) {



        // First check for errors
        var result = searchArgs;
        if (!result) {
            alert("Unknown Ajax Error");
        } else if (result.status === 1) {

            var aSite = new Array();
            //aSite[aSite.length] = searchArgs['Site'];

            for (var z = 0; z < searchArgs['Site'].length; z++) {

                aSite[z] = searchArgs['Site'][z];
            }


            var _showManageContacts = function () {
                var eo = new RightNow.Event.EventObject();
                eo.data.expandlist = new Array('accordionManageContacts');
                eo.data.showlist = new Array('accordionManageContacts');
                eo.data.hidelist = new Array('accordionIbaseUpdate2', 'accordionRepairRequest2');
                RightNow.Event.fire("evt_managePanel", eo);
                (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'ManageContacts2')[0]).focus();

            

                //VS - Keep an eye on this as it may need to be removed. Added to fix issue with manage contacts panel not being displayed
                //An event is firing after this manage panel event that ends up hiding the manage contacts panel.
            };


            var siteDataSource = new YAHOO.util.DataSource(aSite);
            siteDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

            siteDataSource.responseSchema = {
                resultsList: "", // String pointer to result data
                fields: [
                { key: "custSAPId" },
                { key: "OrgName" },
                { key: "street" },
                { key: "city" },
                { key: "zip" },
                { key: "province" },
                { key: "country" }
                ]
            };
//			alert(this.data.js.mysites_name);
            var siteColumnDefs = [
                { key: "custSAPId", label: this.data.js.sitecustid, sortable: false },
                { key: "OrgName", label: this.data.js.mysites_name, sortable: false },
                { key: "street", label: this.data.js.mysites_street, sortable: false },
                { key: "city", label: this.data.js.mysites_city, sortable: false },
                { key: "zip", label: this.data.js.mysites_postalcode, sortable: false },
                { key: "province", label: this.data.js.mysites_province, sortable: false },
                { key: "country", label: this.data.js.mysites_country, sortable: false }
              ];


            this.siteDataTable = new YAHOO.widget.DataTable("div_sitetable2", siteColumnDefs, siteDataSource);

            var eo = new RightNow.Event.EventObject();
            eo.data.expandlist = new Array('accordionSites', 'accordionProducts', 'accordionComponents', 'accordionComponentDetails');
            RightNow.Event.fire("evt_managePanel", eo);

            //events
            this.siteDataTable.set("selectionMode", "single");

            this.siteDataTable.subscribe("rowClickEvent", this._siteSelectedHandler, searchArgs, this);  //pass array of products
            this.siteDataTable.subscribe("rowMouseoverEvent", this.siteDataTable.onEventHighlightRow);
            this.siteDataTable.subscribe("rowMouseoutEvent", this.siteDataTable.onEventUnhighlightRow);
            this.siteDataTable.subscribe("rowClickEvent", this.siteDataTable.onEventSelectRow);
            //             this.siteDataTable.subscribe("linkClickEvent", _showManageContacts);

            //show site panel
            var eo = new RightNow.Event.EventObject();
            eo.data.showlist = new Array('accordionSites2');
            RightNow.Event.fire("evt_managePanel", eo);


        } else {
            // handle the failure, see if there is a result.message for an error
        }

    },


    _linkClicked: function (evt, eo) {

        var rows = this.productDataTable.getRecordSet();
        var record = this.productDataTable.getRecord(evt.target);
        var target_id = evt.target.id;

        if (target_id == "lnkRepairRequest") {

            var eo = new RightNow.Event.EventObject();
            eo.data.showlist = new Array('accordionRepairRequest2');
            eo.data.hidelist = new Array('accordionIbaseUpdate2', 'accordionManageContacts2');
            RightNow.Event.fire("evt_managePanel", eo);
            (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelRepairRequest2')[0]).focus();
        }

        if (target_id == "lnkIbaseUpdate") {
            var eo = new RightNow.Event.EventObject();
            eo.data.expandlist = new Array('accordionIbaseUpdate2');
            eo.data.showlist = new Array('accordionIbaseUpdate2');
            eo.data.hidelist = new Array('accordionRepairRequest2', 'accordionManageContacts2');
            eo.data.setfocus = 1;
            RightNow.Event.fire("evt_managePanel", eo);

        }
    },

    _productSelectedHandler: function (evt, eo) {

        var rows = this.productDataTable.getRecordSet();
        var record = this.productDataTable.getRecord(evt.target);

        //alert("knum = " + record.getData().knum);
        //alert(YAHOO.lang.dump(record.getData()));

        var mypostData = {};

        //VS - blowing up here. Don't see this value being used so commenting it
        //var searchBy = document.getElementById("selProdSearchBy").value;

        //Scott
        //mypostData['product_id'] = record.getData().knum;  //.componentID; // '6456';
        mypostData['product_id'] = record.getData().componentID; // '6456';
        //mypostData['ibase_search'] = 'knum';
        mypostData['ibase_search'] = 'compid';
//	alert('partnerType before post'+record.getData().partnerType);
        mypostData['partner_type'] = record.getData().partnerType;
        mypostData['sap_partner_id'] = record.getData().requestingPartner;

        this._waitPanel.show();
        this._overrideProductSearchAjaxMethod();

        RightNow.Ajax.makeRequest("/cc/ibase_search/get_product", mypostData, {
            data: { eventName: "evt_getProductResponse" },
            successHandler: function (myresponse) {
                var resp = RightNow.JSON.parse(myresponse.responseText);

                this._waitPanel.hide();
                this._myAjaxProductSearchResponse(resp);

            },
            failureHandler: function (myresponse) {
                this._waitPanel.hide();
            },
            scope: this,
            timeout: 120000
        });



    },


    _overrideProductSearchAjaxMethod: function () {
        this._overrideProductSearchAjaxMethodUnsubscribe();
        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_getProductResponse") {
                eo[0].url = '/cc/ibase_search/get_product';
            }
        }, this);
    },

    _overrideProductSearchAjaxMethodUnsubscribe: function () {
        RightNow.Event.unsubscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_getProductResponse") {
                eo[0].url = '/cc/ibase_search/get_product';
            }
        });
    },


    _myAjaxProductSearchResponse: function (searchArgs) {
        var actionRRLink = this._getActionLinks("RepairRequest");
        var actionIULink = this._getActionLinks("IbaseUpdate");

        //searchArgs[0]['products'][0]['hasActiveContract']
		var knowledgesearchtxt = this.data.js.knowledgesearch;
		
        var repairCustomFormatter = function (elCell, oRecord, oColumn, oData) {
		    var htmlProdLnk = "";
		    if (oRecord._oData.productHier != "") 
			    htmlProdLnk = "<a class=\"actionlink\" id=\"lnkPROD\">"+knowledgesearchtxt+"</a> <br/>";
//			    htmlProdLnk = "<a target=\"_blank\" class=\"actionlink\" id=\"lnkPROD\" href=\"/app/answers/list/p/"+oRecord._oData.productHier+"\">Knowledge Search</a> <br/>";
            if (oRecord._oData.hasActiveContract == "Y" && oRecord._oData.plan.toLowerCase().indexOf("parts only") == -1)
                elCell.innerHTML = htmlProdLnk + actionRRLink + "</br>" + actionIULink;
            else
                elCell.innerHTML = htmlProdLnk + actionIULink;

        };

        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.repairCustom = repairCustomFormatter;


        var mfProduct = new Array();
        //mfProduct[0] = arguments[0][0]['products'][0];
        mfProduct[0] = searchArgs[0]['products'][0];

        var productDataSource = new YAHOO.util.DataSource(mfProduct);

        productDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

        productDataSource.responseSchema = {
            resultsList: "", // String pointer to result data
            fields: [
                { key: "ID" },
                { key: "Name" },
                { key: "SN" },
                { key: "SAPID" },
                { key: "repair" },
                { key: "svcDelivery" },
                { key: "compID" },
                { key: "sapProdID" },
                { key: "productHier" },
                { key: "plan" },
                { key: "planStart" },
                { key: "planEnd" },
                { key: "sds" },
                { key: "sp" },
                { key: "rp" },
                { key: "floorBldg" },
                { key: "remoteEOSL" },
                { key: "onsiteEOSL" },
                { key: "hasActiveContract"},
                { key: "enabling_partner"},
                { key: "mfg_partner"},
                { key: "distr_partner"},
                { key: "resell_partner"},
                { key: "direct_partner"},
                { key: "corporate_partner"}
                ]
        };

        var productColumnDefs = [
                { key: "ID", label: this.data.js.prodidentifier, sortable: false },
                { key: "SN", label: this.data.js.serialnumber, sortable: false },
                { key: "Name", label: this.data.js.pic_proddescription, sortable: false },
                { key: "plan", label: this.data.js.pic_entitlement, sortable: false },
                { key: "planStart", label: this.data.js.pic_contractstart, minWidth: 75, sortable: false },
                { key: "planEnd", label: this.data.js.pic_contractend, minWidth: 75, sortable: false },
                { key: "repair", label: this.data.js.pid_action, minWidth: 120, sortable: false, formatter: "repairCustom" }
             ];

        this.productDataTable = new YAHOO.widget.DataTable("rn_" + this.instanceID + "_div_producttable", productColumnDefs, productDataSource);

        var eo = new RightNow.Event.EventObject();
        eo.data.expandlist = new Array('accordionComponents2', 'accordionComponentDetails2');
        eo.data.showlist = new Array('accordionComponents2', 'accordionComponentDetails2');
        RightNow.Event.fire("evt_managePanel", eo);

        var eo_lbl = new RightNow.Event.EventObject();
        eo_lbl.data.headerlabellist = [{ 'name': 'accordionComponentDetails2', 'value': this.data.js.prodidentifierdetails}];
        RightNow.Event.fire("evt_updatePanelHeaderLabel", eo_lbl);


        this.productDataTable.set("selectionMode", "single");        this.productDataTable.set("selectionMode", "single");

        this.productDataTable.subscribe("linkClickEvent", function (oArgs) {

            var oRec = this.getRecord(oArgs.target);
            //alert(YAHOO.lang.dump(oRec.getData()) );
			//alert ('in _myAjaxProductSearchResponse prod is '+oRec.getData().productHier);
            //Knowledge link  action
            if (oArgs.target.id == "lnkPROD") {
			   var detailsURL = '/app/answers/list/p/'+oRec.getData().productHier;
			   window.open(detailsURL, "_blank");
			}

            //Ibase Update action
            if (oArgs.target.id == "lnkIbaseUpdate") {

                //send data to form to pre-fill
                var eor = new RightNow.Event.EventObject();
                eor.data.knum = oRec.getData().ID;
                eor.data.sn = oRec.getData().SN;
                eor.data.equip_id = oRec.getData().compID;
                eor.data.sap_prod_id = oRec.getData().sapProdID;
                eor.data.sold_to = oRec.getData().SAPID;
                eor.data.enablingPartner = oRec.getData().enabling_partner;
                eor.data.mfgPartner = oRec.getData().mfg_partner;
                eor.data.distrPartner = oRec.getData().distr_partner;
                eor.data.resellPartner = oRec.getData().resell_partner;
                eor.data.directPartner = oRec.getData().direct_partner;
                eor.data.corporatePartner = oRec.getData().corporate_partner;

                eor.data.ibase_product_hier = oRec.getData().productHier;                
				RightNow.Event.fire("evt_populateIbaseUpdateData", eor);

                var eo = new RightNow.Event.EventObject();
                eo.data.expandlist = new Array('accordionIbaseUpdate2');
                eo.data.showlist = new Array('accordionIbaseUpdate2');
                eo.data.hidelist = new Array('accordionRepairRequest2', 'accordionManageContacts2');
                eo.data.setfocus = 1;
                RightNow.Event.fire("evt_managePanel", eo);
                //alert('found: ' + YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelIbaseUpdate2').length + ' elements');
                (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelIbaseUpdate2')[0]).focus();
                //var mysel = document.getElementById("rn_ContactSelect_196_Options");
                //mysel.focus();
            }

            //Repair Request action
            //alert("target.id = " + oArgs.target.id);
            if (oArgs.target.id == "lnkRepairRequest") {

                var eo = new RightNow.Event.EventObject();
                eo.data.showlist = new Array('accordionRepairRequest2');
                eo.data.hidelist = new Array('accordionIbaseUpdate2', 'accordionManageContacts2');
                RightNow.Event.fire("evt_managePanel", eo);
                (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelRepairRequest2')[0]).focus();

                //send data to form to pre-fill
                var eor = new RightNow.Event.EventObject();
                eor.data.sds = oRec.getData().sds;
                eor.data.knum = oRec.getData().ID;
                eor.data.sn = oRec.getData().SN;
                eor.data.sp = oRec.getData().sp;
                eor.data.rp = oRec.getData().rp;
                eor.data.equip_id = oRec.getData().compID;
                eor.data.sap_prod_id = oRec.getData().sapProdID;
                eor.data.sold_to = oRec.getData().SAPID;
                eor.data.ibase_product_hier = oRec.getData().productHier;
				//alert('prodHier is '+eor.data.ibase_product_hier);				
                eor.data.cust_sapid = oRec.getData().SAPID;
                eor.data.remoteEOSL = oRec.getData().remoteEOSL;
                eor.data.onsiteEOSL = oRec.getData().onsiteEOSL;

                eor.data.enablingPartner = oRec.getData().enabling_partner;
                eor.data.mfgPartner = oRec.getData().mfg_partner;
                eor.data.distrPartner = oRec.getData().distr_partner;
                eor.data.resellPartner = oRec.getData().resell_partner;
                eor.data.directPartner = oRec.getData().direct_partner;
                eor.data.corporatePartner = oRec.getData().corporate_partner;


                RightNow.Event.fire("evt_populateRepairData", eor);
            }  //end if lnkRepairRequest

        });  //end linkClickEvent function


        //show subcomponents
        var aProduct = new Array();
        var cnt = 0;

        for (var i = 0; i < arguments[0][0]['products'].length; i++) {

            if (arguments[0][0]['products'][i]['mf'] == 'N') {
                aProduct[cnt++] = arguments[0][0]['products'][i];
            }

        }
		var ibasetxt = this.data.js.ibaseupdate;
        var ibaseCustomFormatter = function (elCell, oRecord, oColumn, oData) {
            elCell.innerHTML = "<a class=\"actionlink\" id=\"lnkIbaseUpdateComponent2\">"+ibasetxt+"</a>&nbsp;&nbsp;";
        };

        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.ibaseCustom = ibaseCustomFormatter;

        var subDataSource = new YAHOO.util.DataSource(aProduct);
        subDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

        subDataSource.responseSchema = {
            resultsList: "", // String pointer to result data
            fields: [
                  { key: "compID" },
                  { key: "SN" },
                  { key: "Name" },
                  { key: "plan" },
                  { key: "planStart" },
                  { key: "planEnd" },
                  { key: "floorBldg" }
                  ]
        };

        var subColumnDefs = [
                  { key: "SN", label: this.data.js.serialnumber, sortable: false },
                  { key: "Name", label: this.data.js.pic_proddescription, sortable: true },
                  { key: "plan", label: this.data.js.pic_entitlement, sortable: false },
                  { key: "planStart", label: this.data.js.pic_contractstart, sortable: false },
                  { key: "planEnd", label: this.data.js.pic_contractend, sortable: false },
                  { key: "", label: this.data.js.pid_action, minWidth: 120, sortable: false, formatter: "ibaseCustom" }
                ];

        this.subcomponentsDataTable = new YAHOO.widget.DataTable("div_componentstable", subColumnDefs, subDataSource);

        //show meters
        //
        //meter data
        //
        var eoc = new RightNow.Event.EventObject();
        eoc.data.meterData = mfProduct[0]['meters'];
        eoc.data.compID = mfProduct[0]['compID'];  //product identifier

        RightNow.Event.fire("evt_displayCurrentMeter", eoc);

        //
        //experimental - meter history
        //
        var eoh = new RightNow.Event.EventObject();
        eoh.data.meterData = mfProduct[0]['meter_history'];
        RightNow.Event.fire("evt_displayMeterHistory", eoh);

        var mylink = new YAHOO.util.Element('lnkIbaseUpdateComponent2');
        //mylink.on('click', this._doIbaseUpdate);

        //var ibaseUpdateByComponent = function (e) {
        var ibaseUpdateByComponent = function (evt, eo) {
            //send data to form to pre-fill


            //scott
            var rows = this.subcomponentsDataTable.getRecordSet();
            var record = this.subcomponentsDataTable.getRecord(evt.target);
            //alert(YAHOO.lang.dump(record.getData()));


            var eor = new RightNow.Event.EventObject();
            eor.data.knum = mfProduct[0].ID;
            eor.data.sn = mfProduct[0].SN;
            eor.data.equip_id = record.getData().compID; //mfProduct[0].compID;
            eor.data.sap_prod_id = mfProduct[0].sapProdID;
            eor.data.sold_to = mfProduct[0].SAPID;
            eor.data.ibase_product_hier = mfProduct[0].productHier;
            eor.data.enablingPartner = mfProduct[0].enabling_partner;
            eor.data.mfgPartner = mfProduct[0].mfg_partner;
            eor.data.distrPartner = mfProduct[0].distr_partner;
            eor.data.resellPartner = mfProduct[0].resell_partner;
            eor.data.directPartner = mfProduct[0].direct_partner;
            eor.data.corporatePartner = mfProduct[0].corporate_partner;

            RightNow.Event.fire("evt_populateIbaseUpdateData", eor);

            var eo = new RightNow.Event.EventObject();
            eo.data.expandlist = new Array('accordionIbaseUpdate2');
            eo.data.showlist = new Array('accordionIbaseUpdate2');
            eo.data.hidelist = new Array('accordionRepairRequest2', 'accordionManageContacts2');
            RightNow.Event.fire("evt_managePanel", eo);
            (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelIbaseUpdate2')[0]).focus();
            //var mysel = document.getElementById("rn_ContactSelect_196_Options");
            //mysel.focus();

        };
        mylink.on('click', ibaseUpdateByComponent, this.subcomponentsDataTable, this);


        //end show subcomponents
        this.productDataTable.subscribe("rowMouseoverEvent", this.productDataTable.onEventHighlightRow);
        this.productDataTable.subscribe("rowMouseoutEvent", this.productDataTable.onEventUnhighlightRow);
        this.productDataTable.subscribe("rowClickEvent", this._productHandler, arguments[0], this);  //pass array


        //show contract details

        //clear data from contracts table
        if (this.contractsDataTable != null) {
            this.contractsDataTable.destroy();
        }

        var aContracts = mfProduct[0]['contracts'];

        contractDataSource = new YAHOO.util.DataSource(aContracts);

        contractDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

        contractDataSource.responseSchema = {
            resultsList: "", // String pointer to result data
            fields: [
                    { key: "description" },
                    { key: "id" },
                    { key: "type" },
                    { key: "serviceProfileDesc" },
                    { key: "responseProfileDesc" },
                    { key: "startDate" },
                    { key: "endDate" },
                    { key: "payerID" },
                    { key: "status" },
                    { key: "contractID" }
                    ]
        };


        var contractColumnDefs = [
                  { key: "description", label: this.data.js.pide_product, sortable: false },
                  { key: "serviceProfileDesc", label: this.data.js.pide_coveragetime, sortable: false },
                  { key: "responseProfileDesc", label: this.data.js.pide_prioritizedresponse, sortable: false },
                  { key: "startDate", label: this.data.js.pide_contractstart, sortable: false },
                  { key: "endDate", label: this.data.js.pide_contractend, sortable: false },
                  { key: "type", label: this.data.js.pide_contracttype, sortable: false },
                  { key: "status", label: this.data.js.pide_contractstatus, sortable: false },
                  { key: "contractID", label: this.data.js.pide_contractid, sortable: false }
                  ];

        this.contractsDataTable = new YAHOO.widget.DataTable("div_contracts2", contractColumnDefs, contractDataSource);

        var myTabView = new YAHOO.widget.TabView("tvcontainer2");

        var tblPayer = document.getElementById("contract_payer_info2");
        var tblEquipmentSite = document.getElementById("equipment_site_info2");

        //clear equipment site info
        while (tblEquipmentSite.rows.length > 0) {
            tblEquipmentSite.deleteRow(0);
        }

        //2012.12.07 scott harris: to scroll to display form
        var yval = YAHOO.util.Dom.getY(this._scrollTarg);
        window.scrollTo(0,yval-600);

        //clear payer info
        while (tblPayer.rows.length > 0) {
            tblPayer.deleteRow(0);
        }

        //
        //experimental - meter history
        //
         var eoh = new RightNow.Event.EventObject();
        eoh.data.meterData = mfProduct[0]['meter_history'];
        RightNow.Event.fire("evt_displayMeterHistory", eoh);

        var eoc = new RightNow.Event.EventObject();
        eoc.data.meterData = mfProduct[0]['meters'];
        eoc.data.compID = mfProduct[0]['compID'];
        RightNow.Event.fire("evt_displayCurrentMeter", eoc);

        //Payer
        var pRow = tblPayer.insertRow(-1);
        var aCell = pRow.insertCell(-1);
        var bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.pidscpi_kodakcustnum;
        //CIH: 24/12/2014: Code change to display the ID as Outside ENT id
        bCell.innerHTML = arguments[0]['Payer'].OUTSIDEENTID;
       
       // bCell.innerHTML = arguments[0]['Payer'].SAPID;


        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.pidscpi_customername;
        bCell.innerHTML = arguments[0]['Payer'].OrgName;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.pidscpi_address;
        bCell.innerHTML = arguments[0]['Payer'].street;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = "";
        bCell.innerHTML = arguments[0]['Payer'].city;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = "";
        bCell.innerHTML = arguments[0]['Payer'].province;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = "";
        bCell.innerHTML = arguments[0]['Payer'].zip;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = "";
        bCell.innerHTML = arguments[0]['Payer'].country;

        //equipment site
        if (arguments[0] != null) {
            var eRow = tblEquipmentSite.insertRow(-1);
            var cCell = eRow.insertCell(-1);
            var dCell = eRow.insertCell(-1);
            cCell.innerHTML = this.data.js.pidsi_floorbldg;
            dCell.innerHTML = mfProduct[0].floorBldg;

            var eRow = tblEquipmentSite.insertRow(-1);
            var cCell = eRow.insertCell(-1);
            var dCell = eRow.insertCell(-1);
            cCell.innerHTML = this.data.js.pidsi_entrancedoor;
            dCell.innerHTML = mfProduct[0].door;

            var eRow = tblEquipmentSite.insertRow(-1);
            var cCell = eRow.insertCell(-1);
            var dCell = eRow.insertCell(-1);
            cCell.innerHTML = this.data.js.pidsi_addaddress;
            dCell.innerHTML = mfProduct[0].addlAddress;
        }

        //end show contract details


        //send data to form to pre-fill
        //necessary to fire event from here because row select intercepts the click event
        var eor = new RightNow.Event.EventObject();
        eor.data.sds = mfProduct[0].sds;
        eor.data.knum = mfProduct[0].ID;
        eor.data.sn = mfProduct[0].SN;
        eor.data.sp = mfProduct[0].sp;
        eor.data.rp = mfProduct[0].rp;
        eor.data.equip_id = mfProduct[0].compID;
        eor.data.sap_prod_id = mfProduct[0].sapProdID;
        eor.data.sold_to = mfProduct[0].SAPID;
        eor.data.ibase_product_hier = mfProduct[0].productHier;
        eor.data.remoteEOSL = mfProduct[0].remoteEOSL;
        eor.data.onsiteEOSL = mfProduct[0].onsiteEOSL;

        eor.data.enablingPartner = mfProduct[0].enabling_partner;
        eor.data.mfgPartner = mfProduct[0].mfg_partner;
        eor.data.distrPartner = mfProduct[0].distr_partner;
        eor.data.resellPartner = mfProduct[0].resell_partner;
        eor.data.directPartner = mfProduct[0].direct_partner;
        eor.data.corporatePartner = mfProduct[0].corporate_partner;
        
        RightNow.Event.fire("evt_populateRepairData", eor);


        eor = new RightNow.Event.EventObject();
        eor.data.knum = mfProduct[0].ID;
        eor.data.sn = mfProduct[0].SN;
        eor.data.equip_id = mfProduct[0].compID;
        eor.data.sap_prod_id = mfProduct[0].sapProdID;
        eor.data.sold_to = mfProduct[0].SAPID;

        eor.data.enablingPartner = mfProduct[0].enabling_partner;
        eor.data.mfgPartner = mfProduct[0].mfg_partner;
        eor.data.distrPartner = mfProduct[0].distr_partner;
        eor.data.resellPartner = mfProduct[0].resell_partner;
        eor.data.directPartner = mfProduct[0].direct_partner;
        eor.data.corporatePartner = mfProduct[0].corporate_partner;

        eor.data.ibase_product_hier = mfProduct[0].productHier;
        RightNow.Event.fire("evt_populateIbaseUpdateData", eor);


        //show component details panel 
        var eo = new RightNow.Event.EventObject();
        eo.data.showlist = new Array('accordionComponents2', 'accordionComponentDetails2');
        eo.data.expandlist = new Array('accordionComponents2', 'accordionComponentDetails2');
        RightNow.Event.fire("evt_managePanel", eo);



        this.subcomponentsDataTable.set("selectionMode", "single");
        this.subcomponentsDataTable.subscribe("rowMouseoverEvent", this.subcomponentsDataTable.onEventHighlightRow);
        this.subcomponentsDataTable.subscribe("rowMouseoutEvent", this.subcomponentsDataTable.onEventUnhighlightRow);

        this.subcomponentsDataTable.subscribe("rowClickEvent", this._myComponentHandler, arguments[0], this);  //pass array


    },

    _productHandler: function (evt, eo) {

        //clear data from contracts table
        if (this.contractsDataTable != null) {
            this.contractsDataTable.destroy();
        }

        var myTabView = new YAHOO.widget.TabView("tvcontainer2");
        var tblPayer = document.getElementById("contract_payer_info2");
        var tblEquipmentSite = document.getElementById("equipment_site_info2");

        //clear equipment site info
        while (tblEquipmentSite.rows.length > 0) {
            tblEquipmentSite.deleteRow(0);
        }
        //clear payer info
        while (tblPayer.rows.length > 0) {
            tblPayer.deleteRow(0);
        }

        //fill in contracts
        var mfProduct = new Array();
        mfProduct[0] = arguments[1][0]['products'][0];

        var eoc = new RightNow.Event.EventObject();
        eoc.data.meterData = mfProduct[0]['meters'];
        eoc.data.compID = mfProduct[0]['compID'];  //product identifier
        RightNow.Event.fire("evt_displayCurrentMeter", eoc);

        var eoh = new RightNow.Event.EventObject();
        eoh.data.meterData = mfProduct[0]['meter_history'];
        RightNow.Event.fire("evt_displayMeterHistory", eoh);

        var aContracts = mfProduct[0]['contracts'];

        contractDataSource = new YAHOO.util.DataSource(aContracts);

        contractDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

        contractDataSource.responseSchema = {
            resultsList: "", // String pointer to result data
            fields: [
                    { key: "description" },
                    { key: "id" },
                    { key: "type" },
                    { key: "serviceProfileDesc" },
                    { key: "responseProfileDesc" },
                    { key: "startDate" },
                    { key: "endDate" },
                    { key: "payerID" },
                    { key: "status" },
                    { key: "contractID" }
                    ]
        };


        var contractColumnDefs = [
                  { key: "description", label: this.data.js.sitecustid, sortable: false },
                  { key: "serviceProfileDesc", label: this.data.js.pide_coveragetime, sortable: false },
                  { key: "responseProfileDesc", label: this.data.js.pide_prioritizedresponse, sortable: false },
                  { key: "startDate", label: this.data.js.pide_contractstart, sortable: false },
                  { key: "endDate", label: this.data.js.pide_contractend, sortable: false },
                  { key: "type", label: this.data.js.pide_contracttype, sortable: false },
                  { key: "status", label: this.data.js.pide_contractstatus, sortable: false },
                  { key: "contractID", label: this.data.js.pide_contractid, sortable: false }
                  ];

        this.contractsDataTable = new YAHOO.widget.DataTable("div_contracts2", contractColumnDefs, contractDataSource);


        //Payer
         var outsideEntId=mfProduct[0].support_plans[0]['outsideEntId'];
        
        //alert("ousideENT Id1: "+outsideEntId);
        if(outsideEntId!=''){
        	//Nitesh S
        }
        
        var pRow = tblPayer.insertRow(-1);
        var aCell = pRow.insertCell(-1);
        var bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.pidscpi_kodakcustnum;
       // bCell.innerHTML = arguments[1]['Payer'].SAPID;
       //Nitesh S
		bCell.innerHTML = arguments[1]['Payer'].OUTSIDEENTID;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.pidscpi_customername;
        bCell.innerHTML = arguments[1]['Payer'].OrgName;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.pidscpi_address;
        bCell.innerHTML = arguments[1]['Payer'].street;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.mysites_city;
        bCell.innerHTML = arguments[1]['Payer'].city;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.mysites_province;
        bCell.innerHTML = arguments[1]['Payer'].province;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.mysites_postalcode;
        bCell.innerHTML = arguments[1]['Payer'].zip;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.mysites_country;
        bCell.innerHTML = arguments[1]['Payer'].country;

        //equipment site
        if (arguments[1] != null) {
            var eRow = tblEquipmentSite.insertRow(-1);
            var cCell = eRow.insertCell(-1);
            var dCell = eRow.insertCell(-1);
            cCell.innerHTML = this.data.js.pidsi_floorbldg;
            dCell.innerHTML = mfProduct[0]['floorBldg'];

        }

        var eo_lbl = new RightNow.Event.EventObject();
        eo_lbl.data.headerlabellist = [{ 'name': 'accordionComponentDetails2', 'value': this.data.js.prodidentifierdetails}];
        RightNow.Event.fire("evt_updatePanelHeaderLabel", eo_lbl);

        //testing below here
        //send data to form to pre-fill
        /*
        var eor = new RightNow.Event.EventObject();
        eor.data.sds = oRec.getData().sds;
        eor.data.knum = oRec.getData().ID;
        eor.data.sn = oRec.getData().SN;
        eor.data.sp = oRec.getData().sp;
        eor.data.rp = oRec.getData().rp;
        eor.data.equip_id = oRec.getData().compID;
        eor.data.sap_prod_id = oRec.getData().sapProdID;
        eor.data.sold_to = oRec.getData().SAPID;
        eor.data.ibase_product_hier = oRec.getData().productHier;

        RightNow.Event.fire("evt_populateRepairData", eor);
        */
    },


    _myComponentHandler: function (evt, eo) {


        var rows = this.subcomponentsDataTable.getRecordSet();
        var record = this.subcomponentsDataTable.getRecord(evt.target);

        //clear data from contracts table
        if (this.contractsDataTable != null) {
            this.contractsDataTable.destroy();
        }

        var aContracts = new Array();
        var aMeters = new Array();
        var hMeters = new Array();

        //record._oData.compID);
        //alert(YAHOO.lang.dump(record.getData()));
        for (var i = 0; i < arguments[1][0]['products'].length; i++) {

            if (arguments[1][0]['products'][i]['compID'] == record._oData.compID) {
                aContracts = arguments[1][0]['products'][i]['contracts'];
                aMeters = arguments[1][0]['products'][i]['meters'];
                hMeters = arguments[1][0]['products'][i]['meter_history'];
            }

        }

        //show meters
        //
        //meter data
        //
        var eoc = new RightNow.Event.EventObject();
        eoc.data.meterData = aMeters;
        eoc.data.compID = record._oData.compID;
        RightNow.Event.fire("evt_displayCurrentMeter", eoc);

        //
        //experimental - meter history
        //
        var eoh = new RightNow.Event.EventObject();
        eoh.data.meterData = hMeters;
        RightNow.Event.fire("evt_displayMeterHistory", eoh);

        contractDataSource = new YAHOO.util.DataSource(aContracts);

        contractDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

        contractDataSource.responseSchema = {
            resultsList: "", // String pointer to result data
            fields: [
                    { key: "description" },
                    { key: "id" },
                    { key: "type" },
                    { key: "serviceProfileDesc" },
                    { key: "responseProfileDesc" },
                    { key: "startDate" },
                    { key: "endDate" },
                    { key: "payerID" },
                    { key: "status" },
                    { key: "contractID" }
                    ]
        };


        var contractColumnDefs = [
                  { key: "description", label: this.data.js.pide_product, sortable: false },
                  { key: "serviceProfileDesc", label: this.data.js.pide_coveragetime, sortable: false },
                  { key: "responseProfileDesc", label: this.data.js.pide_prioritizedresponse, sortable: false },
                  { key: "startDate", label: this.data.js.pide_contractstart, sortable: false },
                  { key: "endDate", label: this.data.js.pide_contractend, sortable: false },
                  { key: "type", label: this.data.js.pide_contracttype, sortable: false },
                  { key: "status", label: this.data.js.pide_contractstatus, sortable: false },
                  { key: "contractID", label: this.data.js.pide_contractid, sortable: false }
                  ];

        this.contractsDataTable = new YAHOO.widget.DataTable("div_contracts2", contractColumnDefs, contractDataSource);

        var myTabView = new YAHOO.widget.TabView("tvcontainer2");

        var tblPayer = document.getElementById("contract_payer_info2");
        var tblEquipmentSite = document.getElementById("equipment_site_info2");

        var eo_lbl = new RightNow.Event.EventObject();
        eo_lbl.data.headerlabellist = [{ 'name': 'accordionComponentDetails2', 'value': 'Component Details'}];
        RightNow.Event.fire("evt_updatePanelHeaderLabel", eo_lbl);

        //clear equipment site info
        while (tblEquipmentSite.rows.length > 0) {
            tblEquipmentSite.deleteRow(0);
        }

        //clear payer info
        while (tblPayer.rows.length > 0) {
            tblPayer.deleteRow(0);
        }

        //Payer
        var outsideEntId=mfProduct[0].support_plans[0]['outsideEntId'];
       //alert("ousideENT Id2: "+outsideEntId);
        var pRow = tblPayer.insertRow(-1);
        var aCell = pRow.insertCell(-1);
        var bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.pidscpi_kodakcustnum;
        //CIH: 24/12/2014: Code change to display the ID as Outside ENT id
       // bCell.innerHTML = arguments[1]['Payer'].SAPID;
        bCell.innerHTML = arguments[1]['Payer'].OUTSIDEENTID;


        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.pidscpi_customername;
        bCell.innerHTML = arguments[1]['Payer'].OrgName;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.pidscpi_address;
        bCell.innerHTML = arguments[1]['Payer'].street;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.mysites_city;
        bCell.innerHTML = arguments[1]['Payer'].city;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.mysites_regprovstate;
        bCell.innerHTML = arguments[1]['Payer'].province;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.mysites_postalcode;
        bCell.innerHTML = arguments[1]['Payer'].zip;

        pRow = tblPayer.insertRow(-1);
        aCell = pRow.insertCell(-1);
        bCell = pRow.insertCell(-1);
        aCell.innerHTML = this.data.js.mysites_country;
        bCell.innerHTML = arguments[1]['Payer'].country;

        //equipment site
        if (arguments[1] != null) {
            var eRow = tblEquipmentSite.insertRow(-1);
            var cCell = eRow.insertCell(-1);
            var dCell = eRow.insertCell(-1);
            cCell.innerHTML = this.data.js.pidsi_floorbldg;
            dCell.innerHTML = record._oData.floorBldg;

            var eRow = tblEquipmentSite.insertRow(-1);
            var cCell = eRow.insertCell(-1);
            var dCell = eRow.insertCell(-1);
            cCell.innerHTML = this.data.js.pidsi_entrancedoor;
            dCell.innerHTML = record._oData.door;


        }



    },

    _siteSelectedHandler: function (evt, eo) {

        var rows = this.siteDataTable.getRecordSet();
        var record = this.siteDataTable.getRecord(evt.target);

        //alert("clear product,details");
        this._resetProductDisplay();
        //alert(YAHOO.lang.dump(record.getData()) ); 
        //fire event to do product ibase list

        for (var i = rows._records.length; i >= 0; i--) {

            var x = rows._records[i];
            if (this.siteDataTable.getRecordIndex(x) != this.siteDataTable.getRecordIndex(record)) {
                this.siteDataTable.deleteRow(x);
            }
        }

        var eoSite = new RightNow.Event.EventObject();
        eoSite.w_id = this.instanceID;
        eoSite.data.orgID = record.getData().orgID;  //val of org id
        RightNow.Event.fire('evt_changeSite', eoSite);

        if(this.data.js.internal_user == "N")
          this._getIbaseList(record.getData().ibaseID, this.partnerTypeValue, record.getData().partnerID); //by logged in partner
        else
          this._getIbaseList(record.getData().ibaseID, this.partnerTypeValue, record.getData().customerID); //by selected ibase customer
    },

    _getIbaseList: function (ibaseID, functionID, partnerID) {

        //make ajax call and process

        this._overrideAjaxMethodIbase();

        var mypostData = {};
        mypostData['ibase_id'] = ibaseID;
        mypostData['partner_function'] = functionID; // 'ZSVCDIST'; functionID;
        mypostData['partner_id'] = partnerID;
        mypostData['internal'] = this.data.js.internal_user;

        this._waitPanel.show();

        RightNow.Ajax.makeRequest("/cc/ibase_search/get_ibase", mypostData, {
            data: { eventName: "evt_getIbaseResponse" },
            successHandler: function (myresponse) {
                this._waitPanel.hide();
                var resp = RightNow.JSON.parse(myresponse.responseText);

                if (resp.status === 1)
                    this._myAjaxIbaseResponse(resp);
                else {
                    //alert("No Results.");
                    this._showEmptyProductData();
                }
                this._waitPanel.hide();
                this._overrideAjaxMethodIbaseUnsubscribe();
                //if one product, then retrieve
                //if(resp[0]['products'].length == 1) {
                //  this._getProduct(resp[0]['products'][0]['compID']);
                //}

            },
            failureHandler: function (myresponse) {
                this._waitPanel.hide();
            },
            scope: this,
            timeout: 120000
        });

    },

    _getProduct: function (compid) {
//alert('in getProduct from CustomerSearch logic');
        this._overrideProductSearchAjaxMethod();

        var mypostData = {};

        mypostData['product_id'] = compid;
        mypostData['ibase_search'] = 'compid';

        this._waitPanel.show();

        RightNow.Ajax.makeRequest("/cc/ibase_search/get_product", mypostData, {
            data: { eventName: "evt_getProductResponse" },
            successHandler: function (myresponse) {
                var resp = RightNow.JSON.parse(myresponse.responseText);

                this._waitPanel.hide();
                this._myAjaxProductSearchResponse(resp);
                this._overrideProductSearchAjaxMethodUnsubscribe();

            },
            failureHandler: function (myresponse) {
                this._waitPanel.hide();
            },
            scope: this,
            timeout: 120000
        });


    },

    _overrideAjaxMethodIbase: function () {
        this._overrideAjaxMethodIbaseUnsubscribe();
        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
            eo[0].url = '/cc/ibase_search/get_ibase';
        }, this);
    },

    _overrideAjaxMethodIbaseUnsubscribe: function () {
        RightNow.Event.unsubscribe('on_before_ajax_request', function (evt, eo) {
            eo[0].url = '/cc/ibase_search/get_ibase';
        });
    },

    _overrideAjaxMethod: function () {
        RightNow.Event.unsubscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_getProductResponse") {
                eo[0].url = '/cc/ibase_search/get_product';
            }
        });

        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_getProductResponse") {
                eo[0].url = '/cc/ibase_search/get_product';
            }
        }, this);
    },

    _overrideAjaxMethodCustomer: function () {
        this._overrideAjaxMethodCustomerUnsubscribe();
        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_getIbaseResponse") {
                eo[0].url = '/cc/ibase_search/get_ibase_list';
            }
        }, this);
    },

    _overrideAjaxMethodCustomerUnsubscribe: function () {
        RightNow.Event.unsubscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_getIbaseResponse") {
                eo[0].url = '/cc/ibase_search/get_ibase_list';
            }
        });
    },

    _overrideAjaxMethodStates: function () {

        RightNow.Event.unsubscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_country_filter") {
                eo[0].url = '/cc/ibase_search/get_ibase_list';
            }
        });

        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
            if (eo[0].data.eventName == "evt_country_filter") {
                eo[0].url = '/cc/ibase_search/get_ibase_list';
            }
        }, this);
    },

    _createWaitPanel: function () {
        this._waitPanel =
                    new YAHOO.widget.Panel("waitCustomerSearch",
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

    },

    _showEmptyProductData: function () {

        var mfProduct = new Array();

        var productDataSource = new YAHOO.util.DataSource(mfProduct);

        productDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

        productDataSource.responseSchema = {
            resultsList: "", // String pointer to result data
            fields: [
                { key: "ID" },
                { key: "Name" },
                { key: "SN" },
                { key: "SAPID" },
                { key: "repair" },
                { key: "svcDelivery" },
                { key: "compID" },
                { key: "sapProdID" },
                { key: "productHier" },
                { key: "plan" },
                { key: "planStart" },
                { key: "planEnd" },
                { key: "sds" },
                { key: "sp" },
                { key: "rp" },
                { key: "floorBldg" },
                { key: "remoteEOSL" },
                { key: "onsiteEOSL" },
                { key: "hasActiveContract" }
                ]
        };

        var productColumnDefs = [
                { key: "ID", label: this.data.js.prodidentifier, sortable: false },
                { key: "SN", label: this.data.js.serialnumber, sortable: false },
                { key: "Name", label: this.data.js.pic_proddescription, sortable: false },
                { key: "plan", label: this.data.js.pic_entitlement, sortable: false },
                { key: "planStart", label: this.data.js.pic_contractstart,  minWidth: 75, sortable: false },
                { key: "planEnd", label: this.data.js.pic_contractend,  minWidth: 75, sortable: false },
                { key: "repair", label: this.data.js.pid_action, minWidth: 120, sortable: false, formatter: "repairCustom" }
             ];

        this.productDataTable = new YAHOO.widget.DataTable("rn_" + this.instanceID + "_div_producttable", productColumnDefs, productDataSource);
        var eo = new RightNow.Event.EventObject();
        eo.data.expandlist = new Array('accordionProducts2');
        RightNow.Event.fire("evt_managePanel", eo);
    }

};



