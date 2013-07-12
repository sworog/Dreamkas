define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogCategoryModel = require('models/catalogCategory');

        return BaseCollection.extend({
            initialize: function(models, options){
                this.group = options.group || options.parentModel.id;
            },
            model: CatalogCategoryModel,
            url: function() {
                return LH.baseApiUrl + '/groups/'+ this.group  + '/categories'
            }
        });
    }
);