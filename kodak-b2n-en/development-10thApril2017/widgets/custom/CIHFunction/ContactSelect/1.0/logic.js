RightNow.namespace('Custom.Widgets.CIHFunction.ContactSelect');
Custom.Widgets.CIHFunction.ContactSelect = RightNow.Widgets.extend({ 
    /**
     * Widget constructor.
     */
    constructor: function() {
		
		    this._eo = new RightNow.Event.EventObject();

    var optionsName = "rn_" + this.instanceID + "_Options";

    this._optionsSelect = document.getElementById(optionsName);

    this._parentForm = RightNow.UI.findParentForm(optionsName);
	

    this._eo.data.form = this._parentForm;

    this._currentOrgID = this.data.js.org_id || 0;

    //VS

    this._setSelectedDropdownItem(this.data.js.defaultFilter);

	if(this._optionsSelect!=null){
		if (!this._optionsSelect.disabled) {

			this._disableEvents();

			this._enableEvents();

		}
	}
	
    //YAHOO.util.Event.addListener(this._optionsSelect, "change", this._onSelectChange, null, this);
	//if(this._optionsSelect!=null)
	    this.Y.one("#"+optionsName).on("change",this._onSelectChange,this);

    if (this.data.attrs.validate_on_blur)
        this.Y.all("#"+this._inputField.id).on("blur",this._blurValidate,this);

    RightNow.Event.subscribe('evt_managePanel', this._onManagePanel, this);

    RightNow.Event.subscribe('evt_formFieldValidateRequest', this.onValidate, this);

    RightNow.Event.subscribe('evt_changeSite', this._onChangeSite, this);

    this._selectLastContact();
                  
    },
	
	_onManagePanel: function (evt, args) {



        var hideList, showList;

        hideList = args[0].data.hidelist;

        showList = args[0].data.showlist;



        if (showList != undefined) {

            for (i = 0; i < showList.length; i++) {

                if (showList[i] == this.data.attrs.panel_name) {

                    this._disableEvents(evt, args);

                    this._enableEvents(evt, args);

                    this._selectLastContact();

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

    _enableEvents: function (evt, args) {



        //RightNow.Event.subscribe('evt_formFieldValidateRequest', this.onValidate, this);

        RightNow.Event.subscribe('evt_resetForm', this._onResetForm, this);

        RightNow.Event.subscribe('evt_contactModified', this._getContacts, this);

        RightNow.Event.subscribe('evt_resetContactSelection', this._onResetContactSelection, this);



    },



    _disableEvents: function (evt, args) {



        //RightNow.Event.unsubscribe('evt_formFieldValidateRequest', this.onValidate);

        RightNow.Event.unsubscribe('evt_resetForm', this._onResetForm);

        RightNow.Event.unsubscribe('evt_contactModified', this._getContacts);

        RightNow.Event.unsubscribe('evt_resetContactSelection', this._onResetContactSelection);

        //RightNow.Event.unsubscribe('evt_changeSite', this._onChangeSite);

    },



    _onChangeSite: function (evt, args) {



            this._currentOrgID = args[0].data.orgID;

            this._getContacts(evt, args);

            this._selectLastContact();



    },



    _selectLastContact: function () {

        this._setSelectedDropdownItem(this.data.js.last_contact_id);

        this._onSelectChange(null, this);

    },



    /**

    * Event handler executed when the select box is changed

    *

    * @param evt object Event

    */

    _onSelectChange: function (evt, args) {
		
        if(this._optionsSelect!=null){
			
			var selectedValue = this._optionsSelect.options[this._optionsSelect.selectedIndex].value;
			
			this._setSelected();
			
			RightNow.Event.fire("evt_contactSelectChanged", this._eo);
			
			if(evt){
				
				var inside_form=evt._currentTarget.form.id;	
					
				if(inside_form.indexOf("ManageContacts")!= -1)
				  RightNow.Event.fire("evt_manageContactSelectChanged", this._eo); //For Manage Contact Form
				if(inside_form.indexOf("IBaseUpdate")!= -1)
				  RightNow.Event.fire("evt_ibaseContactSelectChanged", this._eo);  //For Ibase Update Form
				if(inside_form.indexOf("RepairRequest")!= -1)
				  RightNow.Event.fire("evt_repairRequestContactSelectChanged", this._eo); //For Repair Request Form.
			}
			
            this._eo.data.trigger = this._optionsSelect.id;
			RightNow.Event.fire("evt_resetForm", this._eo);
		}
		
		
    },



    /**

    * We need to rebuild the select box when a new contact is added

    *

    **/

    _getContacts: function (evt, args) {
		

        var deactivated = 'true';

        if (this.data.attrs.include_deactivated == false)

            deactivated = 'false';

        var postData = { 'deactivated': deactivated, 'org_id': args[0].data.orgID };

        this._overrideAjaxMethod();

        RightNow.Ajax.makeRequest("/cc/contact_custom/get_contacts", postData, {

            data: { eventName: "evt_getContactsResponse" },

            successHandler: function (response) {
				
				var responseText=response.responseText.replace('<rn:meta title="" template="kodak_b2b_template.php" />',"");  

                var resp = RightNow.JSON.parse(responseText);

                this._ajaxResponse(resp);

            },

            failuerHandler: function (response) {

            },

            scope: this

        });



    },



    _ajaxResponse: function (args) {

        // First check for errors

        var result = args;

        if (!result) {

            alert("Unknown Ajax Error");

        } else if (result.status === 1) {



            /*

            for (var i = 0; i < this._optionsSelect.length; i++) {

            this._optionsSelect.removeChild(this._optionsSelect.childNodes[i]);

            }

            */

            this._optionsSelect.options.length = 0;

            //add the default option

            var option = document.createElement("option");

            option.text = this.data.js.no_selection_label;

            option.value = "0";

            this._optionsSelect.appendChild(option);

            this._optionsSelect.options[0].text = this.data.js.no_selection_label;

            var optionCount = 1;

            for (var contact in args) {

                if (args[contact].c_id != undefined) {

                    this.data.js.last_contact_id = args[contact].last_contact_id;

                    var option = document.createElement("option");

                    option.value = args[contact].c_id;

                    this._optionsSelect.appendChild(option);

                    this._optionsSelect.options[optionCount].text = args[contact].last_name + ', ' + args[contact].first_name + ' ' + args[contact].phone;

                    optionCount+=1;

                }

                

            }
			
			document.querySelectorAll('#panelManageContacts2 .mysel')[0].value=this.data.js.last_contact_id; 



        } else {

            // handle the failure, see if there is a result.message for an error

        }



    },



    _overrideAjaxMethod: function () {



        RightNow.Event.unsubscribe('on_before_ajax_request', function (evt, eo) {

            if (eo[0].data.eventName == "evt_getContactsResponse") {

                eo[0].url = '/cc/contact_custom/get_contacts';

            }

        });



        RightNow.Event.subscribe('on_before_ajax_request', function (evt, eo) {
			
			if(eo[0].hasOwnProperty('data')){

					if (eo[0].data.eventName == "evt_getContactsResponse") {

						eo[0].url = '/cc/contact_custom/get_contacts';

					}
			}

        }, this);

    },



    /**

    * internal function to set the event object from the column select box value

    */

    _setSelected: function () {

        if (this._optionsSelect) {

            var i = this._optionsSelect.selectedIndex;

            i = Math.max(0, i);

            if (this._optionsSelect.options[i]) {

                this._eo.filters.data = this._optionsSelect.options[i].value;

                this.data.js.value = this._eo.filters.data;

            }

        }

    },



    _onResetForm: function (evt, args) {

        if (this._parentForm == args[0].data.form && this._optionsSelect.disabled != true) {

            if (this._optionsSelect.id !== args[0].data.trigger) {

                this._getContacts();

                this._optionsSelect.selectedIndex = -1;

            }

        }

    },



    _onResetContactSelection: function (evt, args) {



        if (this._optionsSelect.id !== args[0].data.trigger)

            this._optionsSelect.selectedIndex = 0;

    },



    /**

    * Sets the selected dropdown item to the one matching the passed-in value.

    * @param valueToSelect Int Value of item to select

    * @return Boolean Whether or not the operation was successful

    */

    _setSelectedDropdownItem: function (valueToSelect) {

        if (this._optionsSelect) {

            for (var i = 0; i < this._optionsSelect.length; i++) {

                if (this._optionsSelect.options[i].value == valueToSelect) {

                    this._optionsSelect.selectedIndex = i;

                    return true;

                }

            }

        }

        return false;

    },



    /**

    * Validates that the input field has a value (if required) and that the value is

    * of the correct format.

    */

    _blurValidate: function () {

        this._formErrorLocation = null;

        if (this._onAccountExistsResponse._dialogShowing) return;



        this._trimField();

        if (this._checkRequired() && this._checkData() && this._checkValue() && this._checkEmail()) {

            if (this._fieldName === "login" || this._fieldName === "email" || this._fieldName === "email_alt1" || this._fieldName === "email_alt2") {

                this._checkExistingAccount();

            }

            YAHOO.util.Dom.removeClass(this._inputField, "rn_ErrorField");

            YAHOO.util.Dom.removeClass("rn_" + this.instanceID + "_Label", "rn_ErrorLabel");

            return true;

        }

    },



    /**

    * Event handler for when form is being submitted

    *

    * @param {String} type Event name

    * @param {Object} args Event arguments

    */

    onValidate: function (type, args) {

        var eo = new RightNow.Event.EventObject();

        eo.data.name = this.data.attrs.name;

        eo.data.value = this.data.js.value;

        eo.data.form = RightNow.UI.findParentForm('rn_' + this.instanceID + '_Options');

        RightNow.Event.fire('evt_formFieldValidateResponse', eo);



        RightNow.Event.fire('evt_formFieldCountRequest');

    }

});