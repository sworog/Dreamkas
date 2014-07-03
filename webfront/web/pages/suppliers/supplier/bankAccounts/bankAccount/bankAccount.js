define(function(require, exports, module) {
    //requirements
    var Page = require('pages/supplier');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        params: {
            bankAccountId: null
        },
        models: {
            bankAccount: function(){
                var page = this,
                    Model = require('models/bankAccount/bankAccount');

                return new Model({
                    id: page.params.bankAccountId,
                    supplierId: page.params.supplierId
                });
            }
        },
        blocks: {
            form_bankAccount: function(){
                var page = this,
                    Form = require('blocks/form/form_bankAccount/form_bankAccount');

                return new Form({
                    model: page.models.bankAccount,
                    supplierId: page.params.supplierId
                });
            }
        }
    });
});