define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            localNavigation: require('rv!pages/reports/localNavigation_net.html')
        },
        resources: {
            grossSalesByStores: require('collections/grossSalesByStores')
        }
    });
});