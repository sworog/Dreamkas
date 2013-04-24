define(
    [
        '/views/kit/block.js',
        '/collections/invoices.js',
        'tpl!./main.html',
        'tpl!./row.html'
    ],
    function(Block, invoices, main, row) {
        return Block.extend({
            initialize: function(){
                var block = this;

                block.render();
            },
            tpl:{
                main: main,
                row: row
            },
            collection: invoices
        });
    }
);