define(function(require) {
    //requirements
    var Model = require('kit/model');

    var TokenModel = Model.extend({
        modelName: 'token',
        urlRoot: LH.baseUrl + '/oauth/v2/token',
        defaults: {
            client_id: LH.clientId,
            client_secret: LH.clientSecret,
            grant_type: 'password'
        },
        sync: Backbone.Model.prototype.sync,
        saveFields: [
            'client_id',
            'client_secret',
            'grant_type',
            'username',
            'password'
        ]
    });

    return new TokenModel();
});