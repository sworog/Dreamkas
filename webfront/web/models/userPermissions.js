define(function(require) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        modelName: 'userPermissions',
        url: LH.baseApiUrl + '/users/permissions'
    });
});