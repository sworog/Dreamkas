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
        saveData: function(){

            return {
                products: this.collections.refundProducts.map(function(refundProductModel) {
                    return refundProductModel.getData();
                }),
                date: new Date
            };
        }
    });
});