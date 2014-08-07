define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        cookies = require('cookies'),
        InvoiceProductsCollection = require('collections/invoiceProducts/invoiceProducts');

    return Model.extend({
        storeId: null,
        fromOrder: null,
        collections: {},
        urlRoot: function() {
            return Model.baseApiUrl + '/invoices'
        },
        initialize: function(){
            var model = this;

            model.collections.products = new InvoiceProductsCollection(model.get('products'));

        },
        defaults: {
            paid: false
        },
        saveData: function() {
            return {
                supplier: this.get('supplier'),
                date: this.get('date'),
                products: this.collections.products.map(function(productModel) {
                    return productModel.getData();
                }),
                paid: this.get('paid'),
                store: this.get('store')
            }
        },
        validateProducts: function() {
            var model = this;

            return model.save(null, {
                url: this.url() + '?validate=1&validationGroups=products'
            });
        },
        parse: function(data) {
            var model = this;

            model.collections.products = model.collections.products || new InvoiceProductsCollection();

            model.collections.products.reset(data.products);

            return data;
        }
    });
});