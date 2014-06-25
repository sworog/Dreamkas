define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        params: {
            organizationId: null
        },
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_companyOrganization.ejs')
        },
        models: {
            companyOrganization: function(){
                var page = this,
                    Model = require('models/companyOrganization/companyOrganization');

                return new Model({
                    id: page.params.organizationId
                });
            }
        },
        blocks: {
            form_companyOrganization: function(){
                var page = this,
                    Form = require('blocks/form/form_companyOrganization/form_companyOrganization');

                return new Form({
                    model: page.models.companyOrganization
                });
            }
        }
    });
});