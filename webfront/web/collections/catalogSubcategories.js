define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogSubcategoryModel = require('models/catalogSubcategory');

        return BaseCollection.extend({
            initialize: function(models, options){
                this.parentCategoryModel = options.parentCategoryModel || options.parentModel;
            },
            model: CatalogSubcategoryModel,
            url: function() {
                return LH.baseApiUrl + '/categories/' + this.parentCategoryModel.id + '/subcategories'
            }
        });
    }
);