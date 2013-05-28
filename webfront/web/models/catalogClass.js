define(
    [
        './baseModel.js'
    ],
    function(BaseModel) {
        return BaseModel.extend({
            modelName: 'invoice',
            urlRoot: baseApiUrl + '/klasses',

            saveFields: [
                'name',
                'description'
            ]
        });
    }
);