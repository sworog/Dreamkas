define(function(require, exports, module) {
    //requirements
    var Page = require('pages/stores/store');

    return Page.extend({
        partials: {
            content: require('rv!./content.html')
        }
    });
});