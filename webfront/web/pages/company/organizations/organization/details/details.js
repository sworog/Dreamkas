define(function(require, exports, module) {
    //requirements
    var Page = require('pages/companyOrganization');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        blocks: {
            form_legalDetails: function(){
                var page = this,
                    Form_companyOrganizationDetails = require('blocks/form/form_legalDetails/form_legalDetails');

                return new Form_companyOrganizationDetails({
                    model: page.models.companyOrganization
                });
            }
        }
    });
});