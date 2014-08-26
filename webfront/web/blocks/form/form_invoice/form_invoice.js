define(function(require, exports, module) {
    //requirements
    var From = require('kit/form/form');

    return From.extend({
        template: require('ejs!./form_invoice.ejs'),
        model: require('models/invoice/invoice'),
        blocks: {
            select_suppliers: require('blocks/select/select_suppliers/select_suppliers'),
            inputDate: require('blocks/inputDate/inputDate'),
            form_invoiceProducts: function(opt){
                var block = this,
                    Form_invoiceProducts = require('blocks/form/form_invoiceProducts/form_invoiceProducts');

                return new Form_invoiceProducts({
                    el: opt.el,
                    collection: block.model.collections.products
                });
            }
        }
    });
});