define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('resources/group/model'),
            url: Collection.baseApiUrl + '/catalog/groups'
        });
    }
);