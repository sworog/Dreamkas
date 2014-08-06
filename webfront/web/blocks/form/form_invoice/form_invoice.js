define(function(require, exports, module) {
    //requirements
    var From = require('kit/form/form');

    return From.extend({
        el: '.form_invoice',
        blocks: {
            inputDate: require('blocks/inputDate/inputDate')
        }
    });
});