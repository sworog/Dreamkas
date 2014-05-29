define(function(require) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        urlRoot: LH.baseUrl + '/oauth/v2/token',
        defaults: {
            client_id: LH.clientId,
            client_secret: LH.clientSecret,
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