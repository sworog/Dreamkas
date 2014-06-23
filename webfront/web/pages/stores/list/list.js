define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        collections: {
            stores: require('collections/stores')
        },
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_stores.ejs')
        }
    });
});