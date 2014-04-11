define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        InvoiceModel = require('models/invoice'),
        InvoiceProductModel = require('models/invoiceProduct'),
        currentUserModel = require('models/currentUser'),
        SuppliersCollection = require('collections/suppliers'),
        InputDate = require('blocks/inputDate/inputDate'),
        Autocomplete = require('blocks/autocomplete/autocomplete'),
        Select_suppliers = require('blocks/select/select_suppliers/select_suppliers'),
        router = require('router');

    return Form.extend({
        redirectUrl: function() {
            return '/' + currentUserModel.stores.at(0).id + '/invoices';
        },
        el: '.form_invoice',
        storeId: null,
        listeners: {
            'blocks.autocomplete': {
                select: function(storeProduct) {
                    var block = this,
                        invoiceProductModel = new InvoiceProductModel({
                            product: storeProduct,
                            priceEntered: storeProduct.product.purchasePrice
                        });

                    block.model.get('products').push(invoiceProductModel);
                }
            }
        },
        model: null,
        collections: {
            suppliers: new SuppliersCollection()
        },
        initialize: function() {
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.model.get('products').on('add', function(orderProductModel) {
                block.editProduct(orderProductModel);
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
        }
    });
});