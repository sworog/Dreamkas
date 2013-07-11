define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogSubcategoryModel = require('models/catalogSubcategory');

        return BaseCollection.extend({
            initialize: function(models, options){
                this.parentCategoryModel = options.parentCategoryModel || options.parentModel;
                this.parentCategoryId = options.parentCategoryId || this.parentCategoryModel.id;
                this.parentGroupId = options.parentGroupId || this.parentCategoryModel.get('parentGroupId');
            },
            model: CatalogSubcategoryModel,
            url: function() {
                return LH.baseApiUrl + '/categories/' + this.parentCategoryId + '/subcategories'
            }
        });
    }
);