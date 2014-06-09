define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        collections: {
            stores: require('collections/stores')
        },
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_stores.ejs')
        }
    });
});