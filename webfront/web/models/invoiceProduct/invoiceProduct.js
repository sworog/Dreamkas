define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Model.extend({
        defaults: {
            product: null,
            priceEntered: null,
            quantity: 1
        },
        saveData: function() {

            return {
                product: this.get('product.id'),
                priceEntered: this.get('priceEntered') ? normalizeNumber(this.get('priceEntered')) : '',
                quantity: this.get('quantity') ? normalizeNumber(this.get('quantity')) : ''
            };
        }
    });
});