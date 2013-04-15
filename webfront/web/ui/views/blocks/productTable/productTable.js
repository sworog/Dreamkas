define(
    [
        '/ui/collections/products.js',
        'tpl!./productTable.html',
        'tpl!./productRow.html'
    ],
    function(products, productTable, productRow) {
        return Backbone.Block.extend({
            initialize: function(){
                var block = this;
                block.render();
            },
            templates: {
                main: productTable,
                productRow: productRow
            },
            collection: products
        });
    }
);