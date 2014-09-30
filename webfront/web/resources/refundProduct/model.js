define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Model.extend({
        defaults: {
            quantity: 0
        },
        models: {
            product: require('resources/product/model')
        },
        saveData: function() {

            return {
                product: this.models.product.id,
                quantity: normalizeNumber(this.get('quantity'))
            };
        }
    });
});