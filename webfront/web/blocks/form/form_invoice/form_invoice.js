define(function(require, exports, module) {
    //requirements
    var From = require('kit/form/form');

    return From.extend({
        el: '.form_invoice',
        model: function(){
            var block = this,
                InvoiceModel = require('models/invoice/invoice');

            return new InvoiceModel();
        },
        blocks: {
            inputDate: require('blocks/inputDate/inputDate'),
            form_invoiceProducts: function(){
                var block = this,
                    Form_invoiceProducts = require('blocks/form/form_invoiceProducts/form_invoiceProducts');

                return new Form_invoiceProducts({
                    collection: block.model.collections.products
                });
            }
        }
    });
});