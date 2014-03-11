define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Table_invoices = require('blocks/table/table_invoices/table_invoices'),
        InvoicesCollection = require('collections/invoices'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_invoice_list',
        partials: {
            '#content': require('tpl!./templates/list.html')
        },
        initialize: function(pageParams) {
            var page = this;

            if (currentUserModel.stores.length) {
                pageParams.storeId = currentUserModel.stores.at(0).id;
            }

            if (!pageParams.storeId) {
                new Page403();
                return;
            }

            page.invoicesCollection = new InvoicesCollection([], {
                storeId: pageParams.storeId
            });

            $.when(page.invoicesCollection.fetch()).then(function() {
                page.render();

                new Table_invoices({
                    collection: page.invoicesCollection,
                    el: document.getElementById('table_invoices')
                });
            });
        }
    });
});