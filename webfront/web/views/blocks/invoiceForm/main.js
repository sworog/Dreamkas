define(
    [
        '/models/Invoice.js',
        'tpl!./main.html'
    ],
    function(Invoice, invoiceForm) {
        return Backbone.Block.extend({
            initialize: function(){
                var block = this;

                block.render();
            },
            templates: {
                main: invoiceForm
            },
            model: Invoice
        });
    }
);