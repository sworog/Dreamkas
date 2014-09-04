define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        receiptProductCid: null,
        id: 'form_receiptProduct',
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
        }
    });
});