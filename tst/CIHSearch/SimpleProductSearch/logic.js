/* Originating Release: February 2012 */

RightNow.Widget.SimpleProductSearch = function(data, instanceID) {

    this.data = data;

    this.instanceID = instanceID;

 

    this.siteDataTable = null;

    this.productDataTable = null;

    this.subcomponentsDataTable = null;

    this.contractsDataTable = null;

    this._waitPanel = null;

    this.partnerSelectInstance = null;

    this.partnerTypeValue = "";



    this.allowManageContact = false;

    this.allowRepairRequest = false;

    this.allowIbaseUpdate = false;





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



    RightNow.Event.subscribe('evt_registerPartnerType', this._setPartnerTypeSelector, this);



    //for Product Search

    this._searchField = document.getElementById("rn_" + this.instanceID + "_SearchField");

    if(!this._searchField) return;



    if(this.data.attrs.initial_focus && this._searchField.focus)

        this._searchField.focus();



    if(this.data.attrs.label_hint)

    {

        YAHOO.util.Event.addListener(this._searchField, "focus", this._onFocus, null, this);

        YAHOO.util.Event.addListener(this._searchField, "blur", this._onBlur, null, this);

    }

    YAHOO.util.Event.addListener("rn_" + this.instanceID + "_Submit", "click", this._onSearch, null, this);





    var mySearchTabView = new YAHOO.widget.TabView("tvsearchcontainer");



    var demoTabView = new YAHOO.widget.TabView("demo");



    this._createWaitPanel();





   

};



