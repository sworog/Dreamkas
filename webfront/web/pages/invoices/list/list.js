define(function(require, exports, module) {
    //requirements
    var Page = require('pages/store');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_invoices.ejs')
        },
        collections: {
            invoices: function() {
                var page = this,
                    InvoicesCollection = require('collections/invoices'),
                    invoicesCollection = new InvoicesCollection();

                invoicesCollection.storeId = page.get('params.storeId');

                return invoicesCollection;
            }
        }
    });
});