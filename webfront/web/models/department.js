define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            modelName: 'department',
            urlRoot: LH.baseApiUrl + '/departments',
            saveFields: [
                'number',
                'name'
            ]
        });
    }
);