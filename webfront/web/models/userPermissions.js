define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    var UserPermissionsModel = BaseModel.extend({
        url: LH.baseApiUrl + '/users/permissions'
    });

    return new UserPermissionsModel();
});