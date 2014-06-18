define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/product'),
            defaults: {
                storeId: null,
                subCategoryId: null
            },
            url: function(){
                if (this.get('storeId')){
                    return Collection.baseApiUrl + '/stores/' + this.get('storeId') + '/subcategories/' + this.get('subCategoryId') + '/products'
                } else {
                    return Collection.baseApiUrl + '/subcategories/' + this.get('subCategoryId') + '/products'
                }
            }
        });
    }
);