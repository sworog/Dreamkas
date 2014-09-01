define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page.deprecated');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        blocks: {
            form_signup: require('blocks/form/form_signup/form_signup')
        }
    });
});