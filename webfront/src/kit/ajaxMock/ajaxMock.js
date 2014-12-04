define(function(require, exports, module) {
    //requirements
    require('mockjax');

    $.mockjaxSettings.contentType = "application/json";

    var mock = function(opt){
        opt = opt || {};

        opt.url = CONFIG.baseApiUrl + opt.url;

        return $.mockjax(opt);
    };

    mock.clear = $.mockjax.clear.bind($.mockjax);

    return mock;
});