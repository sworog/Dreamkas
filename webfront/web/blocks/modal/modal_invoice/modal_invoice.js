define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        SupplierModel = require('models/supplier/supplier'),
        ProductModel = require('models/product/product');

    return Block.extend({
        el: '.modal_invoice',
        collections: {
            suppliers: null,
            invoices: null
        },
        models: {
            invoice: null
        },
        events: {
            'click .addSupplierLink': function() {
                var block = this;

                block.showSupplierModal();
            },
            'click .addProductLink': function() {
                var block = this;

                block.showProductModal();
            },
            'click .invoiceModalLink': function() {
                var block = this;

                block.showInvoiceModal();
            }
        },
        blocks: {
            form_invoice: function() {
                var block = this,
                    InvoiceModel = require('models/invoice/invoice'),
                    Form_invoice = require('blocks/form/form_invoice/form_invoice'),
                    form_invoice = new Form_invoice({
                        el: block.$('.form_invoice'),
                        collection: block.collections.invoices,
                        model: block.models.invoice || new InvoiceModel()
                    });

                form_invoice.listenTo(form_invoice, 'submit:success', function(){
                    block.$el.modal('hide');
                });

                return form_invoice;
            },
            form_supplier: function() {
                var block = this,
                    Form_supplier = require('blocks/form/form_supplier/form_supplier'),
                    form_supplier = new Form_supplier({
                        el: block.$('.form_supplier'),
                        collection: block.collections.suppliers
                    });

                form_supplier.on('submit:success', function(){
                    block.showInvoiceModal();
                    form_supplier.model = new SupplierModel();
                    form_supplier.clear();
                });

                return form_supplier;
            },
            form_product: function() {
                var block = this,
                    Form_product = require('blocks/form/form_product/form_product'),
                    form_product = new Form_product({
                        el: block.$('.form_product')
                    });

                form_product.on('submit:success', function(){
                    block.blocks.form_invoice.blocks.form_invoiceProducts.renderSelectedProduct(form_product.model.toJSON());
                    block.showInvoiceModal();
                    form_product.model = new ProductModel();
                    form_product.clear();
                });

                return form_product;
            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.listenTo(block.collections.suppliers, {
                'add': function(supplierModel){
                    block.renderSupplierSelect(supplierModel)
                }
            });
        },
        showSupplierModal: function() {
            var block = this;

            block.$('.modal__dialog_supplier')
                .removeClass('modal__dialog_hidden')
                .siblings('.modal-dialog')
                .addClass('modal__dialog_hidden');
        },
        showInvoiceModal: function() {
            var block = this;

            block.$('.modal__dialog_invoice')
                .removeClass('modal__dialog_hidden')
                .siblings('.modal-dialog')
                .addClass('modal__dialog_hidden');
        },
        showProductModal: function() {
            var block = this;

            block.$('.modal__dialog_product')
                .removeClass('modal__dialog_hidden')
                .siblings('.modal-dialog')
                .addClass('modal__dialog_hidden');
        },
        renderSupplierSelect: function(supplierModel){
            var block = this,
                select_suppliers = require('ejs!blocks/select/select_suppliers/template.ejs');

            block.$('.select_suppliers').replaceWith(select_suppliers({
                selected: supplierModel.id,
                collections: {
                    suppliers: block.collections.suppliers
                }
            }));
        }
    });
});