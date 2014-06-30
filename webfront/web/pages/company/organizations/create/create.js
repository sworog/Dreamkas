define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_company.ejs')
        },
        blocks: {
            form_companyOrganization: require('blocks/form/form_companyOrganization/form_companyOrganization')
        }
    });
});