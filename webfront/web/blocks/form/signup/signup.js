define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        LoginModel = require('models/login/login');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: require('models/signup/signup'),
        redirectUrl: '/login?signup=success',
        submitSuccess: function(res){
            var block = this;

            LoginModel.email = block.model.get('email');

            Form.prototype.submitSuccess.apply(block, arguments);
        }
    });
});