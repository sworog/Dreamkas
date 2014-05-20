define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        tokenModel = require('models/token'),
        login = require('kit/login/login');

    return Form.extend({
        __name__: 'form_login',
        model: tokenModel,
        submitSuccess: function(response) {
            login(this.model.get('access_token'));
        },
        translate: function(text){
            return LH.getText(this.get('dictionary'), text);
        }
    });
});