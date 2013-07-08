define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel'),
            CatalogCategories = require('collections/catalogCategories');

        return BaseModel.extend({
            urlRoot: LH.baseApiUrl + '/groups',
            initData: {
                categories: CatalogCategories
            },
            saveFields: [
                'name'
            ]
        });
    }
);