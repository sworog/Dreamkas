define(
    [
        '/ui/models/Invoice.js',
        'json!baseApi/invoices.json'
    ],
    function(InvoiceModel, invoices) {
        var Invoices = Backbone.Collection.extend({
            model: InvoiceModel
        });

        return new Invoices(invoices);
    }
);