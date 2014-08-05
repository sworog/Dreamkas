define(function(require) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        modelName: 'log',
        urlRoot: LH.baseApiUrl + '/logs'
    });
});