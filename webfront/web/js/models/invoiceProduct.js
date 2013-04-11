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
        data.invoiceModel = new Invoice(data.invoice);
        data.invoice = data.invoice.id;
        data.productModel = new Product(data.product);
        data.product = data.product.id;
        return data;
    }
})