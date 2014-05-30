define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        Model = require('kit/core/model');

    var Signup = Model.extend({
        url: config.baseApiUrl + '/users/signup',
        saveData: [
            'email'
        ]
    });

    return new Signup;
});
