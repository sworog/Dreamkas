define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        success: false,
        events: {
            'click .modal_receipt__reloadLink': function() {
                var block = this,
                    ReceiptModel = require('models/receipt/receipt');

                block.hide();

                PAGE.models.receipt = new ReceiptModel();

                PAGE.render();
            }
        },
        blocks: {
            form_receipt: require('blocks/form/receipt/receipt')
        }
    });
});