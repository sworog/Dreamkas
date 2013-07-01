define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Table_products = require('blocks/table/table_products/table_products'),
        ProductsCollection = require('collections/products');

    return Page.extend({
        pageName: 'page_product_list',
        templates: {
            '#content': require('tpl!./templates/list.html')
        },
        permissions: {
            products: 'get'
        },
        initialize: function(){
            var page = this;

            page.productsCollection = new ProductsCollection();

            page.render();

            $.when(page.productsCollection.fetch()).then(function(){
                new Table_products({
                    collection: page.productsCollection,
                    el: document.getElementById('table_products')
                });
            });
        }
    });
});