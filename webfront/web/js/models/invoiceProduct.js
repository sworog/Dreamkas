var InvoiceProduct = BasicModel.extend({
    modelName: "invoiceProduct",

    url: function() {
        var url;
        if(this.get('invoice')) {
            url = baseApiUrl;
            url = url + "/invoices/" + this.get('invoice') + "/products.json"
        }
        return url;
    },

    defaults: {
        id: null,
        product: null,
        productModel: null,
        invoice: null,
        invoiceModel: null,
        quantity: null,
        price: null
    },

    excludeSaveFields: [
        'invoice',
        'invoiceModel',
        'productModel'
    ],

    parse: function(response, options) {
        var data = response;
        data.invoiceModel = data.invoice;
        data.invoice = data.invoiceModel.id;
        data.productModel = data.product;
        data.product = data.productModel.id;
        return data;
    }
})