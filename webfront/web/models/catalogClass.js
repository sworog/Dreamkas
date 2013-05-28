define(
    [
        './baseModel.js'
    ],
    function(BaseModel) {
        return BaseModel.extend({
            modelName: 'klass',
            urlRoot: baseApiUrl + '/klasses',

            saveFields: [
                'name'
            ]
        });
    }
);