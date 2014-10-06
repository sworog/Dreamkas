define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        id: 'modal_invoice',
        invoiceId: null,
        models: {
            invoice: null
        },
        events: {
            'click .invoice__removeLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.models.invoice.destroy().then(function() {
                    e.target.classList.remove('loading');
                    block.hide();
                });
            }
        },
        blocks: {
            form_invoice: function(){
                var Form_invoice = require('blocks/form/invoice/invoice');

                return new Form_invoice({
                    model: this.models.invoice
                });
            },
            form_invoiceProducts: function(){
                var Form_invoiceProducts = require('blocks/form/invoiceProducts/invoiceProducts');

                return new Form_invoiceProducts({
                    collection: this.models.invoice.collections.products
                });
            }
        },
        render: function(data) {
            var InvoiceModel = require('resources/invoice/model');

            this.models.invoice = PAGE.collections.stockMovements.get(data && data.invoiceId) || new InvoiceModel;

            Modal.prototype.render.apply(this, arguments);
        }
    });
});