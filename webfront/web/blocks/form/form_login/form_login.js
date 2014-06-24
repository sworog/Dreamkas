define(function(require) {
    //requirements
    var Form = require('kit/form/form'),
        login = require('kit/login/login');

    return Form.extend({
        el: '.form_login',
        nls: require('i18n!./nls/main'),
        model: function(){
            var LoginModel = require('models/login');

            return new LoginModel();
        },
        initialize: function(){

            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            if (block.model.email){
                block.el.querySelector('[name="password"]').focus();
            } else {
                block.el.querySelector('[name="username"]').focus();
            }
        },
        submitSuccess: function(response) {
            login(response.access_token);
        }
    });
});