define(function(require, exports, module) {
    //requirements
    var Page = require('pages/companyOrganization');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
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