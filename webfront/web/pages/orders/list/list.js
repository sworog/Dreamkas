define(function(require, exports, module) {
    //requirements
    var Page = require('pages/store');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_orders.ejs')
        },
        collections: {
            orders: function(){
                var page = this,
                    OrdersCollection = require('collections/orders'),
                    ordersCollection = new OrdersCollection;

                ordersCollection.storeId = page.get('params.storeId');

                return ordersCollection;
            }
        }
    });
});