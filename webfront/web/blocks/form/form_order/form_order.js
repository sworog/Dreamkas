define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form'),
        router = require('router'),
        Autocomplete = require('blocks/autocomplete/autocomplete'),
        Select_suppliers = require('blocks/select/select_suppliers/select_suppliers'),
        productSum = require('ejs!blocks/table/table_orderProducts/productSum.ejs'),
        $ = require('jquery'),
        _ = require('lodash');

    var form;

    return Form.extend({
        el: '.form_order',
        storeId: null,
        redirectUrl: function(){
            return '/stores/' + this.storeId + '/orders';
        },
        model: function(){
            var OrderModel = require('models/order');

            return new OrderModel();
        },
        collections: {
            suppliers: null
        },
        editedProductModel: null,
        nextEditedProductModel: null,
        $errorTr: $('<tr class="table__orderProduct__error"><td colspan="5"></td></tr>'),
        events: {
            'change [name]': function(e) {
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

                if (block.editedProductModel && block.editedProductModel !== orderProductModel) {
                    block.nextEditedProductModel = orderProductModel;
                } else {
                    block.editProduct(orderProductModel);
                }
            },
            'blur .table__orderProduct input': function(e) {
                var block = this;

                if (block.editedProductModel) {
                    e.target.classList.add('preloader_stripes');
                    block.validateEditedProduct(e.target.dataset.name, e.target.value);
                }
            },
            'keydown .table__orderProduct input': function(e) {
                var block = this;

                if (e.keyCode === 13) {
                    e.preventDefault();
                    e.target.classList.add('preloader_stripes');
                    block.validateEditedProduct(e.target.dataset.name, e.target.value);
                }
            },
            'keyup .table__orderProduct input': function(e) {
                var block = this;

                if (e.keyCode === 13 || e.keyCode === 27) {
                    return;
                }

                block.renderProductSum(e.target.value);
            },
            'click .form_order__removeProductLink': function(e) {
                e.stopPropagation();

                var block = this,
                    productCid = e.target.dataset.product_cid,
                    orderProductModel = block.model.get('collections.products').find(function(model) {
                        return model.cid === productCid;
                    });

                orderProductModel.id = null;

                orderProductModel.destroy();
            }
        },
        listeners: {
            'model': {
                change: function() {
                    var block = this;
                    block.el.classList.add('form_changed');
                }
            },
            'blocks.autocomplete': {
                select: function(product) {
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

            Form.prototype.initialize.apply(block, arguments);

            block.model.collections.products.on('change add remove', function() {
                block.el.classList.add('form_changed');
            });

            block.model.collections.products.on('add', function(orderProductModel) {
                block.editProduct(orderProductModel);
            });

            block.blocks = {
                autocomplete: new Autocomplete(),
                select_suppliers: new Select_suppliers({
                    collections: _.pick(block.collections, 'suppliers')
                })
            };
        },
        editProduct: function(orderProductModel) {
            var block = this,
                tr = block.el.querySelector('tr[data-product_cid="' + orderProductModel.cid + '"]');

            block.editedProductModel = orderProductModel;

            tr.classList.add('table__orderProduct_edit');
            tr.querySelector('[autofocus]').focus();
        },
        validateEditedProduct: function(key, value) {
            var block = this,
                changes = {};

            block.removeProductError();

            changes[key] = value;

            block.editedProductModel.set({
                storeId: block.storeId,
                orderId: block.model.id
            });

            block.editedProductModel.id = null;
            block.editedProductModel.set('id', null);

            block.editedProductModel.save(changes, {
                success: function() {
                    block.finishEdit();

                    if (block.nextEditedProductModel) {
                        block.editProduct(block.nextEditedProductModel);
                    }

                    block.nextEditedProductModel = null;
                },
                error: function(model, res) {
                    block.showProductError(res.responseJSON);
                    block.nextEditedProductModel = null;
                }
            });
        },
        finishEdit: function() {
            var block = this,
                tr = block.el.querySelector('.table__orderProduct_edit'),
                quantityInput = block.el.querySelector('.table__orderProduct_edit [data-name="quantity"]');

            block.editedProductModel = null;
            block.removeProductError();

            if (quantityInput) {
                quantityInput.classList.remove('preloader_stripes');
            }

            if (tr) {
                tr.classList.remove('table__orderProduct_edit');
            }

            block.el.querySelector('.autocomplete').focus();
        },
        renderProductSum: function(quantity) {
            var block = this,
                productSum = block.el.querySelector('.table__orderProduct_edit .table_orderProducts__productSum');

            productSum.innerHTML = productSum({
                orderProductModel: block.editedProductModel,
                quantity: quantity
            });
        },
        showProductError: function(error) {
            var block = this,
                tr = block.el.querySelector('.table__orderProduct_edit'),
                quantityInput = block.el.querySelector('.table__orderProduct_edit [data-name="quantity"]');

            if (quantityInput) {
                quantityInput.classList.add('inputText_error');
                quantityInput.classList.remove('preloader_stripes');
                quantityInput.focus();
            }

            block.$errorTr
                .insertAfter(tr)
                .attr('data-error', error.children.quantity.errors.join(', '))
                .find('td')
                .html(error.children.quantity.errors.join(', '));
        },
        removeProductError: function() {
            var block = this;

            block.$errorTr.detach();
        },
        getCreateInvoiceFromOrderLink: function() {
            var link = '/stores/' + model.get('store').id + '/invoices/create?order='+ model.id;
        }
    });
});