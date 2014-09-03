define(function(require) {
        //requirements
        var Model = require('kit/model/model');

        return Model.extend({
            urlRoot: Model.baseApiUrl + '/catalog/groups',
            collections: {
                products: require('collections/products/products')
            },
            saveData: [
                'name'
            ]
        });
    }
);