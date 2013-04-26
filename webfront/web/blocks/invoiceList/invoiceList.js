define(
    [
        '/kit/block.js',
        '/collections/invoices.js',
        'tpl!./invoiceList.html',
        'tpl!./row.html'
    ],
    function(block, invoices, main, row) {
        return block.extend({
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