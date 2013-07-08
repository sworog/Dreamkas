define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogGroupModel = require('models/catalogGroup');

        return BaseCollection.extend({
            model: CatalogGroupModel,
            url: LH.baseApiUrl + "/groups"
        });
    }
);