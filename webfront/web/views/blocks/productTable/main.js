define(
    [
        '/collections/products.js',
        'tpl!./main.html',
        'tpl!./row.html'
    ],
    function(products, main, row) {
        return Backbone.Block.extend({
            initialize: function(){
                var block = this;
                block.render();
            },
            templates: {
                main: main,
                row: row
            },
            collection: products
        });
    }
);