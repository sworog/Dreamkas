define(function(require, exports, module) {
    //requirements
    var Page = require('page'),
        OrdersCollection = require('collections/orders');

    require('jquery');

    return Page.extend({
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!../localNavigation.html')
        },
        localNavigationActiveLink: 'list',
        isAllow: function() {
            return LH.isAllow('stores/{store}/orders', 'GET');
        },
        initialize: function() {
            var page = this;

            page.collections = {
                orders: new OrdersCollection()
            };

            $.when(page.collections.orders.fetch()).done(function() {
                page.render();
            });
        }
    });
});