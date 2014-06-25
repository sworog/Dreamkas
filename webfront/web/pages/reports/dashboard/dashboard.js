define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('ejs!./content.html'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_reports.ejs')
        }
    });
});