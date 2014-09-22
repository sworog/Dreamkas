define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_refund',
        model: require('models/refund/refund'),
        blocks: {
            inputNumber: require('blocks/inputNumber/inputNumber')
        },
        submitSuccess: function(){
            document.getElementById('modal_refund').block.show({
                success: true
            });
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