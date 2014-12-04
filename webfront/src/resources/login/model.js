define(function(require) {
    //requirements
    var Model = require('kit/model/model');

    return Model.extend({
        urlRoot: CONFIG.baseUrl + '/oauth/v2/token',
        defaults: {
            client_id: CONFIG.clientId,
            client_secret: CONFIG.clientSecret,
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