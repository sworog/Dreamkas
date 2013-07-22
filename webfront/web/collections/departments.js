define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/department'),
            url: LH.baseApiUrl + '/departments',
            initialize: function(models, options){
                this.store = options.store || options.parentModel.id;
            }
        });
    }
);