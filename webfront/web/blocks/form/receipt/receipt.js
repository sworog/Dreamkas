define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_receipt',
        model: function() {
            return PAGE.models.receipt
        },
        events: {
            'change input[name="payment.type"]': function(e) {
                var block = this,
                    $form_receipt__cashInput = block.$('.form_receipt__cashInput'),
                    $form_receipt__change = block.$('.form_receipt__change');

                if (e.target.value === 'cash') {
                    $form_receipt__cashInput.show();
                    block.checkChange();
                    block.el.querySelector('input[name="payment.amountTendered"]').focus();

                } else {
                    $form_receipt__cashInput.hide();
                    $form_receipt__change.hide();
                    block.enable();
                }

            },
            'keyup input[name="payment.amountTendered"]': function() {
                var block = this;

                block.checkChange();
            }
        },
        submitSuccess: function(){
            document.getElementById('modal_receipt').block.show({
                success: true
            });
        },
        checkChange: function(){
            var block = this,
                $form_receipt__change = block.$('.form_receipt__change');

            if (!block.data.change || block.normalizeNumber(block.data.change) < 0) {
                $form_receipt__change.hide();
                block.disable();
            } else {
                $form_receipt__change.show();
                block.enable();
            }
        },
        calculateTotalPrice: function() {
            var block = this,
                totalPrice = 0;

            block.model.collections.receiptProducts.forEach(function(receiptProductModel) {
                totalPrice += block.normalizeNumber(receiptProductModel.get('quantity')) * block.normalizeNumber(receiptProductModel.get('price'));
            });

            return block.formatMoney(totalPrice);
        },
        calculateChange: function() {
            var block = this,
                totalPrice = block.calculateTotalPrice(),
                change = block.normalizeNumber(block.data.payment.amountTendered) - block.normalizeNumber(totalPrice);

            block.data.change = _.isNaN(change) ? null : block.formatMoney(change);

            return block.data.change;
        }
    });
});