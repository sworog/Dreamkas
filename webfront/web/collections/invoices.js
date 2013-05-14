define(
    [
        './baseCollection.js',
        '/models/invoice.js'
    ],
    function(baseCollection, invoiceModel) {
        return baseCollection.extend({
            model: invoiceModel,
            url: baseApiUrl + "/invoices.json"
        });
    }
);