define(function(require) {
    //requirements
    var Model = require('kit/model');

    var CurrentUserModel = Model.extend({
        modelName: 'currentUser',
        url: LH.baseApiUrl + '/users/current'
    });

    return new CurrentUserModel();
});