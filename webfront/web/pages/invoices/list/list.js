define(function(require, exports, module) {
    //requirements
    var Page = require('page'),
        InvoicesCollection = require('collections/invoices'),
        currentUserModel = require('models/currentUser');

    return Page.extend({
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!../localNavigation.html')
        },
        localNavigationActiveLink: 'list',
        params: {
            storeId: null
        },
        isAllow: function() {
            var page = this;

            return currentUserModel.stores.length && currentUserModel.stores.at(0).id === page.params.storeId;
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