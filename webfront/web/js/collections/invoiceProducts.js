var InvoiceProductsCollection = Backbone.Collection.extend({
    model:InvoiceProduct,
    url: function() {
        return baseApiUrl + "/invoices/"+ this.invoiceId  +"/products.json"
    }
})