define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        Table_balance = require('blocks/table/table_balance/table_balance'),
        ProductsCollection = require('collections/products');

    return Page.extend({
        __name__: 'page_common_balance',
        templates: {
            '#content': require('tpl!./templates/balance.html')
        },
        permissions: {
            products: 'GET'
        },
        initialize: function(){
            var page = this;

            page.productsCollection = new ProductsCollection();

            $.when(page.productsCollection.fetch()).then(function(){
                page.render();

                new Table_balance({
                    collection: page.productsCollection,
                    el: document.getElementById('table_balance')
                })
            })
        }
    });
});