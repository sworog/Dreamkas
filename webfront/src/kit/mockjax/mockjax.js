define(function(require, exports, module) {
    //requirements
    require('mockjax');

    $.mockjaxSettings.contentType = "application/json";

    return $.mockjax;
});