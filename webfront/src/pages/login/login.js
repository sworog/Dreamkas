define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        blocks: {
            form_login: require('blocks/form/login/login')
        }
    });
});