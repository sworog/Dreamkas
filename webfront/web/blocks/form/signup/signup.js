define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        LoginModel = require('resources/login/model');

    return Form.extend({
        template: require('ejs!./template.ejs'),
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