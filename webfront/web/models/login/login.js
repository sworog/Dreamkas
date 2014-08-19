define(function(require) {
    //requirements
    var config = require('config'),
        Model = require('kit/model/model');

    return Model.extend({
        urlRoot: config.baseUrl + '/oauth/v2/token',
        defaults: {
            client_id: config.clientId,
            client_secret: config.clientSecret,
            grant_type: 'password'
        },
        saveData: [
            'client_id',
            'client_secret',
            'grant_type',
            'username',
            'password'
        ]
    });
});