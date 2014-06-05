define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        SuppliersCollection = require('collections/suppliers'),
        Form_invoice = require('blocks/form/form_invoice/form_invoice'),
        InvoiceModel = require('models/invoice'),
        InvoiceProductsCollection = require('collections/invoiceProducts');

    return Page.extend({
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_invoices.deprecated.html')
        },
        params: {
            storeId: null,
            invoiceId: null
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
                        id: page.params.invoiceId,
                        products: new InvoiceProductsCollection()
                    });

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