define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        receiptProductCid: null,
        id: 'form_receiptProduct',
        data: function(){
            var block = this;

            return _.extend(block.model.toJSON(), {
                sellingPrice: block.model.get('sellingPrice') ? block.formatMoney(block.model.get('sellingPrice')) : '',
                count: block.formatAmount(block.model.get('count'))
            });
        },
        model: function(){
            var ReceiptProductModel = require('models/receiptProduct/receiptProduct');

            return this.receiptProductCid ? PAGE.models.receipt.collections.products.get(this.receiptProductCid) : new ReceiptProductModel;
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
        }
    });
});