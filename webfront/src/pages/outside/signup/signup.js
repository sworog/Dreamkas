define(function(require, exports, module) {
    //requirements
    var Page_outside = require('pages/outside/outside');

    return Page_outside.extend({
        formBlock: 'form_signup',
        blocks: {
            form_signup: require('blocks/form/pass/signup/signup')
        }
    });
});