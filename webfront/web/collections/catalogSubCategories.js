define(function(require) {
        //requirements
        var Collection = require('kit/collection');

        return Collection.extend({
            model: require('models/catalogSubCategory'),
            initialize: function(models, options){
                this.category = options.category || options.parentModel.id;
                this.group = options.group || options.parentModel.get('group');
            },
            url: function() {
                return LH.baseApiUrl + '/categories/' + this.category + '/subcategories'
            }
        });
    }
);