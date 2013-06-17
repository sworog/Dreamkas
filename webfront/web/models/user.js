define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    return BaseModel.extend({
        saveFields: [
            'name',
            'position',
            'login',
            'password',
            'role'
        ],
        urlRoot: LH.baseApiUrl + '/users'
    });
});