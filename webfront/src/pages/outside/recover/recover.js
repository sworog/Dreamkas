define(function(require, exports, module) {
    //requirements
    var Page_outside = require('pages/outside/outside');

    return Page_outside.extend({
        formBlock: 'form_recover',
        blocks: {
            form_recover: require('blocks/form/pass/recover/recover')
        }
    });
});