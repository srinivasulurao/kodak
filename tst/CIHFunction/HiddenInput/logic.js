RightNow.Widget.HiddenInput = function(dat, instanceID)
{
    this.data       = dat;
    this.instanceID = instanceID;

    RightNow.Event.subscribe('evt_formFieldValidateRequest', this.onValidate, this);
    RightNow.Event.subscribe('evt_setHiddenField', this.onSetHiddenField, this);
}

RightNow.Widget.HiddenInput.prototype =
{
    /*
    * Event handler for setting the value.
    *
    * @param event evt  Event
    * @param array args Args
    */
    onSetHiddenField: function(evt, args)
    {
        var eo = args[0];

        if (eo.data.name != this.data.attrs.name)
            return;

        this.data.js.value = eo.data.value;
        //alert(eo.data.name + " = " + eo.data.value);
    },

    /**
     * Event handler for when form is being submitted
     *
     * @param {String} type Event name
     * @param {Object} args Event arguments
     */
    onValidate: function(type, args)
    {
        var eo        = new RightNow.Event.EventObject();
        eo.data.name  = this.data.attrs.name;
        eo.data.value = this.data.js.value;
        eo.data.form       = RightNow.UI.findParentForm('rn_' + this.instanceID + '_HiddenInput');
        RightNow.Event.fire('evt_formFieldValidateResponse', eo);

        RightNow.Event.fire('evt_formFieldCountRequest');
    }
}
