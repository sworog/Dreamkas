define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogSubcategoryModel = require('models/catalogSubcategory');

        return BaseCollection.extend({
            initialize: function(models, options){
                var parentCategoryModel = options.parentCategoryModel || options.parentModel;
                this.category = options.parentCategoryId || parentCategoryModel.id;
                this.group = options.parentGroupId || parentCategoryModel.get('group');
            },
            model: CatalogSubcategoryModel,
            url: function() {
                return LH.baseApiUrl + '/categories/' + this.parentCategoryId + '/subcategories'
            }
        });
    }
);