define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        ProductReturnsCollection = require('collections/productReturns'),
        ProductModel = require('models/product'),
        StoreProductModel = require('models/storeProduct'),
        currentUserModel = require('models/currentUser.inst'),
        Page403 = require('pages/errors/403');

    return Page.extend({
        __name__: 'page_product_view',
        params: {
            productId: null
        },
        currentUserModel: currentUserModel,
        partials: {
            '#content': require('tpl!./templates/returns.html')
        },
        initialize: function(params) {
            var page = this;

            if (!currentUserModel.stores.length || !LH.isAllow('stores/{store}/products/{product}', 'GET::returnProducts')){
                new Page403();
                return;
            }

            if (LH.isAllow('products', 'GET::{product}')){
                page.model = new ProductModel({
                    id: page.params.productId
                });
            }

            if (LH.isAllow('stores/{store}/products/{product}', 'GET::returnProducts')) {
                page.model = new StoreProductModel({
                    id: page.params.productId
                });
            }

            page.productReturnsCollection = new ProductReturnsCollection({
                productId: page.params.productId,
                storeId: currentUserModel.stores.at(0).id
            });

            $.when(page.model.fetch(), page.productReturnsCollection.fetch()).then(function(){

                page.productModel = page.model.get('product') || page.model.toJSON();
                page.storeProductModel = page.model.get('store') ? page.model.toJSON() : null;

                page.render();
            });
        }
    });
});