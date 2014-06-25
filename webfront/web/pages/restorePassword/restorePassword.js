define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            globalNavigation: require('ejs!blocks/globalNavigation/globalNavigation_login.ejs')
        },
        blocks: {
            form_restorePassword: require('blocks/form/form_restorePassword/form_restorePassword')
        }
    });
});