define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        Product = require('blocks/product/product'),
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
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function() {
            var page = this;

            if (
                !LH.isAllow('products', 'GET::{product}')
                && (!LH.isAllow('stores/{store}/products/{product}') || !currentUserModel.stores.length)
            ){
                new Page403();
                return;
            }

            if (LH.isAllow('products', 'GET::{product}')){
                page.model = new ProductModel({
                    id: page.params.productId
                });
            }

            if (LH.isAllow('stores/{store}/products/{product}', 'GET') && currentUserModel.stores.length) {
                page.model = new StoreProductModel({
                    id: page.params.productId
                });
            }

            $.when(page.model.fetch()).then(function(){

                page.productModel = page.model.get('product') || page.model.toJSON();
                page.storeProductModel = page.model.get('store') ? page.model.toJSON() : null;

                page.render();

                new Product({
                    productModel: page.productModel,
                    storeProductModel: page.storeProductModel,
                    el: document.getElementById('product')
                });
            });
        }
    });
});