var InvoiceProduct = BasicModel.extend({
    modelName: "invoiceProduct",

    url: function() {
        var url;
        if(this.get('invoice')) {
            url = baseApiUrl;
            url = url + "/api/1/invoices/" + this.get('invoice') + "/products.json"
        }
        return url;
    },

    defaults: {
        id: null,
        product: null,
        invoice: null,
        quantity: null,
        price: null
    }
})