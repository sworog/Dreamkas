define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        __name__: module.id,
        url: function() {
            return LH.baseApiUrl + '/stores/' + (this.collection.storeId || this.get('storeId')) + '/orders/products?validate=1'
        },
        defaults: {
            storeId: null,
            orderId: null,
            product: null,
            quantity: null
        },
        saveData: function() {
            return {
                product: this.get('product.product.id'),
                quantity: this.get('quantity')
                    .replace(' ', '', 'gi')
                    .replace(',', '.', 'gi')
            };
        }
    });
});