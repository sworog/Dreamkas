define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/catalogGroup'),
            url: LH.baseApiUrl + "/groups"
        });
    }
);