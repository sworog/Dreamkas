define(
    [
        '/views/kit/block.js',
        '/collections/products.js',
        'tpl!./main.html',
        'tpl!./row.html'
    ],
    function(Block, products, main, row) {
        return Block.extend({
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