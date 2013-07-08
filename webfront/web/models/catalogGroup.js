define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel'),
            CatalogGroups = require('collections/catalogGroups');

        return BaseModel.extend({
            urlRoot: LH.baseApiUrl + '/groups',
            initData: {
                groups: CatalogGroups
            },
            saveFields: [
                'name'
            ]
        });
    }
);