define(
    [
        '/kit/block.js',
        '/collections/products.js',
        'tpl!./productTable.html',
        'tpl!./row.html'
    ],
    function(block, products, main, row) {
        return block.extend({
            initialize: function(){
                var block = this;
                block.render();
            },
            tpl: {
                main: main,
                row: row
            },
            collection: products
        });
    }
);