define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page'),
        SignupModel = require('models/signup');

    return Page.extend({
        partials: {
            content: require('tpl!./content.ejs'),
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation_login.ejs')
        },
        components: {
            'form-login': require('blocks/form/form_login/form_login')
        }
    });
});