define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            modelName: 'klass',
            urlRoot: LH.baseApiUrl + '/klasses',

            saveFields: [
                'name'
            ]
        });
    }
);