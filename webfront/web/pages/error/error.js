define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        Page = require('kit/page');

    return Page.extend({
        template: require('rv!./template.html'),
        data: {
            jsErrors: [],
            apiErrors: [],
            debugLevel: config.debugLevel
        }
    });
});