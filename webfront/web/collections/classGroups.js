define(function(require) {
        //requirements
        var BaseCollection = require('collections/baseCollection'),
            CatalogGroupModel = require('models/catalogGroup');

        return BaseCollection.extend({
            initialize: function(opt){
                this.classId = opt.classId;
            },
            model: CatalogGroupModel,
            url: function() {
                return baseApiUrl + '/klasses/'+ this.classId  + '/groups'
            }
        });
    }
);