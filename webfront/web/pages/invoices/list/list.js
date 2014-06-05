define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        InvoicesCollection = require('collections/invoices');

    return Page.extend({
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!../localNavigation.html')
        },
        localNavigationActiveLink: 'list',
        params: {
            storeId: null
        },
        collections: {
            invoices: function() {
                var page = this,
                    invoices = new InvoicesCollection();

                invoices.storeId = page.params.storeId;

                return invoices;
            }
        }
    });
});