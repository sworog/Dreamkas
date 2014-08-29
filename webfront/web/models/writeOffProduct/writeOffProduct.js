define(function(require) {
    //requirements
    var Model = require('kit/model/model'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Model.extend({
        defaults: {
            product: null,
            price: null,
            quantity: 1,
            cause: null
        },
        saveData: function() {
            return {
                product: this.get('product.id'),
                price: this.get('price') ? normalizeNumber(this.get('price')) : '',
                quantity: this.get('quantity') ? normalizeNumber(this.get('quantity')) : '',
                cause: this.get('cause')
            };
        }
    });
});