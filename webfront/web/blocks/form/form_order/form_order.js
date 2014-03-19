define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        OrderModel = require('models/order'),
        Form_orderProduct = require('blocks/form/form_orderProduct/form_orderProduct');

    require('jquery');
    require('lodash');

    var form;

    return Form.extend({
        __name__: module.id,
        redirectUrl: '/orders',
        el: '.form_order',
        model: new OrderModel(),
        $tr: $('<tr class="form_order__editor"><td colspan="4"></td></tr>'),
        events: {
            'click tr[data-product_cid]': function(e) {
                var block = this,
                    tr = e.currentTarget,
                    productCid = tr.dataset.product_cid,
                    orderProductModel = block.model.get('collections.products').find(function(model) {
                        return model.cid === productCid;
                    });

                block.renderEditForm(orderProductModel);
            },
            'click .form_orderProduct__cancelLink': function(e){
                var block = this;
                block.$tr.detach();
            }
        },
        initialize: function() {
            var block = this;

            block.blocks = {
                form_orderProduct: new Form_orderProduct({
                    collection: block.model.get('collections.products')
                })
            };
        },
        renderEditForm: function(orderProductModel) {

            delete orderProductModel.id;

            var block = this;

            if (form){
                form.remove();
            }

            form = new Form_orderProduct({
                model: orderProductModel,
                el: Form_orderProduct.prototype.template({
                    model: orderProductModel
                })
            });

            block.$tr.find('td').html(form.el);

            block.$tr.insertBefore(block.el.querySelector('tr[data-product_cid="' + orderProductModel.cid + '"]'));
        }
    });
});