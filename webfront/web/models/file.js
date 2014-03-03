define(function(require, exports, module) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        modelName: 'file',
        urlRoot: LH.mockApiUrl + '/files',
        saveData: [
            'file'
        ]
    });

});