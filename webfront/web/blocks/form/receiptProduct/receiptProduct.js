define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber'),
        checkKey = require('kit/checkKey/checkKey');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_receiptProduct',
        data: function(){
            var block = this;

            return _.extend(block.model.toJSON(), {
                sellingPrice: block.model.get('sellingPrice') ? block.formatMoney(block.model.get('sellingPrice')) : '',
                count: block.formatAmount(block.model.get('count'))
            });
        },
        model: require('models/receiptProduct/receiptProduct'),
        events: {
            'click .form_receiptProduct__countPlusLink': function(e){
                e.preventDefault();

                var block = this;

                block.changeCount(1);
            },
            'click .form_receiptProduct__countMinusLink': function(e){
                e.preventDefault();

                var block = this;

                block.changeCount(-1);
            },
            'keyup [name="count"]': function(e){
                e.stopPropagation();

                var block = this;

                if (checkKey(e.keyCode, ['UP'])){
                    block.changeCount(1);
                }

                if (checkKey(e.keyCode, ['DOWN'])){
                    block.changeCount(-1);
                }
            }
        },
        collection: function(){
            return PAGE.models.receipt.collections.products;
        },
        submit: function(){
            var block = this;

            return block.model.validate(block.data).then(function(){
                block.model.set(block.data);
            });
        },
        calculateItemPrice: function(){
            var block = this;

            return block.formatMoney(normalizeNumber(block.data.count) * normalizeNumber(block.data.sellingPrice));
        },
        changeCount: function(delta){
            var block = this;

            block.data.count = block.formatMoney(normalizeNumber(block.data.count) + delta);
        }
    });
});