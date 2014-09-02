define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model');

    return Model.extend({
        collections: {
            receiptProducts: require('collections/receiptProducts/receiptProducts')
        },
        saveData: function(){

            return {
                products: this.collections.receiptProducts.map(function(receiptProductModel) {
                    return receiptProductModel.getData();
                })
            }
        }
    });
});