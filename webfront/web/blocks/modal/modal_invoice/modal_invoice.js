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
            }
        }
    });
});