define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogSubcategoryModel = require('models/catalogSubcategory');

        return BaseCollection.extend({
            initialize: function(models, options){
                this.category = options.category || options.parentModel.id;
                this.group = options.group || options.parentModel.get('group');
            },
            model: CatalogSubcategoryModel,
            url: function() {
                return LH.baseApiUrl + '/categories/' + this.category + '/subcategories'
            }
        });
    }
);