define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        cookies = require('cookies'),
        InvoiceProductsCollection = require('collections/invoiceProducts/invoiceProducts');

    return Model.extend({
        storeId: null,
        fromOrder: null,
        urlRoot: function() {
            return Model.baseApiUrl + '/invoices'
        },
        defaults: {
            paid: false,
            products: new InvoiceProductsCollection()
        },
        saveData: function() {
            return {
                supplier: this.get('supplier'),
                date: this.get('date'),
                products: this.get('products').map(function(productModel) {
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
            var products = new InvoiceProductsCollection();
            products.reset(data.products);

            data.products = products;

            return data;
        }
    });
});