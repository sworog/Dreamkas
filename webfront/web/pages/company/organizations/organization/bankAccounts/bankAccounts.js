define(function(require, exports, module) {
    //requirements
    var Page = require('pages/companyOrganization');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        collections: {
            bankAccounts: function() {
                var page = this,
                    Collection = require('collections/bankAccounts/bankAccounts');

                return new Collection([], {
                    organizationId: page.params.organizationId
                });
            }
        }
    });
});