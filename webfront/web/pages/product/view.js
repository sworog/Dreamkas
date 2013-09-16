define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Product = require('blocks/product/product'),
        ProductModel = require('models/product'),
        StoreProductModel = require('models/storeProduct'),
        currentUserModel = require('models/currentUser'),
        Page403 = require('pages/403/403');

    return Page.extend({
        __name__: 'page_product_view',
        productId: null,
        templates: {
            '#content': require('tpl!./templates/view.html')
        },
        initialize: function(productId) {
            var page = this;

            if (!LH.isAllow('stores/{store}/products/{product}')){
                new Page403();
                return;
            }

            if (!LH.isAllow('products', 'GET::{product}') && !currentUserModel.stores.length){
                new Page403();
                return;
            }

            if (LH.isAllow('products', 'GET::{product}')){
                page.model = new ProductModel({
                    id: page.productId
                });
            }

            if (LH.isAllow('stores/{store}/products/{product}', 'GET') && currentUserModel.stores.length) {
                page.model = new StoreProductModel({
                    id: page.productId
                });
            }

            $.when(page.model.fetch()).then(function(){

                page.productModel = page.model.product || page.model;
                page.storeProductModel = page.model.store ? page.model : null;

                page.render();

                console.log(page.productModel);

                new Product({
                    productModel: page.productModel,
                    storeProductModel: page.storeProductModel,
                    el: document.getElementById('product')
                });
            });
        }
    });
});