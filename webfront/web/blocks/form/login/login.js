define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        login = require('kit/login/login'),
        getText = require('kit/getText/getText');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        LoginModel: require('models/login/login'),
        model: require('models/login/login'),
        submitSuccess: function() {
            var block = this;

            login(block.model.get('access_token'));
        },
        showErrors: function(error) {
            var block = this,
                form__errorMessage = block.el.querySelector('.form__errorMessage_global');

            form__errorMessage.innerHTML = getText(error.error);
            form__errorMessage.classList.add('form__errorMessage_visible');
        }
    });
});