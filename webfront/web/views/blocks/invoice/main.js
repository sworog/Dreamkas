define(
    [
        '/collections/invoices.js',
        'tpl!./main.html',
        'tpl!./head.html',
        'tpl!./row.html'
    ],
    function(invoices, mainTpl, headTpl, rowTpl) {
        return Backbone.Block.extend({
            initialize: function() {
                var block = this;

                block.model = invoices.get(block.invoiceId);
                block.render();
            },
            tpl: {
                main: mainTpl,
                head: headTpl,
                row: rowTpl
            }
        });
    }
);