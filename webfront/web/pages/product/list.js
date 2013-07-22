define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Table_products = require('blocks/table/table_products/table_products'),
        ProductsCollection = require('collections/products');

    return Page.extend({
        pageName: 'page_product_list',
        templates: {
            '#content': require('tpl!./templates/list.html')
        },
        permissions: {
            products: 'GET'
        },
        initialize: function(){
            var page = this;

            page.productsCollection = new ProductsCollection();

            $.when(page.productsCollection.fetch()).then(function(){
                page.render();

                new Table_products({
                    collection: page.productsCollection,
                    el: document.getElementById('table_products')
                });
            });
        }
    });
});