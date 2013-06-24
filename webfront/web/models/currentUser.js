define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    var CurrentUserModel = BaseModel.extend({
        urlRoot: LH.baseApiUrl + '/currentUser'
    });

    return new CurrentUserModel();
});