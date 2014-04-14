define(function(require) {
    //requirements
    var Page = require('page'),
        InvoicesCollection = require('collections/invoices'),
        Form_invoiceSearch = require('blocks/form/form_invoiceSearch/form_invoiceSearch'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        templates: {
            content: require('tpl!./content.html')
        },
        params: {
            storeId: null
        },
        isAllow: function() {
            var page = this;

            return currentUserModel.stores.length && currentUserModel.stores.at(0).id === page.params.storeId;
        },
        initialize: function(){
            var page = this;

            if (currentUserModel.stores.length){
                page.params.storeId = currentUserModel.stores.at(0).id;
            }

            if (!page.params.storeId){
                new Page403();
                return;
            }

            page.invoicesCollection = new InvoicesCollection();
            page.invoicesCollection.storeId = page.params.storeId;

            page.render();

            new Form_invoiceSearch({
                el: document.getElementById('form_invoiceSearch'),
                invoicesCollection: page.invoicesCollection
            });
        }
    });
});