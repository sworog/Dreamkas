define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        dialog: 'receipt',
        events: {
            'click .modal_receipt__reloadLink': function() {
                var block = this;

                block.hide();

                block.models.receipt.clear();

                PAGE.render();
            }
        },
        models: {
            receipt: function() {
                return PAGE.models.receipt
            }
        },
        blocks: {
            form_receipt: require('blocks/form/receipt/receipt')
        }
    });
});