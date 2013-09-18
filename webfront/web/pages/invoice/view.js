define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Invoice = require('blocks/invoice/invoice'),
        InvoiceModel = require('models/invoice'),
        InvoiceProductsCollection = require('collections/invoiceProducts');

    return Page.extend({
        __name__: 'page_invoice_view',
        partials: {
            '#content': require('tpl!./templates/view.html')
        },
        permissions: {
            invoices: 'GET::{invoice}'
        },
        initialize: function() {
            var page = this;

            page.invoiceModel = new InvoiceModel({
                id: page.invoiceId
            });

            page.invoiceProductsCollection = new InvoiceProductsCollection({
                invoiceId: page.invoiceId
            });

            $.when(page.invoiceModel.fetch(), page.invoiceProductsCollection.fetch()).then(function(){
                page.render();

                new Invoice({
                    invoiceModel: page.invoiceModel,
                    invoiceProductsCollection: page.invoiceProductsCollection,
                    editMode: page.editMode,
                    el: document.getElementById('invoice')
                });
            });
        }
    });
});