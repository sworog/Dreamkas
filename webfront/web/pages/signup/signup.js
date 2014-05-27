define(function(require, exports, module) {
    //requirements
    var Page = require('pages/page');

    return Page.extend({
        partials: {
            content: require('rv!./content.html')
        }
    });
});