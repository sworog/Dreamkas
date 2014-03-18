define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        SuppliersCollection = require('collections/suppliers'),
        OrderModel = require('models/order'),
        OrderProductsCollection = require('collections/orderProducts'),
        Form_order = require('blocks/form/form_order/form_order');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        partials: {
            '#content': require('tpl!./content.html')
        },
        orderId: null,
        permissions: function() {
            return !LH.isAllow('stores', 'GET::{store}/orders/{order}');
        },
        initialize: function() {
            var page = this;

            page.collections = {
                suppliers: new SuppliersCollection()
            };

            page.models = {
                order: new OrderModel({
                    id: page.orderId
                })
            };

            $.when(page.collections.suppliers.fetch(), page.models.order.fetch()).done(function() {
                page.render();
            });
        },
        render: function() {
            var page = this;

            Page.prototype.render.apply(page, arguments);

            page.blocks = {
                form_order: new Form_order({
                    model: page.models.order
                })
            }
        }
    });
});