define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogCategoryModel = require('models/catalogCategory');

        return BaseCollection.extend({
            initialize: function(models, options){
                this.parentCategoryModel = options.parentCategoryModel || options.parentModel;
                this.parentGroupModel = this.parentCategoryModel.parentGroupModel;
            },
            model: CatalogCategoryModel,
            url: function() {
                return LH.baseApiUrl + '/groups/'+ this.parentGroupModel.id  + '/categories/' + this.parentCategoryModel.id + '/subcategories'
            }
        });
    }
);