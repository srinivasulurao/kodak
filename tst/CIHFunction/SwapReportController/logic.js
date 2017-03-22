RightNow.Widget.SwapReportController = function (data, instanceID)
{
    //Data object contains all widget attributes, values, etc.
    this.data = data;
    this.instanceID = instanceID;
    RightNow.Event.subscribe("on_before_ajax_request", this._ajaxIntercept, this);

};
RightNow.Widget.SwapReportController.prototype = {
    _ajaxIntercept: function (evt, args) {
        if (args[0].url == '/ci/ajaxRequest/getReportData') {
            args[0].url = '/cc/ajaxRequest/getReportData';
        }
    }
};
