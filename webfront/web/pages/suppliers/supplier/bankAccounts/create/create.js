define(function(require, exports, module) {
    //requirements
    var Page = require('pages/supplier');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        blocks: {
            form_bankAccount: function(){
                var page = this,
                    Form = require('blocks/form/form_bankAccount/form_bankAccount');

                return new Form({
                    supplierId: page.params.supplierId
                });
            }
        }
    });
});