define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        el: '.form_bankAccount',
        organizationId: null,
        redirectUrl: function() {
            var block = this;

            return '/company/organizations/' + block.organizationId + '/bankAccounts';
        },
        model: function() {
            var block = this,
                Model = require('models/bankAccount/bankAccount');

            return new Model({
                organizationId: block.organizationId
            });
        }
    });
});