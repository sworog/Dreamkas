define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel'),
            CatalogCategoriesCollection = require('collections/catalogCategories');

        return BaseModel.extend({
            modelName: 'catalogGroup',
            urlRoot: LH.baseApiUrl + '/groups',
            defaults: {
                categories: []
            },
            initData: {
                categories: CatalogCategoriesCollection
            },
            saveFields: [
                'name'
            ]
        });
    }
);