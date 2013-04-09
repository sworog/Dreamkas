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

        if(options.toSave){
            var data = {};
            data[this.modelName] = _.clone(this.attributes)
            data[this.modelName].id = undefined;
            data[this.modelName].invoice = undefined;
            return data;
        }
        else {
            return Backbone.Model.prototype.toJSON.call(this, options);
        }
    },

    parse: function(response, options) {
        var data = response;
        data.invoice = data.invoice.id;
        data.product = data.product.id;
        return data;
    }
})