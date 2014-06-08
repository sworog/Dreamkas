define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page'),
        deepExtend = require('kit/deepExtend/deepExtend');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            localNavigation: require('rv!blocks/localNavigation/localNavigation_catalog.html')
        },
        data: {
            params: {
                edit: 0
            }
        }
    });
});