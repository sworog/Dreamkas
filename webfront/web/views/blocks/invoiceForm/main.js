define(
    [
        '/models/invoice.js',
        'tpl!./main.html'
    ],
    function(Invoice, invoiceForm) {
        return Backbone.Block.extend({
            initialize: function(){
                var block = this;

                block.render();
            },
            tpl: {
                main: invoiceForm
            },
            model: Invoice
        });
    }
);