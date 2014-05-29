define(function(require) {
    //requirements
    var Form = require('kit/form'),
        TokenModel = require('models/token'),
        login = require('kit/login/login');

    return Form.extend({
        template: require('rv!./template.html'),
        data: {
            client_id: LH.clientId,
            client_secret: LH.clientSecret,
            grant_type: 'password',
            username: null,
            password: null
        },
        submit: function(){
            var block = this,
                model = new TokenModel;

            return model.save(block.data);
        },
        submitSuccess: function(response) {
            login(response.access_token);
        }
    });
});