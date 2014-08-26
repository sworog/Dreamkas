define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./modal_invoice.ejs'),
        models: {
            invoice: require('models/invoice/invoice')
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
            },
            'click .invoice__removeLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.models.invoice.destroy().then(function() {
                    e.target.classList.remove('loading');
                });
            }
        },
        blocks: {
            form_invoice: function(opt) {
                var block = this,
                    InvoiceModel = require('models/invoice/invoice'),
                    Form_invoice = require('blocks/form/form_invoice/form_invoice'),
                    form_invoice = new Form_invoice({
                        el: opt.el,
                        model: block.models.invoice || new InvoiceModel()
                    });

                form_invoice.on('submit:success', function(){
                    block.hide();
                });

                return form_invoice;
            },
            form_invoiceProducts: function(opt){
                var block = this,
                    Form_invoiceProducts = require('blocks/form/form_invoiceProducts/form_invoiceProducts');

                return new Form_invoiceProducts({
                    el: opt.el,
                    models: {
                        invoice: block.models.invoice
                    }
                });
            },
            form_supplier: function(opt) {
                var block = this,
                    SupplierModel = require('models/supplier/supplier'),
                    Form_supplier = require('blocks/form/form_supplier/form_supplier'),
                    form_supplier = new Form_supplier({
                        el: opt.el
                    });

                form_supplier.on('submit:success', function(){
                    block.showInvoiceModal();
                    form_supplier.model = new SupplierModel();
                    form_supplier.reset();
                });

                return form_supplier;
            },
            form_product: function(opt) {
                var block = this,
                    ProductModel = require('models/product/product'),
                    Form_product = require('blocks/form/form_product/form_product'),
                    form_product = new Form_product({
                        el: opt.el
                    });

                form_product.on('submit:success', function(){
                    block.blocks.form_invoice.blocks.form_invoiceProducts.renderSelectedProduct(form_product.model.toJSON());
                    block.showInvoiceModal();
                    form_product.model = new ProductModel();
                    form_product.reset();
                });

                return form_product;
            }
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
        }
    });
});