define(
    [
        '/models/invoice.js'
    ],
    function(invoiceModel) {
        return Backbone.Collection.extend({
            model: invoiceModel,
            url: baseApiUrl + "/invoices.json"
        });
    }
);