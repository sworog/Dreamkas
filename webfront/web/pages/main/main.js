define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        resources: {
            grossSales: require('models/grossSales')
        },
        partials: {
            content: require('rv!./content.html')
        }
    });
});