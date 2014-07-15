define(function(require) {
    //requirements
    var Form = require('kit/form/form'),
        login = require('kit/login/login'),
        getText = require('kit/getText/getText');

    return Form.extend({
        el: '.form_login',
        model: function() {
            var LoginModel = require('models/login');

            return new LoginModel({
                email: LoginModel.email
            });
        },
        initialize: function() {

            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            if (block.model.email) {
                block.el.querySelector('[name="password"]').focus();
            } else {
                block.el.querySelector('[name="username"]').focus();
            }
        },
        submitSuccess: function() {
            login(this.model.get('access_token'));
        },
        showErrors: function(error) {
            var block = this,
                form__errorMessage = block.el.querySelector('.form__errorMessage');

            form__errorMessage.innerHTML = getText(error.error);
            form__errorMessage.classList.add('form__errorMessage_visible');
        }
    });
});