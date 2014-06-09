define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('tpl!./content.ejs'),
            globalNavigation: require('tpl!blocks/globalNavigation/globalNavigation_login.ejs')
        },
        blocks: {
            form_restorePassword: require('blocks/form/form_restorePassword/form_restorePassword')
        }
    });
});