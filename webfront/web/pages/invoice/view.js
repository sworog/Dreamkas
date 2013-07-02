define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Invoice = require('blocks/invoice/invoice'),
        InvoiceModel = require('models/invoice');

    return Page.extend({
        pageName: 'page_invoice_view',
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        permissions: {
            invoices: 'GET::{invoice}'
        },
        initialize: function(invoiceId, params) {
            var page = this;

            page.invoiceId = invoiceId;
            page.params = params || {};
            page.invoiceModel = new InvoiceModel({
                id: page.invoiceId
            });

            $.when(page.invoiceModel.fetch()).then(function(){
                page.render();

                new Invoice({
                    model: page.invoiceModel,
                    invoiceId: page.invoiceId,
                    editMode: page.params.editMode,
                    el: document.getElementById('invoice')
                });
            });
        }
    });
});