define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogCategoryModel = require('models/catalogCategory');

        return BaseCollection.extend({
            initialize: function(models, options){
                this.parentGroupModel = options.parentGroupModel || options.parentModel;
            },
            model: CatalogCategoryModel,
            url: function() {
                return LH.baseApiUrl + '/groups/'+ this.parentGroupModel.id  + '/groups'
            }
        });
    }
);