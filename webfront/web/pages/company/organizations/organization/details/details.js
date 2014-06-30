define(function(require, exports, module) {
    //requirements
    var Page = require('pages/companyOrganization');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        blocks: {
            form_organizationDetails: function(){
                var page = this,
                    Form_companyOrganizationDetails = require('blocks/form/form_organizationDetails/form_organizationDetails');

                return new Form_companyOrganizationDetails({
                    model: page.models.companyOrganization
                });
            }
        }
    });
});