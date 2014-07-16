define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('models/group/group'),
            url: Collection.baseApiUrl + '/catalog/groups'
        });
    }
);