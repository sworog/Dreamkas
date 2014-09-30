define(function(require) {
    //requirements
    var Form = require('blocks/form/form.deprecated'),
        login = require('kit/login/login'),
        getText = require('kit/getText/getText');

    return Form.extend({
        el: '.form_login',
        model: function() {
            var LoginModel = require('resources/login/model');

            return new LoginModel();
        },
        submitSuccess: function() {
            login(this.model.get('access_token'));
        },
        showErrors: function(error) {
            var block = this,
                form__errorMessage = block.el.querySelector('.form__errorMessage_global');

            form__errorMessage.innerHTML = getText(error.error);
            form__errorMessage.classList.add('form__errorMessage_visible');
        }
    });
});