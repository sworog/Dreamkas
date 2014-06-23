define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_suppliers.ejs')
        },
        collections: {
            suppliers: require('collections/suppliers')
        }
    });
});