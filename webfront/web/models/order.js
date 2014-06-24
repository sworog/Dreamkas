define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model'),
        OrderProductsCollection = require('collections/orderProducts');

    return Model.extend({
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + this.get('storeId') + '/orders'
        },
        defaults: {
            storeId: null
        },
        initialize: function() {
            var model = this,
                orderProductsCollection = new OrderProductsCollection(model.get('products'));

            orderProductsCollection.storeId = model.get('storeId');

            model.collections = {
                products: orderProductsCollection
            };
        },
        saveData: function() {
            return {
                supplier: this.get('supplier'),
                products: this.collections.products.map(function(productModel) {
                    return {
                        product: productModel.get('product.product.id'),
                        quantity: productModel.get('quantity')
                    }
                })
            }
        },
        parse: function() {

            var data = Model.prototype.parse.apply(this, arguments);

            if (this.collections) {
                this.collections.products.reset(data.products);
            }

            return data;
        },
        getCreateInvoiceFromOrderUrl: function() {
            return '/stores/' + this.get('storeId') + '/invoices/create?fromOrder=' + this.id;
        }
    });
});