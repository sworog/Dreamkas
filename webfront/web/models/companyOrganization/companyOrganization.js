define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/organizations',
        saveData: [
            'name',
            'phone',
            'fax',
            'email',
            'director',
            'chiefAccountant',
            'address'
        ]
    });
});