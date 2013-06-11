define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogClassModel = require('models/catalogClass');

        return BaseCollection.extend({
            model: CatalogClassModel,
            url: baseApiUrl + "/klasses"
        });
    }
);