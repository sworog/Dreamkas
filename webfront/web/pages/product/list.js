define(function(require) {
    //requirements
    var Page = require('kit/page'),
        Table_products = require('blocks/table/table_products/table_products'),
        ProductsCollection = require('collections/products');

    return Page.extend({
        templates: {
            '#content': require('tpl!./templates/list.html')
        },
        initCollections: {
            products: function(){
                return new ProductsCollection();
            }
        },
        initBlocks: {
            table_products: function(){
                var page = this;

                return new Table_products({
                    collection: page.collections.products,
                    el: document.getElementById('table_products')
                });
            }
        }
    });
});