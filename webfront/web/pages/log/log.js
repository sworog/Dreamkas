define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        collections: {
            jobs: require('collections/jobs'),
            log: require('collections/log')
        }
    });
});