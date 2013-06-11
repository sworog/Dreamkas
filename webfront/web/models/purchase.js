define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    return BaseModel.extend({
            modelName: 'purchase',
            urlRoot: baseApiUrl + '/purchases',
            defaults: {
                products: []
            },
            saveFields: [
                'products'
            ]
        });
    });
