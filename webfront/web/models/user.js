define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    return BaseModel.extend({
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