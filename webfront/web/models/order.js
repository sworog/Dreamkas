define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model'),
        OrderProductsCollection = require('collections/orderProducts');

    return Model.extend({
        __name__: module.id,
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + this.get('storeId') + '/orders'
        },
        name: null,
        defaults: {
            collections: {
                products: new OrderProductsCollection()
            }
        },
        saveData: function() {
            return {
                supplier: this.get('supplier'),
                products: this.get('collections.products').map(function(productModel) {
                    return {
                        product: productModel.get('product.product.id'),
                        quantity: productModel.get('quantity')
                    }
                })
            }
        },
        parse: function(data) {

            data.collections = {
                products: new OrderProductsCollection(data.products)
            };

            return data;
        },
        getCreateInvoiceFromOrderUrl: function() {
            return '/stores/' + this.get('storeId') + '/invoices/create?fromOrder=' + this.id;
        }
    });
});