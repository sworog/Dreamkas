define(function(require) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        saveData: [
            'name',
            'email',
            'password'
        ],
        urlRoot: Model.baseApiUrl + '/users'
    });
});