define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        currentUserModel = require('models/currentUser');

    return Model.extend({
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/invoices/products?validate=true';
        },
        defaults: {
            product: null,
            priceEntered: null,
            quantity: 1
        },
        saveData: function() {
            return {
                product: this.get('product.product.id'),
                priceEntered: this.get('priceEntered').toString()
                    .replace(' ', '', 'gi')
                    .replace(',', '.', 'gi'),
                quantity: this.get('quantity').toString()
                    .replace(' ', '', 'gi')
                    .replace(',', '.', 'gi')
            };
        }
    });
});