define(function(require, exports, module) {
    //requirements
    var Page = require('pages/storeProduct');

    return Page.extend({
        partials: {
            content: require('tpl!./content.ejs')
        }
    });
});