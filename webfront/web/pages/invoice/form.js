define(function(require) {
    //requirements
    var Page = require('pages/page'),
        InvoiceModel = require('models/invoice'),
        Form_invoice = require('blocks/form/form_invoice/form_invoice');

    return Page.extend({
        pageName: 'page_invoice_form',
        initialize: function(productId) {
            var page = this;

            page.productId = productId;
        },
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        initModels: {
            invoice: function() {
                return new InvoiceModel()
            }
        },
        initBlocks: function() {
            var page = this;

            new Form_invoice({
                model: page.models.invoice,
                el: document.getElementById('form_invoice')
            });
        }
    });
});