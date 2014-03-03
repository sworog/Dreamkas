define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        __name__: module.id,
        urlRoot: LH.baseApiUrl + '/suppliers',
        name: null,
        saveData: [
            'name',
            'agreement'
        ]
    });
});