define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber'),
        _ = require('lodash');

    return Model.extend({
        defaults: {
            storeId: function(){
                return PAGE.params.storeId;
            }
        },
        urlRoot: function(){
            return Model.baseApiUrl + '/stores/' + this.get('storeId') + '/returns';
        },
        collections: {
            products: require('resources/refundProduct/collection')
        },
        models: {
            sale: function(){
                return PAGE.collections.receipts.get(PAGE.params.receiptId);
            }
        },
        initialize: function(){

            var RefundProductsCollection = require('resources/refundProduct/collection');

            this.collections.products = new RefundProductsCollection(this.models.sale.collections.products.map(function(receiptProductModel){
                return receiptProductModel.pick('product', 'price')
            }));
        },
        saveData: function(){

            var products = this.collections.products.map(function(refundProductModel) {
                var data = refundProductModel.getData();

                if (_.isNaN(data.quantity)){
                    data.quantity = 'x';
                }

                return data;
            });

            products = _.filter(products, function(product){
                return product.quantity !== 0;
            });

            return {
                products: products,
                sale: this.models.sale.id,
                date: new Date
            };
        }
    });
});