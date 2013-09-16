define(function(require) {
    //requirements
    var Model = require('kit/core/model');

    return Model.extend({
        modelName: 'purchase',
        urlRoot: LH.baseApiUrl + '/purchases',
        defaults: {
            products: []
        },
        saveData: [
            'products'
        ]
    });
});
