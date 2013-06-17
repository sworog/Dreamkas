define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            urlRoot: LH.baseApiUrl + '/klasses',

            saveFields: [
                'name'
            ]
        });
    }
);