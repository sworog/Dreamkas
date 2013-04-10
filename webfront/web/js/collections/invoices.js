var InvoicesCollection = Backbone.Collection.extend({
    model: Invoice,
    url: baseApiUrl + "/invoices.json"
});