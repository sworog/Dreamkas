define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model'),
        currentUserModel = require('models/currentUser.inst');

    return Model.extend({
        __name__: module.id,
        url: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/orders/products?validate=1'
        },
        defaults: {
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