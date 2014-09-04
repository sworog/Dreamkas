define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        receiptProductCid: null,
        blocks: {
            form_receiptProduct: require('blocks/form/receiptProduct/receiptProduct')
        }
    });
});