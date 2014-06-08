define(function(require) {
    //requirements
    var Form = require('kit/form'),
        login = require('kit/login/login');

    return Form.extend({
        nls: require('i18n!./nls/main'),
        initialize: function(){

            Form.prototype.apply(this, arguments);

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