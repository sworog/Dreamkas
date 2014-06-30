define(function(require, exports, module) {
    //requirements
    var Page = require('pages/companyOrganization');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        models: {
            bankAccount: function(){
                var page = this,
                    Model = require('models/bankAccount/bankAccount');

                return new Model({
                    organizationId: page.params.organizationId
                });
            }
        },
        blocks: {
            form_bankAccount: function(){
                var page = this,
                    Form = require('blocks/form/form_bankAccount/form_bankAccount');

                return new Form({
                    organizationId: page.params.organizationId
                });
            }
        }
    });
});