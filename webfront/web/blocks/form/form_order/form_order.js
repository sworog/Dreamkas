define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        router = require('router'),
        OrderModel = require('models/order'),
        Autocomplete = require('blocks/autocomplete/autocomplete');

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

            },
            'click .form_orderProduct__cancelLink': function(e) {
                var block = this;
                block.$tr.detach();
            }
        },
        listeners: {
            'blocks.autocomplete': {
                select: function(product){
                    var block = this;

                    block.model.get('collections.products').push({
                        quantity: 1,
                        product: product
                    });
                }
            }
        },
        initialize: function() {
            var block = this;

            if (block.model.id) {
                document.getElementById('form_order__removeLink').addEventListener('click', function(e) {
                    e.preventDefault();

                    if (e.target.classList.contains('preloader_rows')) {
                        return;
                    }

                    if (confirm('Вы уверены?')) {
                        e.target.classList.add('preloader_rows');
                        block.disable();

                        block.model.destroy({
                            success: function() {
                                router.navigate('/orders');
                            }
                        });
                    }
                });
            }

            block.blocks = {
                autocomplete: new Autocomplete()
            };
        }
    });
});