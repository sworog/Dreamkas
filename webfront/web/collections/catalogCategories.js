define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogCategoryModel = require('models/catalogCategory');

        return BaseCollection.extend({
            initialize: function(models, options){
                this.parentGroupModel = options.parentGroupModel || options.parentModel;
                this.parentGroupId = options.parentGroupId || this.parentGroupModel.id;
            },
            model: CatalogCategoryModel,
            url: function() {
                return LH.baseApiUrl + '/groups/'+ this.parentGroupId  + '/categories'
            }
        });
    }
);