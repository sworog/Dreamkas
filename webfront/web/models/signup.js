define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        url: Model.baseApiUrl + '/users/signup',
        saveData: [
            'email'
        ]
    });
});
