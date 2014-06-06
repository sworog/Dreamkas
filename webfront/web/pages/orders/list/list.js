define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    require('jquery');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            localNavigation: require('rv!../localNavigation.html')
        },
        localNavigationActiveLink: 'list',
        resources: {
            orders: require('collections/orders')
        }
    });
});