define(
    [
        './baseModel.js'
    ],
    function(BaseModel) {
        return BaseModel.extend({
            modelName: 'group',
            urlRoot: baseApiUrl + '/groups',

            saveFields: [
                'name',
                'klass'
            ]
        });
    }
);