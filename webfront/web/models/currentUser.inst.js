define(function(require) {
    //requirements
    var Model = require('kit/model');

    var CurrentUserModel = Model.extend({
        url: Model.baseApiUrl + '/users/current'
    });

    return new CurrentUserModel();
});