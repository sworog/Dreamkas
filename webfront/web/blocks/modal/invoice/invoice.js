define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        models: {
            invoice: require('models/invoice/invoice')
        },
        events: {
            'click .invoice__removeLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.models.invoice.destroy().then(function() {
                    e.target.classList.remove('loading');
                });
            }
        },
        blocks: {
            form_invoice: require('blocks/form/invoice/invoice'),
            form_invoiceProducts: require('blocks/form/invoiceProducts/invoiceProducts')
        }
    });
});