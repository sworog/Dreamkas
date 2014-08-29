define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page.deprecated');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        blocks: {
            form_login: require('blocks/form/login/login')
        }
    });
});