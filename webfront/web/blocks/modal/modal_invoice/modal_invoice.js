define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        el: '.modal_invoice',
        events: {
            'click .addSupplierLink': function(){
                var block = this;

                block.showSupplierModal();
            },
            'click .invoiceModalLink': function(){
                var block = this;

                block.showInvoiceModal();
            }
        },
        blocks: {
            form_invoice: function(){
                var block = this,
                    Form_invoice = require('blocks/form/form_invoice/form_invoice');

                return new Form_invoice({
                    el: block.$('.form_invoice')
                });
            }
        },
        showSupplierModal: function(){
            var block = this;

            block.$('.modal__dialog_supplier')
                .removeClass('modal__dialog_hidden')
                .siblings('.modal-dialog')
                .addClass('modal__dialog_hidden');
        },
        showInvoiceModal: function(){
            var block = this;

            block.$('.modal__dialog_invoice')
                .removeClass('modal__dialog_hidden')
                .siblings('.modal-dialog')
                .addClass('modal__dialog_hidden');
        }
    });
});