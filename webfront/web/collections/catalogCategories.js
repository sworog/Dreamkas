define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogGroupModel = require('models/catalogGroup');

        return BaseCollection.extend({
            initialize: function(models, options){
                this.parentGroupModel = options.parentGroupModel || options.parentModel;
            },
            model: CatalogGroupModel,
            url: function() {
                return LH.baseApiUrl + '/groups/'+ this.parentGroupModel.id  + '/groups'
            }
        });
    }
);