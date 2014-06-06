define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        SuppliersCollection = require('collections/suppliers'),
        OrderModel = require('models/order'),
        OrderProductsCollection = require('collections/orderProducts'),
        Form_order = require('blocks/form/form_order/form_order');

    require('jquery');

    return Page.extend({
        templates: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_orders.ejs'),
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation_store.ejs')
        },
        localNavigationActiveLink: 'create',
        collections: {
            suppliers: function(){
                return new SuppliersCollection()
            }
        },
        models: {
            order: function(){
                var page = this,
                    orderProductCollection = new OrderProductsCollection();

                orderProductCollection.storeId = page.get('params.storeId');

                return new OrderModel({
                    storeId: page.get('params.storeId'),
                    collections: {
                        products: orderProductCollection
                    }
                });
            },
            store: function() {
                var page = this,
                    StoreModel = require('models/store');

                return new StoreModel({
                    id: page.get('params.storeId')
                });
            }
        },
        blocks: {
            form_order: function(){
                var page = this;

                return new Form_order({
                    storeId: page.get('params.storeId'),
                    model: page.models.order,
                    collections: _.pick(page.collections, 'suppliers')
                });
            }
        }
    });
});