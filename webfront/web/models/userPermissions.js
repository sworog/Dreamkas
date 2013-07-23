define(function(require) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        modelName: 'userPermissions',
        url: LH.baseApiUrl + '/users/permissions'
    });
});