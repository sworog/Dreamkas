define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        OrdersCollection = require('collections/orders');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function() {
            return !LH.isAllow('orders', 'GET');
        },
        initialize: function() {
            var page = this;

            page.collections = {
                orders: new OrdersCollection()
            };

            page.render();

//            $.when(page.collections.orders.fetch()).done(function() {
//                page.render();
//            });
        }
    });
});