define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page'),
        $ = require('jquery');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            localNavigation: require('rv!blocks/localNavigation/localNavigation_reports.html')
        },
        resources: {
            grossMargin: require('collections/grossMargin')
        }
    });
});