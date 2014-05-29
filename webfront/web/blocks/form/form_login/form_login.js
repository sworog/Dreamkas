define(function(require) {
    //requirements
    var Form = require('kit/form'),
        login = require('kit/login/login');

    return Form.extend({
        template: require('rv!./template.html'),
        model: require('models/login'),
        nls: require('i18n!./nls/main'),
        data: {
            model: {
                username: null,
                password: null
            }
        },
        complete: function(){
            if (this.get('model.username')){
                this.el.querySelector('[name="password"]').focus();
            } else {
                this.el.querySelector('[name="username"]').focus();
            }
        },
        submitSuccess: function(response) {
            login(response.access_token);
        }
    });
});