define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model');

    return Model.extend({
        urlRoot: Model.baseApiUrl + '/suppliers',
        saveData: [
            'name',
            'agreement'
        ]
    });
});