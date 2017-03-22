RightNow.Widget.PartnerTypeList = function(data, instanceID)
{
    this.data = data;
    this.instanceID = instanceID;
    this._eo = new RightNow.Event.EventObject();
    var optionsName = "rn_" + this.instanceID + "_Options";
    this._parentForm = RightNow.UI.findParentForm('rn_' + this.instanceID + '_Options');

    this._eo.data.form = RightNow.UI.findParentForm("rn_" + this.instanceID);
    this._optionsSelect = document.getElementById(optionsName);

    YAHOO.util.Event.addListener(this._optionsSelect, "change", this._onSelectChange, null, this);
    RightNow.Event.subscribe('evt_formFieldValidateRequest', this.onValidate, this);
        
    this._setSelectedDropdownItem(this.data.js.defaultFilter);

    RightNow.Event.subscribe('evt_resetForm', this._onResetForm, this);

    this._setSelectedDropdownItem(this.data.js.defaultFilter);

    //added by Scott
    var eo = new RightNow.Event.EventObject();
    eo.data.partnerTypeSelect = optionsName;
    eo.data.name = this.data.attrs.name;
    RightNow.Event.fire('evt_registerPartnerType', eo);
   
};

RightNow.Widget.PartnerTypeList.prototype = {

    /**
    * Event handler executed when the select box is changed
    *
    * @param evt object Event
    */
    _onSelectChange: function (evt) {
        this._setSelected();
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

    /**
    * Sets the selected dropdown item to the one matching the passed-in value.
    *   If only one type, then select as default
    * @param valueToSelect Int Value of item to select
    * @return Boolean Whether or not the operation was successful
    */
    _setSelectedDropdownItem: function (valueToSelect) {
        if (this._optionsSelect) {

            if (valueToSelect == undefined) {
                if(this._optionsSelect.length == 2) {
                  this._optionsSelect.selectedIndex = 1;
                }
                else 
                  this._optionsSelect.selectedIndex = 0;
            }
            else {
                for (var i = 0; i < this._optionsSelect.length; i++) {
                    //if (this._optionsSelect.options[i].value == valueToSelect) {
                    if (this._optionsSelect.options[i].text == valueToSelect) {
                        this._optionsSelect.selectedIndex = i;
                        this._optionsSelect.options[i].selected = true;
                        return true;
                    }
                }
            }
        }
        return false;
    },

    onValidate: function (type, args) {
        var eo = new RightNow.Event.EventObject();
        eo.data.name = this.data.attrs.name;
        eo.data.value = this.data.js.value;
        eo.data.form = RightNow.UI.findParentForm('rn_' + this.instanceID + '_Options');
        RightNow.Event.fire('evt_formFieldValidateResponse', eo);

        RightNow.Event.fire('evt_formFieldCountRequest');
    },

    _onResetForm: function (evt,args) {
        if (args[0].data.form === this._parentForm) {
            this._optionsSelect.selectedIndex = 0;
        }
    }
};
