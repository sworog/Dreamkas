define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page.deprecated');

    return Page.extend({
        content: require('ejs!./content.ejs')
    });
});