define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        showDeletedMessage: false,
        models: {
            receiptProduct: require('resources/receiptProduct/model')
        },
        blocks: {
            form_receiptProduct: function(){
                var block = this,
                    Form_receiptProduct = require('blocks/form/receiptProduct/receiptProduct');

                return new Form_receiptProduct({
                    model: block.models.receiptProduct
                });
            }
        }
    });
});