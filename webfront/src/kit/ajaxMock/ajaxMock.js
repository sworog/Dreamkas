define(function(require, exports, module) {
    //requirements
    var config = require('config');

    require('mockjax');

    $.mockjaxSettings.contentType = "application/json";

    var mock = function(opt){
        opt = opt || {};

        opt.url = config.baseApiUrl + opt.url;

        return $.mockjax(opt);
    };

    mock.clear = $.mockjax.clear.bind($.mockjax);

    return mock;
});