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
        template: require('tpl!./template.html'),
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

                block.editProduct(orderProductModel);
            },
            'click .form_orderProduct__cancelLink': function(e) {
                var block = this;
                block.$tr.detach();
            }
        },
        listeners: {
            'model': {
                change: function(){
                    console.log(1);
                }
            },
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
        submitSucess: function(){
            document.location.reload();
        },
        initialize: function() {
            var block = this;

            block.model.get('collections.products').on('change add remove', function(){
                block.el.classList.add('form_changed');
            });

            block.model.get('collections.products').on('add', function(orderProductModel){
                block.editProduct(orderProductModel);
            });

            block.blocks = {
                autocomplete: new Autocomplete()
            };
        },
        editProduct: function(orderProductModel){
            var block = this,
                tr = block.el.querySelector('tr[data-product_cid="' + orderProductModel.cid + '"]');

            block.cancelProductEditing();

            tr.classList.add('table__orderProduct_edit');
            tr.querySelector('[autofocus]').focus();
        },
        cancelProductEditing: function(){
            var block = this,
                tr = block.el.querySelector('.table__orderProduct_edit');

            if (tr){
                tr.classList.remove('table__orderProduct_edit');
            }
        }
    });
});