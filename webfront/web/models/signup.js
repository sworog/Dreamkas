define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        url: LH.baseApiUrl + '/users/signup',
        saveData: [
            'email'
        ]
    });
});
