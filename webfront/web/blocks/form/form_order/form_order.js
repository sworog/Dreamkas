define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        OrderModel = require('models/order'),
        Form_orderProduct = require('blocks/form/form_orderProduct/form_orderProduct');

    require('jquery');
    require('lodash');

    return Form.extend({
        __name__: module.id,
        redirectUrl: '/orders',
        el: '.form_order',
        model: new OrderModel(),
        initialize: function() {
            var block = this;

            block.blocks = {
                form_orderProduct: new Form_orderProduct({
                    collection: block.model.get('collections.products')
                })
            }
        }
    });
});