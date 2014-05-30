define(function(require) {
    //requirements
    var config = require('config'),
        Model = require('kit/core/model');

    var CurrentUserModel = Model.extend({
        url: config.baseApiUrl + '/users/current'
    });

    return new CurrentUserModel();
});