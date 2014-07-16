define(function(require) {
        //requirements
        var Model = require('kit/model/model');

        return Model.extend({
            urlRoot: Model.baseApiUrl + '/catalog/groups',
            saveData: [
                'name'
            ]
        });
    }
);