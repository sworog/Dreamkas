define(function(require) {
    //requirements
    var Model = require('kit/model');

    var UserPermissionsModel = Model.extend({
        modelName: 'userPermissions',
        url: LH.baseApiUrl + '/users/permissions'
    });

    return new UserPermissionsModel();
});