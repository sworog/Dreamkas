define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form'),
        LoginModel = require('models/login');

    return Form.extend({
        el: '.form_restorePassword',
        model: function(){
            var RestorePasswordModel = require('models/restorePassword');

            return new RestorePasswordModel();
        },
        redirectUrl: '/login?restorePassword=success',
        submitSuccess: function(res){
            var block = this;

            LoginModel.email = res.email;

            Form.prototype.submitSuccess.apply(block, arguments);
        }
    });
});