define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        OrderModel = require('models/order'),
        Form_orderProduct = require('blocks/form/form_orderProduct/form_orderProduct'),
        Table_orderProducts = require('blocks/table/table_orderProducts/table_orderProducts'),
        OrderProductsCollection = require('collections/orders');

    require('jquery');
    require('lodash');

    return Form.extend({
        __name__: module.id,
        redirectUrl: '/orders',
        el: '.form_order',
        model: new OrderModel({
            products: new OrderProductsCollection()
        }),
        initialize: function(){
            var block = this;

            block.blocks = {
                form_orderProduct: new Form_orderProduct({
                    collection: block.model.get('products')
                }),
                orderProducts_table: new Table_orderProducts({
                    collection: block.model.get('products'),
                    el: block.el.parentNode.getElementsByClassName('order__productsTable')
                })
            }
        }
    });
});