define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model');

    return Model.extend({
        urlRoot: function(){
            return Model.baseApiUrl + '/stores/' + this.get('storeId') + '/sales/' + this.get('receiptId') + '/refund';
        },
        collections: {
            refundProducts: require('collections/refundProducts/refundProducts')
        },
        models: {
            sale: function(){
                var ReceiptModel = require('models/receipt/receipt');

                return new ReceiptModel;
            }
        },
        saveData: function(){

            return {
                products: this.collections.refundProducts.map(function(refundProductModel) {
                    return refundProductModel.getData();
                }),
                sale: this.models.sale.id,
                date: new Date
            };
        }
    });
});