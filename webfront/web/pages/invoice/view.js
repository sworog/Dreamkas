define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Invoice = require('blocks/invoice/invoice'),
        InvoiceModel = require('models/invoice'),
        InvoiceProductsCollection = require('collections/invoiceProducts');

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

            page.invoiceProductsCollection = new InvoiceProductsCollection({
                invoiceId: page.invoiceId
            });

            $.when(page.invoiceModel.fetch(), page.invoiceProductsCollection.fetch()).then(function(){
                page.render();

                new Invoice({
                    invoiceModel: page.invoiceModel,
                    invoiceProductsCollection: page.invoiceProductsCollection,
                    editMode: page.params.editMode,
                    el: document.getElementById('invoice')
                });
            });
        }
    });
});