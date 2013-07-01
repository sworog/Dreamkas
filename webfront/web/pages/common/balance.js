define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Table_balance = require('blocks/table/table_balance/table_balance'),
        ProductsCollection = require('collections/products');

    return Page.extend({
        pageName: 'page_common_balance',
        templates: {
            '#content': require('tpl!./templates/balance.html')
        },
        permissions: {
            balance: 'get'
        },
        initialize: function(){
            var page = this;

            page.productsCollection = new ProductsCollection();

            page.render();

            $.when(page.productsCollection.fetch()).then(function(){
                new Table_balance({
                    collection: page.productsCollection,
                    el: document.getElementById('table_balance')
                })
            })
        }
    });
});