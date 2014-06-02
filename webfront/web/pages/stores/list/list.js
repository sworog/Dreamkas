define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        resources: {
            stores: require('collections/stores')
        },
        partials: {
            content: require('rv!./content.html')
        }
    });
});