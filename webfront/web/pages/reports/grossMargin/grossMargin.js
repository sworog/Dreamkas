define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_reports.ejs')
        },
        collections: {
            grossMargin: require('collections/grossMargin')
        }
    });
});