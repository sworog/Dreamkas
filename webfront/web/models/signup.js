define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        Model = require('kit/core/model');

    return Model.extend({
        url: config.baseApiUrl + '/users/signup',
        saveData: [
            'email'
        ]
    });
});
