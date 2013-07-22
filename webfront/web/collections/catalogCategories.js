define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/catalogCategory'),
            initialize: function(models, options){
                this.group = options.group || options.parentModel.id;
            },
            url: function() {
                return LH.baseApiUrl + '/groups/'+ this.group  + '/categories'
            }
        });
    }
);