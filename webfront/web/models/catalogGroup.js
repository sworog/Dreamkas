define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

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