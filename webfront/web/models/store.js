define(function(require) {
        //requirements
        var Model = require('kit/core/model');

        return Model.extend({
            urlRoot: LH.baseApiUrl + '/stores',
            saveData: [
                'number',
                'address',
                'contacts'
            ]
        });
    }
);