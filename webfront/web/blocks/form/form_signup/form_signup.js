define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form.deprecated'),
        LoginModel = require('resources/login/model');

    return Form.extend({
        el: '.form_signup',
        model: function(){
            var SignUpModel = require('resources/signup/model');

            return new SignUpModel();
        },
        redirectUrl: '/login?signup=success',
        submitSuccess: function(res){
            var block = this;

            LoginModel.email = block.model.get('email');

            Form.prototype.submitSuccess.apply(block, arguments);
        }
    });
});