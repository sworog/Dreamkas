define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_company.ejs')
        },
        collections: {
            organizations: require('collections/organizations/organizations')
        }
    });
});