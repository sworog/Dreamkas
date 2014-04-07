define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        Invoice = require('blocks/invoice/invoice'),
        InvoiceModel = require('models/invoice'),
        InvoiceProductsCollection = require('collections/invoiceProducts'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403'),
        Page404 = require('pages/errors/404');

    return Page.extend({
        __name__: 'page_invoice_view',
        params: {
            invoiceId: null,
            editMode: false
        },
        partials: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function(pageParams) {
            var page = this;

            if (!LH.isAllow('stores/{store}/invoices/{invoice}/products', 'POST')){
                new Page403();
                return;
            }

            if (currentUserModel.stores.length){
                pageParams.storeId = currentUserModel.stores.at(0).id;
            }

            if (!pageParams.storeId){
                new Page403();
                return;
            }

            page.invoiceModel = new InvoiceModel({
                id: page.params.invoiceId
            });

            page.invoiceProductsCollection = new InvoiceProductsCollection({
                invoiceId: page.invoiceId,
                storeId: pageParams.storeId
            });

            $.when(page.invoiceModel.fetch(), page.invoiceProductsCollection.fetch()).then(function(){
                page.render();

                new Invoice({
                    invoiceModel: page.invoiceModel,
                    invoiceProductsCollection: page.invoiceProductsCollection,
                    editMode: page.params.editMode,
                    el: document.getElementById('invoice')
                });
            }, function() {
                new Page404();
            });
        }
    });
});