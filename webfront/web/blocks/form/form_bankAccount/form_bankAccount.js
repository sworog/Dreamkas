define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        el: '.form_bankAccount',
        organizationId: null,
        supplierId: null,
        redirectUrl: function() {
            var block = this,
                url;

            if (block.organizationId){
                url = '/company/organizations/' + block.organizationId + '/bankAccounts';
            }

            if (block.supplierId){
                url = '/suppliers/' + block.supplierId + '/bankAccounts';
            }

            return url;
        },
        model: function() {
            var block = this,
                Model = require('models/bankAccount/bankAccount');

            return new Model({
                organizationId: block.organizationId,
                supplierId: block.supplierId
            });
        }
    });
});