define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        el: '.modal_invoice',
        collections: {
            suppliers: null,
            invoices: null
        },
        events: {
            'click .addSupplierLink': function() {
                var block = this;

                block.showSupplierModal();
            },
            'click .invoiceModalLink': function() {
                var block = this;

                block.showInvoiceModal();
            }
        },
        blocks: {
            form_invoice: function() {
                var block = this,
                    Form_invoice = require('blocks/form/form_invoice/form_invoice'),
                    form_invoice = new Form_invoice({
                        el: block.$('.form_invoice'),
                        collection: block.collections.invoices
                    });

                form_invoice.on('submit:success', function(){

                    block.$el.one('hidden.bs.modal', function(e) {
                        PAGE.render();
                    });

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
                });

                return form_supplier;
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
        renderSupplierSelect: function(supplierModel){
            var block = this,
                supplierTemplate = require('ejs!blocks/select/select_suppliers/template.ejs');

            block.$('.select_supplier').replaceWith(supplierTemplate({
                selected: supplierModel.id,
                collections: {
                    suppliers: block.collections.suppliers
                }
            }));
        }
    });
});