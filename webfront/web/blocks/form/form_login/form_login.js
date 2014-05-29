define(function(require) {
    //requirements
    var Form = require('kit/form'),
        login = require('kit/login/login');

    return Form.extend({
        template: require('rv!./template.html'),
        model: require('models/token'),
        nls: require('i18n!./nls/main'),
        data: {
            model: {
                username: null,
                password: null
            }
        },
        submitSuccess: function(response) {
            login(response.access_token);
        }
    });
});