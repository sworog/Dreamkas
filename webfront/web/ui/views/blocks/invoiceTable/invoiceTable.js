define(
    [
        '/ui/collections/invoices.js',
        'tpl!./invoiceTable.html',
        'tpl!./invoiceRow.html'
    ],
    function(invoices, invoiceTable, invoiceRow) {
        return Backbone.Block.extend({
            initialize: function(){
                var block = this;

                block.render();
            },
            templates:{
                main: invoiceTable,
                invoiceRow: invoiceRow
            },
            collection: invoices
        });
    }
);