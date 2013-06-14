define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            modelName: 'group',
            urlRoot: LH.baseApiUrl + '/groups',

            saveFields: [
                'name',
                'klass'
            ]
        });
    }
);