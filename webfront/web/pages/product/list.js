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
        initCollections: {
            products: function() {
                return new ProductsCollection();
            }
        },
        initBlocks: function() {
            var page = this;

            new Table_products({
                collection: page.collections.products,
                el: document.getElementById('table_products')
            });
        }
    });
});