define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model'),
        _ = require('lodash');

    return Model.extend({
        defaults: {
            storeId: function(){
                return PAGE.params.storeId;
            }
        },
        urlRoot: function(){
            return Model.baseApiUrl + '/stores/' + this.get('storeId') + '/return';
        },
        collections: {
            products: require('collections/refundProducts/refundProducts')
        },
        models: {
            sale: function(){
                return PAGE.collections.receipts.get(PAGE.params.receiptId);
            }
        },
        initialize: function(){

            var RefundProductsCollection = require('collections/refundProducts/refundProducts');

            this.collections.products = new RefundProductsCollection(this.models.sale.collections.products.map(function(receiptProductModel){
                return receiptProductModel.pick('product', 'price')
            }));
        },
        saveData: function(){

            return {
                products: this.collections.products.map(function(refundProductModel) {
                    return refundProductModel.getData();
                }),
                sale: this.models.sale.id,
                date: new Date
            };
        }
    });
});