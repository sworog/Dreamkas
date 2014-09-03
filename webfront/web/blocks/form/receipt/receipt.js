define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        formatMoney = require('kit/formatMoney/formatMoney');

    return Form.extend({
        model: require('models/receipt/receipt'),
        template: require('ejs!./template.ejs'),
        initialize: function(){
            var block = this;
            
            Form.prototype.initialize.apply(block, arguments);

            block.listenTo(block.model.collections.products, {
                'add remove reset': function(){
                    block.render();
                    block.$('.form_receipt__scrollContainer').scrollTop(block.$('.form_receipt__scrollContainer').height());
                }
            });
        },
        calculateItemPrice: function(receiptProductModel){
            var block = this;

            return formatMoney(receiptProductModel.get('count') * receiptProductModel.get('product.sellingPrice'));
        },
        calculateTotalPrice: function(){
            var block = this,
                totalPrice = 0;

            block.model.collections.products.forEach(function(receiptProductModel){
                totalPrice += receiptProductModel.get('count') * receiptProductModel.get('product.sellingPrice');
            });

            return formatMoney(totalPrice);
        }
    });
});