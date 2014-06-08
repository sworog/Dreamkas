define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('tpl!./content.ejs'),
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation_signup.ejs')
        },
        blocks: {
            form_signup: require('blocks/form/form_signup/form_signup')
        }
    });
});