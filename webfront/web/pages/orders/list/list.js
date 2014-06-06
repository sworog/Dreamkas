define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            localNavigation: require('rv!blocks/localNavigation/localNavigation_orders.html'),
            globalNavigation: require('rv!blocks/globalNavigation/globalNavigation_store.html')
        },
        resources: {
            orders: function(){
                var page = this,
                    OrdersCollection = require('collections/orders'),
                    ordersCollection = new OrdersCollection;

                ordersCollection.storeId = page.get('params.storeId');

                return ordersCollection;
            },
            store: function() {
                var page = this,
                    StoreModel = require('models/store');

                return new StoreModel({
                    id: page.get('params.storeId')
                });
            }
        }
    });
});