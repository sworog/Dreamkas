define(function(require) {
    //requirements
    var Model = require('kit/model');

    var CurrentUserModel = Model.extend({
        url: Model.baseApiUrl + '/users/current',
        saveData: [
            'name',
            'email',
            'password'
        ]
    });

    return new CurrentUserModel();
});