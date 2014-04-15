define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        InvoiceProductModel = require('models/invoiceProduct'),
        currentUserModel = require('models/currentUser'),
        SuppliersCollection = require('collections/suppliers'),
        InputDate = require('blocks/inputDate/inputDate'),
        Autocomplete = require('blocks/autocomplete/autocomplete'),
        Select_suppliers = require('blocks/select/select_suppliers/select_suppliers'),
        totalSum = require('tpl!blocks/form/form_invoice/totalSum.html'),
        router = require('router');

    return Form.extend({
        redirectUrl: function() {
            return '/stores/' + currentUserModel.stores.at(0).id + '/invoices';
        },
        el: '.form_invoice',
        storeId: null,
        editedProductModel: null,
        editedProductField: null,
        nextEditedProductModel: null,
        nextEditedProductField: null,
        model: null,
        collections: {
            suppliers: new SuppliersCollection()
        },
        $errorTr: $('<tr class="table__orderProduct__error"><td colspan="6"></td></tr>'),
        events: {
            'click tr[data-product_cid]': function(e) {
                var block = this,
                    tr = e.currentTarget,
                    productCid = tr.dataset.product_cid,
                    orderProductModel = block.model.get('products').find(function(model) {
                        return model.cid === productCid;
                    });

                if (block.editedProductModel && block.editedProductModel !== orderProductModel) {
                    block.nextEditedProductModel = orderProductModel;
                } else if (block.editedProductModel !== orderProductModel) {
                    block.editProduct(orderProductModel);
                }
            },
            'keydown .table__invoiceProduct input': function(e) {
                var block = this;

                if (e.keyCode === 13) {
                    e.preventDefault();
                    e.target.classList.add('preloader_stripes');
                    block.validateProducts();
                }
            },
            'blur .table__invoiceProduct input': function(e) {
                var block = this;

                if (block.editedProductField === e.target) {
                    e.target.classList.add('preloader_stripes');
                    block.validateProducts(e.target.dataset.name, e.target.value);
                }
            },
            'focus .table__invoiceProduct input': function(e) {
                var block = this;

                if (!block.editedProductField) {
                    block.editedProductField = e.target;
                } else if (block.editedProductField !== e.target) {
                    block.editedProductField.focus();
                    block.nextEditedProductField = e.target;
                }
            },
            'click .form_invoice__removeProductLink': function(e) {
                e.stopPropagation();

                var block = this,
                    productCid = e.target.dataset.product_cid,
                    orderProductModel = block.model.get('products').find(function(model) {
                        return model.cid === productCid;
                    });

                orderProductModel.id = null;

                orderProductModel.destroy();
            }
        },
        listeners: {
            'blocks.autocomplete': {
                select: function(storeProduct) {
                    var block = this,
                        invoiceProductModel = new InvoiceProductModel({
                            product: storeProduct.product,
                            priceEntered: storeProduct.product.purchasePrice
                        });

                    block.model.get('products').push(invoiceProductModel);

                    var productRow = block.el.querySelector('[data-product_cid="' + invoiceProductModel.cid + '"]');

                    productRow.classList.add('preloader_stripes');

                    $.when(block.model.validateProducts()).then(function(){
                        productRow.classList.remove('preloader_stripes');
                    }, function(){
                        productRow.classList.remove('preloader_stripes');
                        console.error(arguments);
                    });
                }
            }
        },
        initialize: function() {
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.model.get('products').on('add', function(orderProductModel) {
                block.editProduct(orderProductModel);
            });

            block.model.get('products').on('add remove change reset', function() {
                block.renderTotalSum();
            });

            block.blocks = {
                autocomplete: new Autocomplete(),
                inputDate: new InputDate(),
                select_suppliers: new Select_suppliers({
                    collections: _.pick(block.collections, 'suppliers')
                })
            };
        },
        editProduct: function(orderProductModel) {
            var block = this,
                tr = block.el.querySelector('tr[data-product_cid="' + orderProductModel.cid + '"]');

            block.editedProductModel = orderProductModel;

            tr.classList.add('table__invoiceProduct_edit');
            tr.querySelector('[autofocus]').focus();
        },
        validateProducts: function() {
            var block = this;

            block.removeProductError();

            return $.when(block.model.validateProducts()).then(function(){
                block.finishEdit();
            }, function(){
                console.log(arguments);
            });
        },
        finishEdit: function() {
            var block = this,
                trs = block.el.querySelectorAll('.table__invoiceProduct_edit'),
                inputs = block.el.querySelectorAll('.table__invoiceProduct_edit input');

            block.removeProductError();

            if (inputs.length) {
                _.forEach(inputs, function(input) {
                    input.classList.remove('preloader_stripes');
                });
            }

            if (block.nextEditedProductField) {
                block.editedProductField = block.nextEditedProductField;
                block.nextEditedProductField = null;
                block.editedProductField.focus();
                return;
            }

            block.editedProductModel = null;
            block.editedProductField = null;

            if (trs) {
                _.forEach(trs, function(tr) {
                    tr.classList.remove('table__invoiceProduct_edit');
                });
            }

            block.el.querySelector('.autocomplete').focus();
        },
        showProductError: function(error) {
            var block = this,
                tr = block.el.querySelector('.table__invoiceProduct_edit'),
                quantityInput = block.el.querySelector('.table__invoiceProduct_edit [data-name="quantity"]');

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
        renderTotalSum: function(){
            var block = this;

            $(block.el.querySelector('.form__totalSum')).replaceWith(totalSum({
                invoiceModel: block.model
            }));
        }
    });
});