define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('models/product'),
            storeId: null,
            subCategoryId: null,
            url: function(){
                if (this.storeId){
                    return Collection.baseApiUrl + '/stores/' + this.storeId + '/subcategories/' + this.subCategoryId + '/products'
                } else {
                    return Collection.baseApiUrl + '/subcategories/' + this.subCategoryId + '/products'
                }
            },
            fetch: function(){
                if (this.subCategoryId){
                    return Collection.prototype.fetch.apply(this, arguments);
                }
            }
        });
    }
);