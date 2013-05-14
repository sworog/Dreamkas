define(
    [
        './baseModel.js'
    ],
    function(BaseModel) {
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
