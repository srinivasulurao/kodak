RightNow.Widget.ListFailedOrgContacts = function (data, instanceID)
{
    this.data = data;
    this.instanceID = instanceID;
    this._eo = new RightNow.Event.EventObject();
    var optionsName = "rn_" + this.instanceID + "_Options";
    this._optionsSelect = document.getElementById(optionsName);
    this._parentForm = RightNow.UI.findParentForm('rn_' + this.instanceID + '_Options');

    YAHOO.util.Event.addListener(this._optionsSelect, "change", this._onSelectChange, null, this);
    RightNow.Event.subscribe('evt_resetForm', this._onResetForm, this);

    this._setSelectedDropdownItem(this.data.js.defaultFilter);
   
};

RightNow.Widget.ListFailedOrgContacts.prototype = {

    /**
    * Event handler executed when the select box is changed
    *
    * @param evt object Event
    */
    _onSelectChange: function (evt) {
        this._setSelected();
        this._eo.data.form = this._parentForm;
        this._eo.data.trigger = this._optionsName;
        RightNow.Event.fire("evt_contactSelectChanged", this._eo);

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
                this._eo.data.value = this._optionsSelect.options[i].value;
                this.data.js.value = this._eo.filters.data;
            }
        }
    },

    _onResetForm: function (evt, args) {
        if (args[0].data.form === this._parentForm) {
            this._optionsSelect.selectedIndex = 0;
        }
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
    }
};
