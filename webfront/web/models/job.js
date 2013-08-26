define(function(require) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        modelName: 'job',
        urlRoot: LH.baseApiUrl + '/jobs'
    });
});