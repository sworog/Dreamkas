define(function(require) {
    //requirements
    var Form = require('kit/form'),
        login = require('kit/login/login');

    return Form.extend({
        template: require('rv!./template.html'),
        data: {
            client_id: LH.clientId,
            client_secret: LH.clientSecret,
            grant_type: 'password',
            email: null,
            password: null
        },
        submit: function(){
            var block = this;

            return $.ajax({
                type: 'POST',
                data: block.data,
                url: LH.baseUrl + '/oauth/v2/token'
            });
        },
        submitSuccess: function(response) {
            login(response.access_token);
        }
    });
});