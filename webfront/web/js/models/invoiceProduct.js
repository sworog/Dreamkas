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
    },

    toJSON: function(options) {
        _.defaults(options || (options = {}), {
            toSave: false
        });

        var data = BasicModel.prototype.toJSON.call(this, options)

        if(options.toSave){
            data[this.modelName].invoice = undefined;
        }

        return data;
    },

    parse: function(response, options) {
        var data = response;
        data.invoice = data.invoice.id;
        data.product = data.product.id;
        return data;
    }
})