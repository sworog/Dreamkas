define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs')
    });
});