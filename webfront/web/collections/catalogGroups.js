define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogGroupModel = require('models/catalogGroup');

        return BaseCollection.extend({
            initialize: function(models, options){
                console.log(options.parentModel);
                this.parentClassModel = options.parentClassModel || options.parentModel;
            },
            model: CatalogGroupModel,
            url: function() {
                return LH.baseApiUrl + '/klasses/'+ this.parentClassModel.id  + '/groups'
            }
        });
    }
);