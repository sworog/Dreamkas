define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        blocks: {
            form_refund: require('blocks/form/refund/refund')
        }
    });
});