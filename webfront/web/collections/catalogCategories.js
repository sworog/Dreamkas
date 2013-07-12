define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogCategoryModel = require('models/catalogCategory');

        return BaseCollection.extend({
            initialize: function(models, options){
                var parentGroupModel = options.parentGroupModel || options.parentModel;

                this.group = options.parentGroupId || parentGroupModel.id;
            },
            model: CatalogCategoryModel,
            url: function() {
                return LH.baseApiUrl + '/groups/'+ this.parentGroupId  + '/categories'
            }
        });
    }
);