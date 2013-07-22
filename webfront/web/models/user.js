define(function(require) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        modelName: 'user',
        saveFields: [
            'name',
            'position',
            'username',
            'password',
            'role'
        ],
        urlRoot: LH.baseApiUrl + '/users'
    });
});