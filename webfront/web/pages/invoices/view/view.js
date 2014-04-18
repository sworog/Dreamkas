define(function(require, exports, module) {
    //requirements
    var Page = require('page'),
        SuppliersCollection = require('collections/suppliers'),
        Form_invoice = require('blocks/form/form_invoice/form_invoice'),
        InvoiceModel = require('models/invoice'),
        InvoiceProductsCollection = require('collections/invoiceProducts'),
        currentUserModel = require('models/currentUser');

    return Page.extend({
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!../localNavigation.html')
        },
        params: {
            storeId: null,
            invoiceId: null
        },
        isAllow: function() {
            var page = this;

            return currentUserModel.stores.length && currentUserModel.stores.at(0).id === page.params.storeId;
        },
        collections: {
            suppliers: function() {
                return new SuppliersCollection();
            }
        },
        models: {
            invoice: function() {
                var page = this,
                    invoiceModel = new InvoiceModel({
                        products: new InvoiceProductsCollection()
                    });

                invoiceModel.id = page.params.invoiceId;
                invoiceModel.storeId = page.params.storeId;

                return invoiceModel;
            }
        },
        blocks: {
            form_invoice: function() {
                var page = this;

                return new Form_invoice({
                    storeId: page.params.storeId,
                    model: page.models.invoice,
                    collections: _.pick(page.collections, 'suppliers')
                });
            }
        }
    });
});