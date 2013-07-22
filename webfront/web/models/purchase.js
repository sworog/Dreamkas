define(function(require) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        modelName: 'purchase',
        urlRoot: LH.baseApiUrl + '/purchases',
        defaults: {
            products: []
        },
        saveFields: [
            'products'
        ]
    });
});
