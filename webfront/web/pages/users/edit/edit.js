define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_users.ejs')
        },
        blocks: {
            form_user: require('blocks/form/form_user/form_user')
        }
    });
});
