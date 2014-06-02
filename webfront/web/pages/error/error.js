define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        Page = require('kit/page');

    return Page.extend({
        name: 'error',
        template: require('rv!./template.html'),
        data: {
            jsErrors: [],
            apiErrors: [],
            debugLevel: config.debugLevel,
            getResponseJson: function(error){
                return JSON.stringify(JSON.parse(error.responseText), null, 2);
            }
        }
    });
});