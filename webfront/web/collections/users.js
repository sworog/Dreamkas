define(function(require) {
    //requirements
    var BaseCollection = require('collections/baseCollection'),
        UserModel = require('models/user');

    return BaseCollection.extend({
        model: UserModel,
        url: LH.baseApiUrl + "/users"
    });
});