define(function(require) {
    //requirements
    var Page = require('kit/page'),
        InvoiceModel = require('models/invoice'),
        Form_invoice = require('blocks/form/form_invoice/form_invoice');

    return Page.extend({
        __name__: 'page_invoice_form',
        templates: {
            '#content': require('tpl!./templates/form.html')
        },
        permissions: {
            invoices: 'POST'
        },
        initialize: function() {
            var page = this;

            page.invoiceModel = new InvoiceModel();

            page.render();

            new Form_invoice({
                model: page.invoiceModel,
                el: document.getElementById('form_invoice')
            });
        }
    });
});