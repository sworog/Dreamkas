define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        tokenModel = require('models/token'),
        login = require('utils/login');

    return Form.extend({
        __name__: 'form_login',
        model: tokenModel,
        submitSuccess: function(response) {
            login(this.model.get('access_token'));
        }
    });
});