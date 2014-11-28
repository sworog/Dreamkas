define(function(require, exports, module) {
    //requirements
    var Page_auth = require('blocks/page/auth/auth');

    return Page_auth.extend({
        blocks: {
            form: require('blocks/form/pass/signup/signup')
        }
    });
});