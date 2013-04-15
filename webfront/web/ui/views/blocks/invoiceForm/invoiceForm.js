define(
    [
        '/ui/models/Invoice.js',
        'tpl!./invoiceForm.html'
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