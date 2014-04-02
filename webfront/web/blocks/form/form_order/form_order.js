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
        editedProductModel: null,
        events: {
            'change [name]': function(e){
                var block = this;

                block.model.set(e.target.name, e.target.value);
            },
            'click tr[data-product_cid]': function(e) {
                var block = this,
                    tr = e.currentTarget,
                    productCid = tr.dataset.product_cid,
                    orderProductModel = block.model.get('collections.products').find(function(model) {
                        return model.cid === productCid;
                    });

                block.editProduct(orderProductModel);
            },
            'blur .table__orderProduct input': function(){
                var block = this;

                block.validateEditedProduct();
            }
        },
        listeners: {
            'model': {
                change: function(){
                    var block = this;
                    block.el.classList.add('form_changed');
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

            block.editedProductModel = orderProductModel;

            tr.classList.add('table__orderProduct_edit');
            tr.querySelector('[autofocus]').focus();
        },
        validateEditedProduct: function(){
            var block = this;

            block.editedProductModel.save({}, {
                success: function(){
                    console.log(arguments);
                }
            });
        }
    });
});