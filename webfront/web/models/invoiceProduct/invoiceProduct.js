define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Model.extend({
        urlRoot: function() {
            return Model.baseApiUrl + '/stores/' + 'storeId' + '/invoices/products?validate=true';
        },
        defaults: {
            product: null,
            priceEntered: null,
            quantity: 1
        },
        saveData: function() {

            return {
                product: this.get('product.id'),
                priceEntered: normalizeNumber(this.get('priceEntered')),
                quantity: normalizeNumber(this.get('quantity'))
            };
        }
    });
});