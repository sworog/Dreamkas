define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        Page = require('kit/page');

    return Page.extend({
        template: require('ejs!./content.ejs'),
        params: {
            debugLevel: config.debugLevel
        }
    });
});