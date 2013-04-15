define(
    [
        '/collections/invoices.js',
        'tpl!./main.html',
        'tpl!./row.html'
    ],
    function(invoices, main, row) {
        return Backbone.Block.extend({
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