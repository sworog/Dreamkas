define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: function(){
            return PAGE.models.receipt
        },
        calculateTotalPrice: function() {
            var block = this,
                totalPrice = 0;

            block.model.collections.products.forEach(function(receiptProductModel) {
                totalPrice += block.normalizeNumber(receiptProductModel.get('quantity')) * block.normalizeNumber(receiptProductModel.get('price'));
            });

            return block.formatMoney(totalPrice);
        }
    });
});