define(function(require, exports, module) {
    //requirements
    var Page = require('page'),
        SuppliersCollection = require('collections/suppliers'),
        OrderModel = require('models/order'),
        OrderProductsCollection = require('collections/orderProducts'),
        Form_order = require('blocks/form/form_order/form_order');

    require('jquery');

    return Page.extend({
        moduleId: module.id,
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!../localNavigation.html')
        },
        isAllow: function() {
            return LH.isAllow('stores/{store}/orders', 'POST');
        },
        collections: {
            suppliers: function(){
                return new SuppliersCollection()
            }
        },
        models: {
            order: function(){
                return new OrderModel({
                    collections: {
                        products: new OrderProductsCollection()
                    }
                });
            }
        },
        blocks: {
            form_order: function(){
                var page = this;

                return new Form_order({
                    model: page.models.order
                });
            }
        }
    });
});