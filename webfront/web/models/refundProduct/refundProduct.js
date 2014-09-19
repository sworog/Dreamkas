define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Model.extend({
        defaults: {
            quantity: 1
        },
        models: {
            receiptProduct: require('models/receiptProduct/receiptProduct')
        },
        saveData: function() {

            return {
                product: this.models.receiptProduct.models.product.id,
                quantity: this.get('quantity')
            };
        }
    });
});