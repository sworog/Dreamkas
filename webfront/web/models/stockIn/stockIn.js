define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/stockIns',
        collections: {
            products: require('collections/stockInProducts/stockInProducts')
        },
        saveData: function() {
            return {
                date: this.get('date'),
                products: this.collections.products.map(function(productModel) {
                    return productModel.getData();
                }),
                store: this.get('store')
            }
        }
    });
});