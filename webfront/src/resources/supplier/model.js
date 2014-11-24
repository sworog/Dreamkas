define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model');

    require('./mocks/delete');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/suppliers',
        saveData: [
            'name',
            'address',
            'phone',
            'email',
            'contactPerson'
        ]
    });
});