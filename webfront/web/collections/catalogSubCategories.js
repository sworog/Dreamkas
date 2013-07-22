define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogSubCategoryModel = require('models/catalogSubCategory');

        return BaseCollection.extend({
            initialize: function(models, options){
                this.category = options.category || options.parentModel.id;
                this.group = options.group || options.parentModel.get('group');
            },
            model: CatalogSubCategoryModel,
            url: function() {
                return LH.baseApiUrl + '/categories/' + this.category + '/subcategories'
            }
        });
    }
);