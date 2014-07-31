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
            supplier: null,
            date: null,
            accepter: null,
            legalEntity: null,
            includesVAT: true,
            supplierInvoiceNumber: null,
            products: new InvoiceProductsCollection(),
            order: null,
            paid: false,
            store: null
        },
        saveData: function(){
            var supplier = this.get('supplier');
            if (supplier instanceof Object) {
                supplier = supplier.id;
            }
            var order = this.get('order');
            if (order instanceof Object) {
                order = order.id;
            }
            return {
                order: order,
                supplier: supplier,
                date: this.get('date'),
                accepter: this.get('accepter'),
                legalEntity: this.get('legalEntity'),
                includesVAT: this.get('includesVAT'),
                supplierInvoiceNumber: this.get('supplierInvoiceNumber'),
                products: this.get('products').map(function(productModel) {
                    return productModel.getData();
                }),
                paid: this.get('paid'),
                store: this.get('store')
            }
        },
        parse: function(data) {
            var products = new InvoiceProductsCollection();
            products.reset(data.products);

            data.products = products;

            return data;
        },
        fetch: function(options) {
            var model = this;

            return Model.prototype.fetch.call(this, options);
        },
        validateProducts: function(){
            var model = this;

            return model.save(null, {
                url: this.url() + '?validate=1&validationGroups=products'
            });
        }
    });
});