define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            globalNavigation: require('ejs!blocks/globalNavigation/globalNavigation_login.ejs')
        },
        blocks: {
            form_login: require('blocks/form/form_login/form_login')
        }
    });
});