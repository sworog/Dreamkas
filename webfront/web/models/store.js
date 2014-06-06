define(function(require) {
        //requirements
        var Model = require('kit/model');

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