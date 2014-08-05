define(function(require) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        modelName: 'job',
        urlRoot: LH.baseApiUrl + '/jobs'
    });
});