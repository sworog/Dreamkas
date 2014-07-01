define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/suppliers',
        saveData: [
            'name',
            'phone',
            'fax',
            'email',
            'contactPerson',
            'agreement'
        ]
    });
});