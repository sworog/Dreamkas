define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    var CurrentUserModel = BaseModel.extend({
        urlRoot: LH.baseApiUrl + 'users/current'
    });

    return new CurrentUserModel();
});