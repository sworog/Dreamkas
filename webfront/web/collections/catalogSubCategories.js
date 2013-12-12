define(function(require) {
        //requirements
        var Collection = require('kit/core/collection');

        return Collection.extend({
            model: require('models/catalogSubcategory'),
            initialize: function(models, options){
                this.category = options.category || options.parentModel.id;
                this.group = options.group || options.parentModel.get('group');
                this.storeId = options.storeId || options.parentModel.get('storeId');
            },
            url: function() {
                if (this.storeId){
                    return LH.baseApiUrl + '/stores/' + this.storeId + '/categories/' + this.category + '/subcategories'
                } else {
                    return LH.baseApiUrl + '/categories/' + this.category + '/subcategories'
                }
            }
        });
    }
);