define(function(require, exports, module) {
    //requirements
    var From = require('kit/form/form');

    return From.extend({
        el: '.form_invoice',
        model: require('models/invoice/invoice'),
        blocks: {
            form_invoiceProducts: function(){
                var block = this,
                    Form_invoiceProducts = require('blocks/form/form_invoiceProducts/form_invoiceProducts');

                return new Form_invoiceProducts({
                    el: block.$el.closest('.modal').find('.form_invoiceProducts'),
                    collection: block.model.collections.products
                });
            }
        }
    });
});