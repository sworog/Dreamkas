define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model');

    return Model.extend({
        collections: {
            products: require('collections/receiptProducts/receiptProducts')
        },
        saveData: function(){

            return {
                products: this.collections.products.map(function(receiptProductModel) {
                    return receiptProductModel.getData();
                })
            }
        }
    });
});