RightNow.Widget.SimpleProductSearch.prototype = {



   _setPartnerTypeSelector : function (evt, args) {

     if(args[0].data.name == "partnerTypeSelect1")

       this.partnerSelectInstance = args[0].data.partnerTypeSelect;

   },



    /**

    * Called when the user searches

    */

    _onSearch : function() {



        var mypostData = {};



               

     var partnerType = document.getElementById(this.partnerSelectInstance);

     var partnerIDValue = partnerType.options[partnerType.selectedIndex].value;



     if(partnerType.options[partnerType.selectedIndex].text == "--") {

       alert("A valid partner type is required on search.");

       return false;

     }

//alert ('partnertype text is '+partnerType.options[partnerType.selectedIndex].text);

//alert ('direct '+this.data.js.direct);

     switch(partnerType.options[partnerType.selectedIndex].text) {



       case this.data.js.direct :

         this.partnerTypeValue = "00000002";

         break;



       case "Enabling Partner" :

         this.partnerTypeValue = "ZENABPRN";

         break;



       case "Manufacturing Partner" :

         this.partnerTypeValue = "ZMVSMFG";

         break;



       case "Service Distributor" :

         this.partnerTypeValue = "ZSVCDIST";

         break;



       case "Service Reseller" :

         this.partnerTypeValue = "ZSVCRESL";

         break;



       case this.data.js.corporate :

         this.partnerTypeValue = "ZCORPACC";

         break;



       default:

         this.partnerTypeValue = "";



     }

 //alert ('partnertype is '+this.partnerTypeValue);

        var searchBy = document.getElementById("selProdSearchBy").value;



        mypostData['partner_type'] = this.partnerTypeValue;

        mypostData['sap_partner_id'] = partnerIDValue;

        mypostData['product_id'] = this._searchField.value;

        mypostData['ibase_search'] = searchBy;



        this._resetDisplay();



        this._waitPanel.show();

    

        this._overrideAjaxMethod();

        RightNow.Ajax.makeRequest("/cc/ibase_search/get_product", mypostData, {

            data: { eventName: "evt_getProductResponse" },

            successHandler: function (myresponse) {

                var resp = RightNow.JSON.parse(myresponse.responseText);

    

                this._waitPanel.hide();



                if(resp.status === 1 && resp[0]['ibase_list']) {

      

                  //2013.04.30 scott harris: code needed to select other tab for searching

                  var eot = new RightNow.Event.EventObject();

                  RightNow.Event.fire("evt_tab", eot)



                  var eodata = new RightNow.Event.EventObject();

                  eodata.data.result = resp;

                  RightNow.Event.fire("evt_MultipleOverride", eodata)

                   

                }

                else {

                  this._myAjaxResponse(resp);

                }

               

            },

            failureHandler: function (myresponse) {

              this._waitPanel.hide();

            },

            scope: this,

            timeout: 120000

        });



    },

    /**

    * Called when the search field is focused. Removes initial_value text

    */

    _onFocus: function() {

        if(this._searchField.value === this.data.attrs.label_hint)

            this._searchField.value = "";

    },

    /**

    * Called when the search field is blurred. Removes initial_value text

    */

    _onBlur: function() {

        if(this._searchField.value === "")

            this._searchField.value = this.data.attrs.label_hint;

    },





    _resetDisplay: function () {





      var eo = new RightNow.Event.EventObject();

      eo.data.hidelist = new Array('accordionComponents','accordionComponentDetails', 'accordionManageContacts','accordionIbaseUpdate', 'accordionRepairRequest');

      RightNow.Event.fire("evt_managePanel", eo);





      //clear sites

      if(this.siteDataTable != null) {

        this.siteDataTable.deleteRows(0, this.siteDataTable.getRecordSet()._records.length);

      }



      //clear products

      if(this.productDataTable != null) {

        this.productDataTable.deleteRows(0, this.productDataTable.getRecordSet()._records.length);

      }



      //clear subcomponents

      if(this.subcomponentsDataTable != null) {

        this.subcomponentsDataTable.deleteRows(0, this.subcomponentsDataTable.getRecordSet()._records.length);

      }





      //clear data from contracts table

      if(this.contractsDataTable != null) {

        this.contractsDataTable.deleteRows(0, this.contractsDataTable.getRecordSet()._records.length);

      }





      var tblEquipmentSite = document.getElementById("equipment_site_info");

      if(tblEquipmentSite.rows.length > 0) {

        //clear equipment site info

        while(tblEquipmentSite.rows.length > 0) {

          tblEquipmentSite.removeChild(tblEquipmentSite.firstChild);

        }

      }



      var tblPayer = document.getElementById("contract_payer_info");

      if(tblPayer.rows.length > 0) {

        //clear payer info

        while(tblPayer.rows.length > 0) {

          tblPayer.removeChild(tblPayer.firstChild);

        }

      }



    },





    _myAjaxResponse: function (searchArgs) {



     

      var tblSite = document.getElementById("site");

                              

       // First check for errors

        var result = searchArgs;

        if (!result) {

            alert("Unknown Ajax Error");

        } else if (result.status === 0) {

           alert("No Results Found.");

       

        } else if (result.status === 1) {

           

            var aSite = new Array();

            //aSite[aSite.length] = searchArgs['Site'];



            for(var z=0; z < searchArgs['Site'].length; z++) {



              aSite[z] = searchArgs['Site'][z];

            }





            var _showManageContacts = function () {



              var eo = new RightNow.Event.EventObject();

              eo.data.expandlist = new Array('accordionManageContacts');

              eo.data.showlist = new Array('accordionManageContacts');

              eo.data.hidelist = new Array('accordionIbaseUpdate', 'accordionRepairRequest');

              RightNow.Event.fire("evt_managePanel", eo);

              (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelManageContacts')[0]).focus();

            };

     

            var htmlManageLink = this._getManageLink();

 

            var myCustomFormatter = function(elCell, oRecord, oColumn, oData) {

               //elCell.innerHTML = "<a id=\"lnkManageContacts\" class=\"actionlink\">Manage Contacts</a>";

               elCell.innerHTML = htmlManageLink; 

            };



            

            // Add the custom formatter to the shortcuts 

            YAHOO.widget.DataTable.Formatter.myCustom = myCustomFormatter; 

    

            var siteDataSource = new YAHOO.util.DataSource(aSite);

            siteDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;



            siteDataSource.responseSchema = {

                resultsList : "", // String pointer to result data

                fields : [

                { key: "OrgName" }, 

                { key: "street" }, 

                { key: "city" },   

                { key: "zip" },

                { key: "province" },

                { key: "country" },

                { key: "custSAPId" },

                { key: "orgID" },

                { key: "manage" }

                ]

                };



            var siteColumnDefs = [

                {key:"custSAPId",label:this.data.js.sitecustid, sortable:false},

                {key:"OrgName",label:this.data.js.mysites_name, sortable:false},

                {key:"street",label:this.data.js.mysites_street, sortable:false},

                {key:"city",label:this.data.js.mysites_city, sortable:false},

                {key:"zip",label:this.data.js.mysites_postalcode, sortable:false},

                {key:"province",label:this.data.js.mysites_province, sortable:false},

                {key:"country",label:this.data.js.mysites_country, sortable:false},

                {key:"manage",label:this.data.js.mysites_action, minWidth:140, sortable:false, formatter:"myCustom"}

              ];







             this.siteDataTable = new YAHOO.widget.DataTable("div_sitetable", siteColumnDefs, siteDataSource);



/////

       this._siteSelectedHandler(searchArgs);  //first one is the mainframe, to display in product identifier panel, and sub-components



/*

       var aProd = new Array();

       aProd[0] = searchArgs[0]['products'][0];



       var productDataSource = new YAHOO.util.DataSource(aProd);



       productDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;



       productDataSource.responseSchema = {

                resultsList : "", // String pointer to result data

                fields : [

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

                { key: "addlAddress" },

                { key: "door" },

                { key: "remoteEOSL" },

                { key: "onsiteEOSL" },

                { key: "hasActiveContract" },

                { key: "partnerType" },

                { key: "requestingPartner" },

                { key: "enabling_partner"},

                { key: "mfg_partner"},

                { key: "distr_partner"},

                { key: "resell_partner"},

                { key: "direct_partner"}

                ]

                };



            var productColumnDefs = [

                {key:"ID",label:this.data.js.prodidentifier, sortable:false},

                {key:"SN",label:this.data.js.serialnumber, sortable:false},

                {key:"Name",label:this.data.js.pid_proddescription, sortable:false},

                {key:"plan",label:this.data.js.pic_entitlement, sortable:false},

                {key:"planStart",label:this.data.js.pic_contractstart, sortable:false},

                {key:"planEnd",label:this.data.js.pic_contractend, sortable:false},

                {key:"repair",label:this.data.js.pid_action, minWidth:140, sortable:false, formatter:"repairCustom"}

             ];



             this.productDataTable = new YAHOO.widget.DataTable("div_producttable", productColumnDefs, productDataSource);



             this.productDataTable.set("selectionMode", "single");

*/



/////







             var eo = new RightNow.Event.EventObject();

             eo.data.expandlist = new Array('accordionSites','accordionProducts','accordionComponents','accordionComponentDetails');

             RightNow.Event.fire("evt_managePanel", eo);

              

             //events

             this.siteDataTable.set("selectionMode", "single");

             //this.siteDataTable.subscribe("rowClickEvent", this._siteSelectedHandler, searchArgs, this);  //pass array of products

             this.siteDataTable.subscribe("rowMouseoverEvent", this.siteDataTable.onEventHighlightRow); 

             this.siteDataTable.subscribe("rowMouseoutEvent", this.siteDataTable.onEventUnhighlightRow); 

             //this.siteDataTable.subscribe("rowClickEvent", this.siteDataTable.onEventSelectRow);

             this.siteDataTable.subscribe("linkClickEvent", _showManageContacts);



             //show site panel

             var eo = new RightNow.Event.EventObject();

             eo.data.showlist = new Array('accordionSites');

             RightNow.Event.fire("evt_managePanel", eo);



                                      

        } else {

            // handle the failure, see if there is a result.message for an error

        }



     },



     _siteSelectedHandler : function (arr) {



       var mfProduct = new Array();

       mfProduct[0] = arr[0]['products'][0];



       var reo = new RightNow.Event.EventObject();

       reo.data.selectedOrg = arr['Site'][0]['orgID'];

       RightNow.Event.fire("evt_selectedOrgToManage", reo);



       var eoSite = new RightNow.Event.EventObject();

       eoSite.w_id = this.instanceID;

       eoSite.data.orgID = arr['Site'][0]['orgID']; 

       RightNow.Event.fire('evt_changeSite', eoSite);



       var actionRRLink = this._getActionLinks("RepairRequest");

       var actionIULink = this._getActionLinks("IbaseUpdate");

	   var knowledgesearchtxt = this.data.js.knowledgesearch;



       var repairCustomFormatter = function (elCell, oRecord, oColumn, oData) {



		    if (oRecord._oData.productHier != "") 

   		        var htmlProdLnk = "";

			    htmlProdLnk = "<a class=\"actionlink\" id=\"lnkPROD\">"+knowledgesearchtxt+"</a> <br/>";

//			    htmlProdLnk = "<a target=\"_blank\" class=\"actionlink\" id=\"lnkPROD\" href=\"/app/answers/list/p/"+oRecord._oData.productHier+"\">Knowledge Search</a> <br/>";

           if (oRecord._oData.hasActiveContract == "Y" && oRecord._oData.plan.toLowerCase().indexOf("parts only") == -1)

               elCell.innerHTML = htmlProdLnk + actionRRLink + "</br>" + actionIULink;

           else

               elCell.innerHTML = htmlProdLnk + actionIULink;



       };



       // Add the custom formatter to the shortcuts

       YAHOO.widget.DataTable.Formatter.repairCustom = repairCustomFormatter;

     



       var productDataSource = new YAHOO.util.DataSource(mfProduct);



       productDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;



       productDataSource.responseSchema = {

                resultsList : "", // String pointer to result data

                fields : [

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

                { key: "addlAddress" },

                { key: "door" },

                { key: "remoteEOSL" },

                { key: "onsiteEOSL" },

                { key: "hasActiveContract" },

                { key: "partnerType" },

                { key: "requestingPartner" },

                { key: "enabling_partner"},

                { key: "mfg_partner"},

                { key: "distr_partner"},

                { key: "resell_partner"},

                { key: "direct_partner"}

                ]

                };



            var productColumnDefs = [

                {key:"ID",label:this.data.js.prodidentifier, sortable:false},

                {key:"SN",label:this.data.js.serialnumber, sortable:false},

                {key:"Name",label:this.data.js.pic_proddescription, sortable:false},

                {key:"plan",label:this.data.js.pic_entitlement, sortable:false},

                {key:"planStart",label:this.data.js.pic_contractstart, sortable:false},

                {key:"planEnd",label:this.data.js.pic_contractend, sortable:false},

                {key:"repair",label:this.data.js.pid_action, minWidth:140, sortable:false, formatter:"repairCustom"}

             ];



             this.productDataTable = new YAHOO.widget.DataTable("div_producttable", productColumnDefs, productDataSource);



             this.productDataTable.set("selectionMode", "single");



       //var repairCustomFormatter = function(elCell, oRecord, oColumn, oData) {

       //  elCell.innerHTML = "<a id=\"lnkRepairRequest\" class=\"actionlink\">Repair Request</a><br/><a id=\"lnkIbaseUpdate\" class=\"actionlink\">Ibase Update</a>";

       //};

        



       //searchArgs[0]['products'][0]['hasActiveContract']





             this.productDataTable.subscribe("linkClickEvent", function(oArgs) {



               var oRec = this.getRecord(oArgs.target);

               //alert(YAHOO.lang.dump(oRec.getData()) );

 			   //alert ('in _myAjaxProductSearchResponse prod is '+oRec.getData().productHier);

               //Knowledge link  action

               if (oArgs.target.id == "lnkPROD") {

			      var detailsURL = '/app/answers/list/p/'+oRec.getData().productHier;

			      window.open(detailsURL, "_blank");

			   }



               //Ibase Update action

               if(oArgs.target.id == "lnkIbaseUpdate") {



                 //send data to form to pre-fill

                 var eor = new RightNow.Event.EventObject();

                 eor.data.knum = oRec.getData().ID;

                 eor.data.sn = oRec.getData().SN;

                 eor.data.equip_id = oRec.getData().compID;

                 eor.data.sap_prod_id = oRec.getData().sapProdID;

                 eor.data.sold_to = oRec.getData().SAPID;



                 eor.data.ibase_product_hier = oRec.getData().productHier;

                 RightNow.Event.fire("evt_populateIbaseUpdateData", eor);



                 var eo = new RightNow.Event.EventObject();

                 eo.data.expandlist = new Array('accordionIbaseUpdate');

                 eo.data.showlist = new Array('accordionIbaseUpdate');

                 eo.data.hidelist = new Array('accordionRepairRequest','accordionManageContacts');

                 RightNow.Event.fire("evt_managePanel", eo);

                 (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelIbaseUpdate')[0]).focus();

               }

 

               //Repair Request action

               if(oArgs.target.id == "lnkRepairRequest") {



                 var eo = new RightNow.Event.EventObject();

                 eo.data.showlist = new Array('accordionRepairRequest');

                 eo.data.hidelist = new Array('accordionIbaseUpdate','accordionManageContacts');

                 RightNow.Event.fire("evt_managePanel", eo);

                 (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelRepairRequest')[0]).focus();



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

                 eor.data.remoteEOSL = oRec.getData().remoteEOSL;

                 eor.data.onsiteEOSL = oRec.getData().onsiteEOSL;



                 eor.data.enablingPartner = oRec.getData().enabling_partner;

                 eor.data.mfgPartner = oRec.getData().mfg_partner;

                 eor.data.distrPartner = oRec.getData().distr_partner;

                 eor.data.resellPartner = oRec.getData().resell_partner;

                 eor.data.directPartner = oRec.getData().direct_partner;



                 RightNow.Event.fire("evt_populateRepairData", eor);

               }  //end if lnkRepairRequest





             });



              ///////////////////////////////////////////////////////////





              //var myProductHandler = function(oArgs) {

              

               var aProduct = new Array();

               var cnt = 0;



               for(var i=0; i < arr[0]['products'].length; i++) {



                 if(arr[0]['products'][i]['mf'] == 'N') {

                   aProduct[cnt++] = arr[0]['products'][i];

                 }



               }



			   var ibaseu = this.data.js.ibaseupdate;

                // ID of DOM element should be unique -- mickey.zhang
               var lnkIbaseUpdateIndex = 0; 
               var ibaseCustomFormatter = function(elCell, oRecord, oColumn, oData) {
                    lnkIbaseUpdateIndex++;
                   elCell.innerHTML = "<a class=\"actionlink\" id=\"lnkIbaseUpdateComponent_" + lnkIbaseUpdateIndex + "\">"+ibaseu+"</a>";
               };




               // Add the custom formatter to the shortcuts

               YAHOO.widget.DataTable.Formatter.ibaseCustom = ibaseCustomFormatter;



 

                var subDataSource = new YAHOO.util.DataSource(aProduct);

                subDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;

  

                subDataSource.responseSchema = {

                  resultsList : "", // String pointer to result data

                  fields : [

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

                  {key:"SN",label:this.data.js.serialnumber, sortable:false},

                  {key:"Name",label:this.data.js.pic_proddescription, sortable:false},

                  {key:"plan",label:this.data.js.pic_entitlement, sortable:false},

                  {key:"planStart",label:this.data.js.pic_contractstart, sortable:false},

                  {key:"planEnd",label:this.data.js.pic_contractend, sortable:false},

                  {key:"",label:this.data.js.pid_action, minWidth:140, sortable:false, formatter:"ibaseCustom"}

                ];



                this.subcomponentsDataTable = new YAHOO.widget.DataTable("div_subtable", subColumnDefs, subDataSource);



                //show meters

                //

                //meter data

                //

                //meterDataSource = new YAHOO.util.DataSource(this.data.js.meter);



                var eoc = new RightNow.Event.EventObject();

                eoc.data.meterData = mfProduct[0]['meters'];

                eoc.data.compID = mfProduct[0].ID;

                RightNow.Event.fire("evt_displayCurrentMeter", eoc);



                //

                //experimental - meter history

                //

                var eoh = new RightNow.Event.EventObject();

                eoh.data.meterData = mfProduct[0]['meter_history'];

                RightNow.Event.fire("evt_displayMeterHistory", eoh);



                var mylink = new YAHOO.util.Element('lnkIbaseUpdateComponent');



                //var ibaseUpdateByComponent = function(e) {

                var ibaseUpdateByComponent = function(evt, eo) {

                  //alert("here" + mfProduct[0].ID);  //use mf product from here

                  //send data to form to pre-fill



                  var rows = this.subcomponentsDataTable.getRecordSet();

                  var record = this.subcomponentsDataTable.getRecord(evt.target);



                  //alert(YAHOO.lang.dump(record.getData()));



                  var eor = new RightNow.Event.EventObject();

                  eor.data.knum = mfProduct[0].ID;

                  eor.data.sn = mfProduct[0].SN;

                  eor.data.equip_id = record.getData().compID;  // mfProduct[0].compID;

                  eor.data.sap_prod_id = mfProduct[0].sapProdID;

                  eor.data.sold_to = mfProduct[0].SAPID;



                  eor.data.enablingPartner = mfProduct[0].enabling_partner;

                  eor.data.mfgPartner = mfProduct[0].mfg_partner;

                  eor.data.distrPartner = mfProduct[0].distr_partner;

                  eor.data.resellPartner = mfProduct[0].resell_partner;

                  eor.data.directPartner = mfProduct[0].direct_partner;



                  eor.data.ibase_product_hier = mfProduct[0].productHier;

                  RightNow.Event.fire("evt_populateIbaseUpdateData", eor);



                  var eo = new RightNow.Event.EventObject();

                  eo.data.expandlist = new Array('accordionIbaseUpdate');

                  eo.data.showlist = new Array('accordionIbaseUpdate');

                  eo.data.hidelist = new Array('accordionRepairRequest','accordionManageContacts');

                  RightNow.Event.fire("evt_managePanel", eo);

                  (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelIbaseUpdate')[0]).focus();



                };


                // Binding event for each unique ID -- mickey.zhang
                for (var i=1; i<=lnkIbaseUpdateIndex; i++) {
                    var mylink = new YAHOO.util.Element('lnkIbaseUpdateComponent_' + i);
                    mylink.on('click', ibaseUpdateByComponent, this.subcomponentsDataTable, this);
                }



                //show component details panel 

                var eo = new RightNow.Event.EventObject();

                eo.data.showlist = new Array('accordionComponents');

                eo.data.expandlist = new Array('accordionComponents');

                RightNow.Event.fire("evt_managePanel", eo);



/* for the mainframe we also need to show its contracts and site info */                       

  

                 //clear data from contracts table

                  if(this.contractsDataTable != null) {

                    this.contractsDataTable.destroy();

                  }



                  var aContracts = mfProduct[0]['contracts']; 



                  contractDataSource = new YAHOO.util.DataSource(aContracts);



                  contractDataSource.responseType = YAHOO.util.XHRDataSource.TYPE_JSARRAY;



                  contractDataSource.responseSchema = {

                    resultsList : "", // String pointer to result data

                    fields : [

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

                  {key:"description",label:this.data.js.pide_product, sortable:false},

                  {key:"serviceProfileDesc",label:this.data.js.pide_coveragetime, sortable:false},

                  {key:"responseProfileDesc",label:this.data.js.pide_prioritizedresponse, sortable:false},

                  {key:"startDate",label:this.data.js.pide_contractstart, sortable:false},

                  {key:"endDate",label:this.data.js.pide_contractend, sortable:false},

                  {key:"type",label:this.data.js.pide_contracttype, sortable:false},

                  {key:"status",label:this.data.js.pide_contractstatus, sortable:false},

                  {key:"contractID",label:this.data.js.pide_contractid, sortable:false}

                  ];



                  this.contractsDataTable = new YAHOO.widget.DataTable("div_contracts", contractColumnDefs, contractDataSource);



                  var myTabView = new YAHOO.widget.TabView("tvcontainer");



                  var tblPayer = document.getElementById("contract_payer_info");

                  var tblEquipmentSite = document.getElementById("equipment_site_info");



                  //clear equipment site info

                  while(tblEquipmentSite.rows.length > 0) {

                    tblEquipmentSite.deleteRow(0);

                  }



                  //clear payer info

                  while(tblPayer.rows.length > 0) {

                    tblPayer.deleteRow(0);

                  }





                  //Payer

                  var pRow = tblPayer.insertRow(-1);

                  var aCell = pRow.insertCell(-1);

                  var bCell = pRow.insertCell(-1);

                  aCell.innerHTML = this.data.js.pidscpi_kodakcustnum;

                  bCell.innerHTML = arr['Payer'].SAPID;





                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = this.data.js.pidscpi_customername;

                  bCell.innerHTML = arr['Payer'].OrgName;



                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = this.data.js.pidscpi_address;

                  bCell.innerHTML = arr['Payer'].street;



                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = "";

                  bCell.innerHTML = arr['Payer'].city;



                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = "";

                  bCell.innerHTML = arr['Payer'].province;



                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = "";

                  bCell.innerHTML = arr['Payer'].zip;



                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = "";

                  bCell.innerHTML = arr['Payer'].country;



                  //equipment site

                  if(mfProduct[0] != null) {

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





           

                ////////////////////////////////////



                var myComponentHandler = function(evt, eo) {





                  var rows = this.subcomponentsDataTable.getRecordSet();

                  var record = this.subcomponentsDataTable.getRecord(evt.target);



                  //clear data from contracts table

                  if(this.contractsDataTable != null) {

                    this.contractsDataTable.destroy();

                  }

                  

                  var aContracts = new Array();

                  var aMeters = new Array();

                  var hMeters = new Array();



                  //record._oData.compID);

                  //alert(YAHOO.lang.dump(record.getData()));



                  for(var i=0; i < arr[0]['products'].length; i++) {



                    if(arr[0]['products'][i]['compID'] == record._oData.compID) {

                      aContracts = arr[0]['products'][i]['contracts']; 

                      aMeters = arr[0]['products'][i]['meters'];

                      hMeters = arr[0]['products'][i]['meter_history'];

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

                    resultsList : "", // String pointer to result data

                    fields : [

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

                  {key:"description",label:this.data.js.pide_product, sortable:false},

                  {key:"serviceProfileDesc",label:this.data.js.pide_coveragetime, sortable:false},

                  {key:"responseProfileDesc",label:this.data.js.pide_prioritizedresponse, sortable:false},

                  {key:"startDate",label:this.data.js.pide_contractstart, sortable:false},

                  {key:"endDate",label:this.data.js.pide_contractend, sortable:false},

                  {key:"type",label:this.data.js.pide_contracttype, sortable:false},      

                  {key:"status",label:this.data.js.pide_contractstatus, sortable:false},

                  {key:"contractID",label:this.data.js.pide_contractid, sortable:false}

                  ];

     

                  this.contractsDataTable = new YAHOO.widget.DataTable("div_contracts", contractColumnDefs, contractDataSource);



                  var myTabView = new YAHOO.widget.TabView("tvcontainer");



                  var tblPayer = document.getElementById("contract_payer_info");

                  var tblEquipmentSite = document.getElementById("equipment_site_info");



                  //clear equipment site info

                  while(tblEquipmentSite.rows.length > 0) {

                    tblEquipmentSite.deleteRow(0);

                  }



                  //clear payer info

                  while(tblPayer.rows.length > 0) {

                    tblPayer.deleteRow(0);

                  }



                  //Payer

                  var pRow = tblPayer.insertRow(-1);

                  var aCell = pRow.insertCell(-1);

                  var bCell = pRow.insertCell(-1);

                  aCell.innerHTML = this.data.js.pidscpi_kodakcustnum;

                  bCell.innerHTML = arr['Payer'].SAPID;





                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = this.data.js.pidscpi_customername;

                  bCell.innerHTML = arr['Payer'].OrgName;



                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = this.data.js.pidscpi_address;

                  bCell.innerHTML = arr['Payer'].street;



                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = "";

                  bCell.innerHTML = arr['Payer'].city;



                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = "";

                  bCell.innerHTML = arr['Payer'].province;



                  pRow = tblPayer.insertRow(-1); 

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = "";

                  bCell.innerHTML = arr['Payer'].zip;



                  pRow = tblPayer.insertRow(-1);

                  aCell = pRow.insertCell(-1);

                  bCell = pRow.insertCell(-1);

                  aCell.innerHTML = "";

                  bCell.innerHTML = arr['Payer'].country;



                  //equipment site

                  if(arr[0]['products'] != null) {

                    var eRow = tblEquipmentSite.insertRow(-1);

                    var cCell = eRow.insertCell(-1);

                    var dCell = eRow.insertCell(-1);

                    cCell.innerHTML = this.data.js.pidsi_floorbldg;

                    dCell.innerHTML = record._oData.floorBldg;



                 }



                }; 

 

                ////////////////////////////////////





                this.subcomponentsDataTable.set("selectionMode", "single");

                this.subcomponentsDataTable.subscribe("rowMouseoverEvent", this.subcomponentsDataTable.onEventHighlightRow); 

                this.subcomponentsDataTable.subscribe("rowMouseoutEvent", this.subcomponentsDataTable.onEventUnhighlightRow);

                this.subcomponentsDataTable.subscribe("rowClickEvent", myComponentHandler, arguments[1], this);  //pass array



                //show component details panel 

                var eo = new RightNow.Event.EventObject();

                eo.data.showlist = new Array('accordionComponentDetails');

                RightNow.Event.fire("evt_managePanel", eo);





            //   };



              ///////////////////////////////////////////////////////////



             //this.productDataTable.subscribe("rowClickEvent", myProductHandler);   //, arguments[1]);  //pass array



             this.productDataTable.subscribe("rowMouseoverEvent", this.productDataTable.onEventHighlightRow); 

             this.productDataTable.subscribe("rowMouseoutEvent", this.productDataTable.onEventUnhighlightRow); 





    },



    _doIbaseUpdate : function (evt, eo) {



      //first record is the mainframe

      var record = this.productDataTable.getRecord(0);

      //alert(YAHOO.lang.dump(record.getData()));



      //Ibase Update action



        //send data to form to pre-fill

        var eor = new RightNow.Event.EventObject();

        eor.data.knum = record.getData().ID;

        eor.data.sn = record.getData().SN;

        eor.data.equip_id = record.getData().compID;

        eor.data.sap_prod_id = record.getData().sapProdID;

        eor.data.sold_to = record.getData().SAPID;



        eor.data.enablingPartner = record.getData().enabling_partner;

        eor.data.mfgPartner = record.getData().mfg_partner;

        eor.data.distrPartner = record.getData().distr_partner;

        eor.data.resellPartner = record.getData().resell_partner;

        eor.data.directPartner = record.getData().direct_partner;



        eor.data.ibase_product_hier = record.getData().productHier;

        RightNow.Event.fire("evt_populateIbaseUpdateData", eor);



        var eo = new RightNow.Event.EventObject();

        eo.data.expandlist = new Array('accordionIbaseUpdate');

        eo.data.showlist = new Array('accordionIbaseUpdate');

        eo.data.hidelist = new Array('accordionRepairRequest','accordionManageContacts');

        RightNow.Event.fire("evt_managePanel", eo);

        (YAHOO.util.Dom.getElementsByClassName('mysel', 'select', 'panelIbaseUpdate')[0]).focus();

    },



    _overrideAjaxMethod: function () {

        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {

           if(eo[0].data.eventName == "evt_getProductResponse") {

             eo[0].url = '/cc/ibase_search/get_product';

           }

        }, this);

    },



    _overrideAjaxMethodCustomer: function () {

        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {

           if(eo[0].data.eventName == "evt_getIbaseResponse") {

             eo[0].url = '/cc/ibase_search/get_ibase_list';

           }

        }, this);

    },



  _getActionLinks : function(type) {



    var htmlRepair = "";

    var htmlUpdate = "";

    var br = "";

    var htmlLinks = "";





    if(this.allowRepairRequest && type == "RepairRequest")

      htmlRepair = "<a id=\"lnkRepairRequest\" class=\"actionlink\">"+this.data.js.repairrequest+"</a>";



    if(this.allowIbaseUpdate && type == "IbaseUpdate")

      htmlUpdate = "<a id=\"lnkIbaseUpdate\" class=\"actionlink\">"+this.data.js.ibaseupdate+"</a>&nbsp;&nbsp;";



    if(htmlRepair != "" && htmlUpdate != "")

      br = "<br/>";



    htmlLinks = htmlRepair+br+htmlUpdate;



    return htmlLinks;



  },



  _getManageLink : function() {



    var htmlManage = "";

    var partnerType = document.getElementById(this.partnerSelectInstance);

    var partnerIDValue = partnerType.options[partnerType.selectedIndex].label;



    if (partnerType != null) {



      var partnerIDValue = partnerType.options[partnerType.selectedIndex].text;

      if (this.allowManageContact && partnerIDValue == this.data.js.direct) {

        htmlManage = "<a class=\"actionlink\" id=\"lnkManageContacts\">"+this.data.js.managecontacts+"</a>";

      }



    }



    if (this.allowManageContact && this.data.js.isDirect == "Y")

      htmlManage = "<a class=\"actionlink\" id=\"lnkManageContacts\">"+this.data.js.managecontacts+"</a>";



    return htmlManage;

  },

 

  _createWaitPanel: function () {

        this._waitPanel =

                    new YAHOO.widget.Panel("waitSimpleSearch",

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




