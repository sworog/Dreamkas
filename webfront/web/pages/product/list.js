define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        Table_products = require('blocks/table/table_products/table_products'),
        ProductsCollection = require('collections/products');

    return Page.extend({
        __name__: 'page_product_list',
        partials: {
            '#content': require('ejs!./templates/list.html')
        },
        permissions: {
            products: 'GET'
        },
        initialize: function(){
            var page = this;

            page.storeProductsCollection = new ProductsCollection();

            $.when(page.storeProductsCollection.fetch()).then(function(){
                page.render();

                new Table_products({
                    collection: page.storeProductsCollection,
                    el: document.getElementById('table_products')
                });
            });
        }
    });
});