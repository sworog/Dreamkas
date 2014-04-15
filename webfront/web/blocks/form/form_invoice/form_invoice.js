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
        table_invoiceProducts = require('tpl!blocks/table/table_invoiceProducts/template.html'),
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

                if (block.editedProductModel !== null && block.editedProductModel !== block.model.get('products').indexOf(orderProductModel)) {
                    block.nextEditedProductModel = orderProductModel;
                } else if (block.editedProductModel !== block.model.get('products').indexOf(orderProductModel)) {
                    block.editProduct(orderProductModel);
                }
            },
            'keydown .table__invoiceProduct input': function(e) {
                var block = this;

                if (e.keyCode === 13) {
                    e.preventDefault();

                    e.target.classList.add('preloader_stripes');
                    block.model.get('products').at(block.editedProductModel).set(e.target.dataset.name, e.target.value);
                    block.validateProducts();
                }
            },
            'blur .table__invoiceProduct input': function(e) {
                var block = this;

                if (block.editedProductField === e.target) {
                    e.target.classList.add('preloader_stripes');
                    block.model.get('products').at(block.editedProductModel).set(e.target.dataset.name, e.target.value);
                    block.validateProducts();
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
                    invoiceProductModel = block.model.get('products').find(function(model) {
                        return model.cid === productCid;
                    });

                block.validationRequest.abort();

                invoiceProductModel.id = null;

                invoiceProductModel.destroy();

                block.validateProducts();
            }
        },
        listeners: {
            'blocks.autocomplete': {
                select: function(storeProduct) {
                    var block = this,
                        products = block.model.get('products'),
                        invoiceProductModel = new InvoiceProductModel({
                            product: storeProduct.product,
                            priceEntered: storeProduct.product.purchasePrice
                        });

                    products.push(invoiceProductModel);

                    block.blocks.autocomplete.el.classList.add('preloader_stripes');

                    $.when(block.model.validateProducts()).then(function() {
                        block.blocks.autocomplete.el.classList.remove('preloader_stripes');
                        block.editProduct(products.at(products.length - 1));
                    }, function() {
                        block.blocks.autocomplete.el.classList.remove('preloader_stripes');
                        console.error(arguments);
                    });
                }
            }
        },
        initialize: function() {
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.model.get('products').on('reset', function() {
                block.finishEdit();
            });

            block.model.on('change:sumTotal', function() {
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
        editProduct: function(invoiceProductModel) {
            var block = this,
                tr = block.el.querySelectorAll('tr[data-product_cid]'),
                index = block.model.get('products').indexOf(invoiceProductModel);

            block.editedProductModel = index;

            tr[index].classList.add('table__invoiceProduct_edit');
            tr[index].querySelector('[autofocus]').focus();
        },
        validateProducts: function() {
            var block = this;

            block.removeProductError();

            block.validationRequest = block.model.validateProducts();

            block.validationRequest.fail(function(res) {
                var errors = res.responseJSON;

                _.forEach(block.el.querySelectorAll('.table__invoiceProduct_edit input'), function(input) {
                    input.classList.remove('preloader_stripes');
                });

                if (errors) {
                    _.forEach(errors.children.products.children, function(error) {
                        _.find(error.children, function(field) {
                            if (field.errors) {
                                block.showProductError(error);
                                return true;
                            }
                        })
                    });
                }
            });
        },
        finishEdit: function() {
            var block = this,
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

            block.renderInvoiceProducts();

            block.el.querySelector('.autocomplete').focus();
        },
        showProductError: function(error) {
            var block = this,
                errorString = '',
                tr = block.el.querySelector('.table__invoiceProduct_edit');

            _.forEach(block.el.querySelectorAll('.table__invoiceProduct_edit input'), function(input) {
                input.classList.remove('preloader_stripes');
            });

            _.forEach(error.children, function(field, key) {
                if (field.errors) {
                    errorString += field.errors.join(', ')
                    block.el.querySelector('.table__invoiceProduct_edit [data-name="' + key + '"]').classList.add('inputText_error');
                }
            });

            block.$errorTr
                .insertAfter(tr)
                .attr('data-error', errorString)
                .find('td')
                .html(errorString);
        },
        removeProductError: function() {
            var block = this;

            _.forEach(block.el.querySelectorAll('.table__invoiceProduct_edit input'), function(input) {
                input.classList.remove('inputText_error');
            });

            block.$errorTr.detach();
        },
        renderTotalSum: function() {
            var block = this;

            $(block.el.querySelector('.form__totalSum')).replaceWith(totalSum());
        },
        renderInvoiceProducts: function() {
            var block = this;

            $(block.el.querySelector('.table_invoiceProducts')).replaceWith(table_invoiceProducts());
        }
    });
});