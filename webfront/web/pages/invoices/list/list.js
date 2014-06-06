define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page'),
        InvoicesCollection = require('collections/invoices');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            localNavigation: require('rv!blocks/localNavigation/localNavigation_invoices.html'),
            globalNavigation: require('rv!blocks/globalNavigation/globalNavigation_store.html')
        },
        resources: {
            invoices: function() {
                var page = this,
                    invoices = new InvoicesCollection();

                invoices.storeId = page.get('params.storeId');

                return invoices;
            },
            store: function() {
                var page = this,
                    StoreModel = require('models/store');

                return new StoreModel({
                    id: page.get('params.storeId')
                });
            }
        }
    });
});