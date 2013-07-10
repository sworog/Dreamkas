define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    return BaseModel.extend({
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
