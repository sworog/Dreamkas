define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        SuppliersCollection = require('collections/suppliers'),
        Form_invoice = require('blocks/form/form_invoice/form_invoice'),
        InvoiceModel = require('models/invoice'),
        InvoiceProductsCollection = require('collections/invoiceProducts'),
        currentUserModel = require('models/currentUser.inst');

    return Page.extend({
        templates: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_invoices.ejs'),
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation_store.ejs')
        },
        localNavigationActiveLink: 'create',
        params: {
            storeId: null,
            fromOrder: null
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

                invoiceModel.storeId = page.params.storeId;
                invoiceModel.fromOrder = page.params.fromOrder;

                return invoiceModel;
            },
            store: function() {
                var page = this,
                    StoreModel = require('models/store');

                return new StoreModel({
                    id: page.get('params.storeId')
                });
            }
        },
        fetchData: function() {
            var page = this;

            return _.values(page.collections).concat(_.filter(page.models, function(model) {
                return model && (model.id || null != model.fromOrder);
            }));
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