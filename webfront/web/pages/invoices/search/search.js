define(function(require) {
    //requirements
    var Page = require('kit/page/page'),
        InvoicesCollection = require('collections/invoices'),
        Form_invoiceSearch = require('blocks/form/form_invoiceSearch/form_invoiceSearch'),
        when = require('when');

    return Page.extend({
        localNavigationActiveLink: 'search',
        templates: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_invoices.ejs'),
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation_store.ejs')
        },
        params: {
            storeId: null,
            numberOrSupplierInvoiceNumber: null
        },
        collections: {
            invoices: function() {
                var page = this,
                    invoices = new InvoicesCollection();

                invoices.storeId = page.params.storeId;

                return invoices;
            }
        },
        models: {
            store: function() {
                var page = this,
                    StoreModel = require('models/store');

                return new StoreModel({
                    id: page.get('params.storeId')
                });
            }
        },
        fetch: function() {
            var page = this;

            return when.all([
                page.collections.invoices.fetch({
                    data: {
                        numberOrSupplierInvoiceNumber: page.params.numberOrSupplierInvoiceNumber
                    }
                })
            ]);
        },
        blocks: {
            form_invoiceSearch: function() {
                var page = this;

                return new Form_invoiceSearch({
                    collections: _.pick(page.collections, 'invoices')
                });
            }
        }
    });
});