var InvoicesCollection = Backbone.Collection.extend({
    model: Invoice,
    url: 'http://lighthouse/api/1/invoices.json'
});