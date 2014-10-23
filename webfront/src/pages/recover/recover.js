define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        blocks: {
            form_recover: require('blocks/form/pass/recover/recover')
        }
    });
});