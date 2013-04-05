var InvoicesCollection = Backbone.Collection.extend({
    model: Invoice,
    url: baseApiUrl + "/api/1/invoices.json"
});