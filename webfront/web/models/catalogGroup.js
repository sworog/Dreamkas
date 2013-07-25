define(function(require) {
        //requirements
        var Model = require('kit/model');

        return Model.extend({
            modelName: 'catalogGroup',
            urlRoot: LH.baseApiUrl + '/groups',
            initData: {
                categories: require('collections/catalogCategories')
            },
            saveFields: [
                'name',
                'retailMarkupMax',
                'retailMarkupMin'
            ]
        });
    }
);