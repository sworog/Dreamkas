define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        formatMoney = require('kit/formatMoney/formatMoney'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Form.extend({
        model: function(){
            return PAGE.models.receipt;
        },
        template: require('ejs!./template.ejs'),
        events: {
            'click .form_receipt__productLink': function(e){
                document.getElementById('modal_receiptProduct').block.show({
                    models: {
                        receiptProduct: PAGE.models.receipt.collections.products.get(e.currentTarget.dataset.receiptProductCid)
                    }
                });
            },
            'click .form_receipt__clearLink .confirmLink__confirmation': function(e){
                var block = this;

                block.model.clear();
            }
        },
        initialize: function(){
            var block = this;
            
            Form.prototype.initialize.apply(block, arguments);

            block.listenTo(block.model.collections.products, {
                'add remove reset change': function(){
                    block.render();
                    block.$('.form_receipt__scrollContainer').scrollTop(block.$('.form_receipt__scrollContainer table').height());
                }
            });
        },
        calculateItemPrice: function(receiptProductModel){
            return formatMoney(normalizeNumber(receiptProductModel.get('quantity')) * normalizeNumber(receiptProductModel.get('price')));
        },
        calculateTotalPrice: function(){
            var block = this,
                totalPrice = 0;

            block.model.collections.products.forEach(function(receiptProductModel){
                totalPrice += normalizeNumber(block.calculateItemPrice(receiptProductModel));
            });

            return formatMoney(totalPrice);
        }
    });
});