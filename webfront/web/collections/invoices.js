define(
    [
        './main.js',
        '/models/invoice.js'
    ],
    function(BaseCollection, invoiceModel) {
        return BaseCollection.extend({
            model: invoiceModel,
            url: baseApiUrl + "/invoices.json"
        });
    }
);