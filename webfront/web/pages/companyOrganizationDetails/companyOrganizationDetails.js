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
            certificateDateInput: function(){
                var InputDate = require('blocks/inputDate/inputDate');

                return new InputDate({
                    el: '[name="certificateDate"]'
                });
            }
        }
    });
});