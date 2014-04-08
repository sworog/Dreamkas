define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        ProductInvoicesCollection = require('collections/productInvoices'),
        ProductModel = require('models/product'),
        StoreProductModel = require('models/storeProduct'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_product_view',
        params: {
            productId: null
        },
        currentUserModel: currentUserModel,
        partials: {
            '#content': require('tpl!./templates/invoices.html')
        },
        initialize: function() {
            var page = this;

            if (!currentUserModel.stores.length || !LH.isAllow('stores/{store}/products/{product}', 'GET::invoiceProducts')){
                new Page403();
                return;
            }

            if (LH.isAllow('products', 'GET::{product}')){
                page.model = new ProductModel({
                    id: page.params.productId
                });
            }

            if (LH.isAllow('stores/{store}/products/{product}', 'GET::invoiceProducts')) {
                page.model = new StoreProductModel({
                    id: page.params.productId
                });
            }

            page.productInvoicesCollection = new ProductInvoicesCollection({
                productId: page.params.productId,
                storeId: currentUserModel.stores.at(0).id
            });

            $.when(page.model.fetch(), page.productInvoicesCollection.fetch()).then(function(){

                page.productModel = page.model.get('product') || page.model.toJSON();
                page.storeProductModel = page.model.get('store') ? page.model.toJSON() : null;

                page.render();
            });
        }
    });
});